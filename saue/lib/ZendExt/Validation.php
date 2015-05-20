<?php
	/**
	 * ZendExt_Validation
	 * 
	 * Validacion de negocio y tipos de datos.
	 * 
	 * @author: Oiner Gomez Baryolo
	 * @copyright UCID-ERP Cuba
	 * @package: ZendExt
	 * @version 1.0-0
	 */
	class ZendExt_Validation
	{
		/**
		 * Constante definida para disparar las excepciones en 
		 * tiempo de explotacion
		 */
		const VALIDATION_EXCEPTION_ACTIVE = false;
		
		/**
		 * ValidateAction
		 * 
		 * Validacion de una accion declarada en el xml de validaciones
		 * 
		 * @param string $appName, nombre del sistema, subsistema, modulo...
		 * @param string $controllerName, nombre del controlador
		 * @param string $actionName, nombre de la accion
		 * @throws ZendExt_Exception - excepcion declarada en el xml de excepciones
		 * @return boolean, retorna true si no se dispara una excepcion durante la validacion
		 */
		public function ValidateAction($appName, $controllerName, $actionName)
		{
			if (isset($_GET)) //Si se estan enviando los datos por GET
	  			//Se dispara una excepcion declarada en el xml de excepciones
	  			throw new ZendExt_Exception('EV004');
			//Se busca la accion que se desea validar dentro del xml de validaciones
			$action = $this->SearchAction($appName, $controllerName, $actionName);
			if(is_object($action)) //Si es un objeto
			{
				//Se validan los datos para la accion segun el tipo declarado en el xml
			    $this->ValidateType($action);
			    $this->CheckPrecondition($action);
			}						
			return true;	  
		}	
	
		/**
		 * SearchAction
		 * 
		 * Busqueda dentro del xml de validaciones la accion que desea validar
		 * 
		 * @param string $appName, nombre del sistema, subsistema, modulo...
		 * @param string $controllerName, nombre del controlador
		 * @param string $actionName, nombre de la accion
		 * @throws ZendExt_Exception - se disparan excepciones declaradas en el xml de validacion
		 * @return object, retorna un objeto
		 */
		public function SearchAction($appName, $controllerName, $actionName)
		{
			//Se carga el xml de validaciones
			$xml = ZendExt_FastResponse::getXML('validaciones');
			if($xml instanceof SimpleXMLElement) //Si el xml fue cargado correctamente.
			{
				//Se recorre el xml buscando el nombre de la aplicacion.
				foreach ($xml as $apps)
				{
					if($apps['nombre'] == $appName) //Si la aplicacion esta declarada en el xml.
					{   
						//Se busca el controlador.
						foreach ($apps as $controllers)
						{
							if($controllers['nombre'] == $controllerName) //Si el controlador esta declarado
							{ 
								//Se busca la accion que se desea validar
								foreach ($controllers as $actions)
								{ 	
									if($actions['nombre'] == $actionName) //Si la accion esta declarada.									
										return $actions; //Se retorna la accion.
								}
								if (!self::VALIDATION_EXCEPTION_ACTIVE)
									return true;
								else
									//Si no se encuentra la accion se dispara una excepcion
									throw new ZendExt_Exception('EV003');
							}
						}
						if (!self::VALIDATION_EXCEPTION_ACTIVE)
							return true;
						else
							//Si no se encuentra el controlador se dispara una excepcion
							throw new ZendExt_Exception('EV002');
					}
				}
				if (!self::VALIDATION_EXCEPTION_ACTIVE)
					return true;
				else
					//Si no se encuentra la aplicacion se dispara una excepcion
					throw new ZendExt_Exception('EV001');
			}
			else {
				if (!self::VALIDATION_EXCEPTION_ACTIVE)
					return true;
				else
					//Si no se carga el xml se dispara una excepcion
					throw new ZendExt_Exception('XML001');
			}
		}
		
		/**
		 * ValidateType
		 * 
		 * Validacion de los datos segun el tipo de dato declarado 
		 * en el xml para la accion especifica.
		 * 
		 * @param object $action, accion
		 * @throws ZendExt_Exception - se disparan excepciones declaradas en el xml de excepciones
		 * @return void
		 */
		public function ValidateType($action)
	  	{
	  		if($action->tipos) //Si la accion requiere validacion de tipos
	  		{
	  			//Se recorren los tipos
				foreach ($action->tipos as $values)
				{
					//Se recorren los datos para realizar la validacion de los mismos
					foreach ($values as $value)
					{ 			
						$nameField = (string) $value['nombre'];
						$typeField = (string) $value;
						$postField = trim($_POST["$nameField"]);
						$not_null = (string) $value['no_nulo'];
						
						//Si lo que viene por POST esta vacio y es un campo obligatorio
						if($postField == '' && $not_null == 'true')
						{
							$null_error = (string) $value['null_error'];
							$error_code = ($null_error)?$null_error:'EV005';
							//Se dispara una excepcion declarada en el xml de excepciones
							throw new ZendExt_Exception($error_code);
						}
						elseif ($postField != '')
						{
							//Se carga el XML de expresiones regulares
	  						$expressions = ZendExt_FastResponse::getXML('expresiones');
							if($expressions) //Si se cargo el xml de expresiones regulares
							{
							  	$typeRegexp = utf8_encode($expressions->$typeField);
							  	//Verifico si hay una expresion regular para este tipo	
							  	if($typeRegexp)
							  	{
							  		if(!preg_match('/'.$typeRegexp.'/', $postField)) //Si el campo enviado no es valido 
							  		{
							  			$error = utf8_decode($value['error']);
							  			$error_code = ($error)?$error:'EV006';
										//Se dispara una excepcion declarada en el xml de excepciones
										throw new ZendExt_Exception($error_code);
							  		}	
							  	}
							  	else
							  		//Se dispara una excepcion declarada en el xml de excepciones
	  								throw new ZendExt_Exception('EV007');
							}
						}
					}
		  		}
		  		die();
		  	}
	  	}
		  
	  	/**
	  	 * CheckPrecondition
	  	 * 
	  	 * Verifica las precondiciones de la accion
	  	 * 
	  	 * @param object $action, accion
		 * @throws ZendExt_Exception - se disparan excepciones declaradas en el xml de excepciones
		 * @return void
	  	 */
	  	public function CheckPrecondition($action)
	  	{
	  		//Dada una accion recorro todas las precondiciones
			foreach ($action->precondicion as $preconditions)
			{
				//Por cada precondicion recorro todas las validaciones que existen dentro de el xml de excepciones
				foreach ($preconditions->validador as $validators)
				{
					//En cada validacion dada la clase se crea una instancia de ella.
			    	$class = utf8_decode($validators->clase['nombre']);
			    	$method = utf8_decode($validators->metodo['nombre']);
			    	if (!$class && VALIDATION_EXCEPTION_ACTIVE) //Si no hay una clase declarada para la precondicion
						//Se dispara una exception declarada en el xml de excepciones
						throw new ZendExt_Exception('EV008');
					elseif (!class_exists($class) && VALIDATION_EXCEPTION_ACTIVE) //si la clase no existe
						//Se dispara una exception declarada en el xml de excepciones
						throw new ZendExt_Exception('EV009');
					elseif (!$method && VALIDATION_EXCEPTION_ACTIVE) //si el metodo no esta declarado
						//Se dispara una exception declarada en el xml de excepciones
						throw new ZendExt_Exception('EV010');
					elseif (method_exists($object, $method) && VALIDATION_EXCEPTION_ACTIVE) //si el metodo no existe
						//Se dispara una exception declarada en el xml de excepciones
						throw new ZendExt_Exception('EV011');
					else
			    	{	
                		$object = new $class();		
						if(!$object->$method()) //Si accion realizada no es valida
						{
							$error = utf8_decode($validators->error);
							$error_code = ($error)?$error:'EV012';
							//Se dispara una exception declarada en el xml de excepciones
							throw new ZendExt_Exception($error_code);
						}
			    	}							
				}
	  		}
	  		return true;
    	}
	}
