<?php
	/**
	 * Clase para la implementación de los publicadores de las trazas en bases de datos
	 *   
	 * @author Omar Antonio Díaz Peña.
	 * @copyright ERP-Cuba.
	 * @package Traces
	 * @subpackage Publisher
	 * @version 1.0.0 
	 */

	class ZendExt_Trace_Publisher_Db {
		/**
		 * Instancia del Xml de config de las trazas.
		 *
		 * @var SimpleXmlElement.
		 */
		private $_xml;
		
		/**
		 * Conexión a la BD de trazas
		 *
		 * @var Doctrine_Connection
		 */
		protected $_conn;
		
		/**
		 * Conexión previa a la BD.
		 *
		 * @var Doctrine_Connection
		 */
		//private $_prev_conn;
		
		/**
		 * Constructor
		 * 
		 * @return void.
		 */
		function __construct () {
			$this->_xml = ZendExt_FastResponse :: getXML ('traceconfig');
			//$transactionManager = ZendExt_Aspect_TransactionManager::getInstance();
			$configApp = new ZendExt_App_Config();
			$configApp->addBdToConfig('traza', 'traza');			
			//$this->_conn = $transactionManager->openConections('traza');
		}
		
		/**
		 * Método de persistencia de los logs en la BD.
		 *
		 * @param ZendExt_Trace_Container_Log $pPublisher
		 */
		function save ($pContainer) 
		{
			//echo'<pre>';print_r($pContainer);die;
			$className = get_class ($pContainer); //Obtengo el nombre de la clase del contenedor 			
			$tmp = $this->_xml->containers->$className; //Busco su rama dentro del Xml de trazas
			$enabled = (int) $tmp ['enabled'];			

			if ($enabled) { //Si la traza está habilitada
				$doctrine = (string) $tmp ['doctrine']; //Obtengo el nombre de la clase de Doctrine
											   //que persiste ese tipo de traza
				
				/*
					Lleno la instancia de Doctrine con los datos de la clase padre
					(ZendExt_Trace_ContainerLog)
				*/	
				//$tm = ZendExt_Aspect_TransactionManager::getInstance();
				//$conn = $tm->openConections();
				$obj = new $doctrine ();
				$obj->fecha =  $pContainer->getDate();
				$obj->hora =  $pContainer->getTime();
				$obj->usuario = $pContainer->getUser();
				$obj->ip_host = $pContainer->getIp_Host();
				$obj->idrol = $pContainer->getRol();
				$obj->iddominio = $pContainer->getDominio();
				$obj->idestructuracomun = $pContainer->getCommonStructure();
				$obj->idtipotraza = $pContainer->getIdType();
				$obj->rol = $pContainer->getDenomRol();
				$obj->dominio = $pContainer->getDenomDominio();
				$obj->estructuracomun = $pContainer->getDenomEstructura();
				
				//echo'<pre>';print_r($obj);die;
				
				/* 	Lleno la instancia de Doctrine con los atributos privados de cada
					tipo de traza.  
				 */

				try {
						foreach ($tmp->atts->children () as $var) {
							$att       = (string) $var ['att'];					
							$method    = (string) $var ['method'];
							
							$obj->$att = $pContainer->$method ();							
						}
				}
				
				catch (Exception $e) {
					throw new ZendExt_Exception('TRACE050', $e);					
				}
			
				try {
					$obj->save ();//$this->_conn);					
				}
				catch (Exception $e) {
                    print_r($e);die;
                    throw new ZendExt_Exception('TRACE001', $e);					
				}	
			}
		}
	}
?>
