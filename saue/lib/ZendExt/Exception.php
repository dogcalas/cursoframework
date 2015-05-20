<?php
	/**
	 * ZendExt_Exception
	 * 
	 * Gestor vertical de excepciones
	 * 
	 * @author Omar Antonio Diaz Peña
	 * @package ZendExt
	 * @copyright UCID-ERP Cuba.
	 * @version 1.0-0
	 */
	class ZendExt_Exception extends Zend_Exception 
	{
		/**
		 * Identificador de la excepcion
		 * 
		 * @var string
		 */
		private $id_exception;		
		
		/**
		 * Descripcion de la excepcion
		 * 
		 * @var string
		 */
		private $description;
		
		/**
		 * Tipo de excepcion
		 * 
		 * @var string
		 */
		private $type;
		
		/**
		 * Excepcion interna, cualquier excepcion no declarada
		 * 
		 * @var Exception
		 */
		private $inner_exception = null;
		
		/**
		 * Traza de la excepcion
		 * 
		 * @var string
		 */
		private $trace;
		
		/**
		 * Instancia de excepciones segun el tipo de excepciones
		 * 
		 * @var ZendExt_Exception_Log|ZendExt_Exception_LogPresentacion|
		 * 		ZendExt_Exception_Blind|Ext_Exception_Presentacion
		 */
		private $instance;
		
		/**
		 * Devuelve el identificador de la excepcion
		 * 
		 * @return string, identificador de la excepcion
		 */
		public function getIdException ()
		{
			return $this->id_exception;
		}	
		
		/**
		 * Devuelve la excepcion interna
		 * 
		 * @var Exception, excepcion interna
		 */
		public function getInnerException ()
		{
			return $this->inner_exception;			
		}
		
		/**
		 * Devuelve la descripcion de la excepcion
		 * 
		 * @return string, descripcion de la excepcion
		 */
		public function getDescription ()
		{
			return $this->description;
		}
		
		/**
		 * Devuelve el tipo de excepcion
		 * 
		 * @return string, tipo de excepcion
		 */
		public function getType ()
		{
			return $this->type;
		}
		
		/**
		 * Devuelve la clase que disparo la excepcion declarada
		 * 
		 * @return string, clase que disparo la excepcion
		 */
		public function getClass ()
		{
			$traces = parent::getTrace();
			return $traces [count($traces) - 1] ['class'];
		}
		
		/**
		 * Devuelve el metodo donde se disparo la excepcion declarada
		 * 
		 * @return string - nombre del donde se disparo la excepcion
		 */
		public function getMethod ()
		{
			$traces = parent::getTrace();
			return $traces [count($traces) - 1] ['function'];
		}

		/**
		 * Maneja la excepcion y en dependencia del tipo la muestra o la guarda en un formato
		 * 
		 * @return void
		 */
		public function handle ()
		{
			$this->instance->handle ();			
		}
		
		/**
		 * Devuelve la instancia de la excepcion disparada segun el tipo
		 * 
		 * @return ZendExt_Exception_Log|ZendExt_Exception_LogPresentacion|
		 * 		   ZendExt_Exception_Blind|Ext_Exception_Presentacion
		 */
		public function getInstance ()
		{
			return $this->instance;
		}
		
		/**
		 * Constructor de la clase, dispara la excepcion declarada en el xml de excepciones
		 * 
		 * @param string $pIdException - Identificador de la excepcion
		 * @param Excepcion $pInnerException - excepcion interna , cualquier excepcion no declarada
		 */
		public function ZendExt_Exception ($pIdException, $pInnerException = null/*, $framework = false*/)
		{
			//if (!$framework)
				$xml  = ZendExt_FastResponse :: getXML('excepciones');
			/*else
				$xml  = ZendExt_FastResponse :: getXML('frameworkexceptions');*/
            //print_r($xml);die('ZendExt_Exception');
			$xmlException = $xml->$pIdException;

			if ($xmlException->tipo) {
				$this->id_exception = $pIdException;
				$this->inner_exception = $pInnerException;
				$this->type = (string) $xmlException->tipo;
				$registro = Zend_Registry::getInstance();
				$lang = $registro->session->perfil['idioma'];//pq no leen el idioma del xml
				//if (!$lang)
					//$lang = 'es';
				$this->description = $xmlException->$lang->descripcion;
				$exception_types = ZendExt_FastResponse::getXML('tipos_excepciones');
				$currType = $this->type;
				$exception_class = (string) $exception_types->$currType;
				$this->instance = new $exception_class ($this);
				parent::__construct((string) $xmlException->$lang->descripcion, $pIdException);
			}
			else
				throw new ZendExt_Exception('NODEFINED', new ZendExt_Exception_NotDefined ("Exception $pIdException not defined."));
		}	
		
		/**
		 * Devuelve el modulo que disparo la excepcion
		 * 
		 * @return string - Modulo que disparo la excepcion
		 */
		public function getModule() {
			$explodeDirModule = explode(DIRECTORY_SEPARATOR, $this->getFile());
			$count = count($explodeDirModule);
			for($i = $count; $i > 0; $i--) {
				if ($explodeDirModule[$i - 1] == 'index.php')
					return $explodeDirModule[$i - 2];
			}
			return null;
		}
	}
