<?php
	/**
	 * ZendExt_App_Service
	 * 
	 * Gestor de la aplicacion
	 * 
	 * @author Yoandry Morejon Borbon
	 * @package ZendExt
	 * @copyright UCID-ERP Cuba
	 * @version 1.0-0
	 */
	class ZendExt_App_Service extends ZendExt_App_External
	{		
		
		/**
		 * Inicializa la aplicacion y controla las excepciones disparadas por el sistema
		 * 
		 * @param array $config - arreglo con la configuracion de la aplicacion
		 * @throws ZendExt_Exception - dispara las excepciones no controladas en tiempo de desarrollo.
		 * @return void
		 */
		public function init($config)
		{
			try
			{
				$this->initApp($config);
			}
			catch (Exception $e)
			{
				//throw $e;
				throw new SoapFault($e->getMessage(), 'SERVER');
			}
		}
		
		/**
		 * Inicializa la aplicacion
		 * 
		 * @param array $config - arreglo con la configuracion de la aplicacion
		 * @throws ZendExt_Exception - dispara las excepciones no controladas en tiempo de desarrollo.
		 * @return void
		 */
		protected function initApp($config)
		{
			try //Control de Excepciones
			{
				//Se inicializa el registro
				$this->initRegister();
				//Se inicializa la configuracion de la aplicacion
				$this->initConfig($config);
				//Se inicializa la configuracion de la session
				$this->initSession(); //Aspect OKP
				//Se inicializa la conexion
				$this->initConexion(); //Aspect OKP
				//Se actualiza el registro
				$this->updateRegister();
			}
			catch (Exception $e) //Si se captura una excepcion
			{
				if ($e instanceof ZendExt_Exception) //Si la excepcion capturada es de ZendExt
				{ 
					//$e->handle (); //Se dispara la excepcion
					throw new SoapFault($e->getMessage(),'SERVER');
				}
				else {//Si es una excepcion no controlada
					$e = new ZendExt_Exception('NOCONTROLLED', $e); //Se dispara una excepcion controlada
					$aspectxml = ZendExt_FastResponse::getXML('aspect');
					if ($aspectxml->failedTraceAction['active'] == 'true')
						ZendExt_Aspect::getInstance()->failedTraceAction($e);
					//throw $e;
					throw new SoapFault($e->getMessage(),'SERVER');
				}
			}
		}
	}
