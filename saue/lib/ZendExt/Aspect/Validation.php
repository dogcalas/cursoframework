<?php
/**
 * ZendExt_Aspect_Validation
 * 
 * Valida las precondiciones y parametros de una accion segun su especificacion
 * en el fichero validation.xml
 * 
 * @author: Oiner Gomez Baryolo
 * @copyright UCID-ERP Cuba
 * @package: ZendExt
 * @version 1.0-0
 */
final class ZendExt_Aspect_Validation implements ZendExt_Aspect_ISinglenton {

	/**
	 * Constructor de la clase, es privado para impedir que pueda 
	 * ser instanciado y de esta forma garantizar que la instancia 
	 * sea un singlenton
	 * 
	 * @return void
	 */
	private function __construct() {
	
	}
	
	/**
	 * Obtencion de la instancia de la clase, ya que esta no puede ser 
	 * instanciada directamente debido a que es un singlenton
	 * 
	 * @return ZendExt_Aspect_Validation - instancia de la clase
	 */
	static public function getInstance() {
		static $instance;
		if (!isset($instance))
			$instance = new self();
		return $instance;
	}
	
	/**
	 * Validacion de los parametros utilizados en una accion 
	 * y de sus precondiciones
	 * 
	 * @param SimpleXMLElement $action, elemento xml que representa la accion
	 * @throws ZendExt_Exception - excepcion declarada en el xml de excepciones
	 * @return void
	 */
	public function validateAction(SimpleXMLElement $action = null) {
		if (!isset($_POST)) //Si no se enviaron los valores por POST
  			$this->Exception(null, 'EV004');
  		//Si es un objeto
		if ($action instanceof SimpleXMLElement) {
			//Se validan los datos para la accion segun el tipo declarado en el xml
		    $this->checkPreconditions($action);
			$this->validateParams($action);
		}
	}
	
	/**
	 * Validacion de los parametros utilizados por una accion segun el 
	 * tipo de dato.
	 * 
	 * @param SimpleXmlElement $action, accion a la cual se le van a validar los parametros
	 * @return void
	 */
	private function validateParams(SimpleXMLElement $action) {
		if ($action->param) {
			//Se recorren los params para realizar la validacion de los mismos
			foreach ($action->param as $param)
			{
				$nameField = (string) $param['name'];
				$typeField = (string) $param['type'];
				$postField = trim($_POST["$nameField"]);
				$not_null = (bool) $param['no_nulo'];
				//Si lo que viene por POST esta vacio y es un campo obligatorio
				if($postField == '' && $not_null) {
					$null_error = (string) $param['null_error'];
					$this->Exception($null_error, 'EV005');
				}
				elseif ($postField != '') {
					//Se obtienen las expresiones regulares
	  				$expressions = $this->getRegEspressions();
	  				//Si se cargo el xml de expresiones regulares
					if($expressions) {
					  	$typeRegexp = (string) $expressions->$typeField;
					  	//Verifico si hay una expresion regular para este tipo	
					  	if($typeRegexp) {
					  		//Si el campo enviado no es valido
					  		if(!eregi($typeRegexp, $postField)) {
					  			$type_error = (string) $param['type_error'];
					  			$this->Exception($type_error, 'EV006');
					  		}	
					  	}
					  	else {
					  		$this->Exception(null, 'EV007');
					  	}
					}
				}
			}
		}
  	}
  	
	/**
  	 * Verifica las precondiciones de la accion
  	 * 
  	 * @param SimpleXMLElement $action, accion a la cual se le van a chequear las precondiciones
	 * @return void
  	 */
  	private function checkPreconditions(SimpleXMLElement $action)
  	{
  		if ($action->precondition) {
	  		//Dada una accion recorro todas las precondiciones
			foreach ($action->precondition as $preconditions) {
				//Por cada precondicion recorro todas las validaciones que existen dentro de el xml de excepciones
				foreach ($preconditions->validator as $validator) {
					//En cada validacion dada la clase se crea una instancia de ella.
			    	$class = (string) $validator['class'];
			    	$method = (string) $validator['method'];
			    	if (!$class) //Si no hay una clase declarada para el validador
						$this->Exception(null, 'EV008');
					elseif (!class_exists($class)) //si la clase no existe
						$this->Exception(null, 'EV009');
					elseif (!$method)//si el metodo no esta declarado
						$this->Exception(null, 'EV010');					
					$object = new $class();
					if (!method_exists($object, $method)) //si el metodo no existe
						$this->Exception(null, 'EV011');
					else {
						//Si la accion realizada no es valida
						if(!$object->$method()) {
							$error_code = (string) $validator['error'];
							$this->Exception($error_code, 'EV012');
						}
			    	}							
				}
	  		}
  		}
    }

    /**
     * Obtencion de las expresiones regulares utilizando el componente de
     * respuesta rapida ZendExt_FastResponse
     * 
     * @return SimpleXMLElement - esxpresiones regulares
     */
    private function getRegEspressions()
    {
    	//Si no se han cargado las expresesiones regulares se cargan
  		static $expressions;
  		if (!$expressions)
  			$expressions = ZendExt_FastResponse::getXML('expresiones');
  			//$expressions = simplexml_load_file('Aspect/xml/expressions.xml');
  		return $expressions;
    }
    
    /**
     * Dispara una excepcion controlada por el sistema segun el codigo, 
     * si en el primer codigo se pasa null se dispara la excepcion con
     * el segundo
     * 
     * @throws ZendExt_Exception - excepciones controladas por el sistema 
     */
  	private function Exception($error_code, $aux_code = null) {
  		if ($error_code)
  			throw new ZendExt_Exception($error_code);
  		elseif ($aux_code)
  			throw new ZendExt_Exception($aux_code, null);
  	}
}
