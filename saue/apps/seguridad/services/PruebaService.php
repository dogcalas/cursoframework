<?php
/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien García Tejo
 * @author Julio Cesar García Mosquera  
 * @version 1.0-0
 */
	//Direccion del fichero de configuracion
	require_once (substr(__FILE__, 0, strrpos(__FILE__, 'aplicaciones')) . 'aplicaciones/comun/config_service.php');
	
	/**
	 * Probando los servicios web
	 */
	class prueba {
		/**
		 * Probando los servicios web
		 * 
		 * @param Prueba $certificate - Certificado o token de seguridad
		 * @return string - Probando los servicios web.
		 */
		public function getPrueba ($certificate) {
			global $module_reference;
			$a = new stdClass();
			$a->certificate = $certificate;
			$a->prueba = 'SIRVIO!!!';
			//return $a;
			return $module_reference;
		}
	}

	ini_set('soap.wsdl_cache_enabled', 0);
	$soapServer = new SoapServer(null, array('uri'=>'http://localhost/PACSOFT/aplicaciones/portal/services/PruebaService.php'));
	$soapServer->setClass('prueba');
	$soapServer->handle();
	/*$p = new prueba();
	echo '<pre>'; print_r($p->getPrueba('182378991827'));*/
