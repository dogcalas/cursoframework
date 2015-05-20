<?php
/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */

	//Creando un cliente de soap en modo <sin wsdl>
	echo '<pre>';
	try {	
		//Creando un cliente de soap en modo <sin wsdl>
		/*$config = array();
		$config['location'] = 'http://localhost/PACSOFT/aplicaciones/seguridad/services.php';
		$config['uri'] = 'http://localhost/PACSOFT/aplicaciones/seguridad/services.php'; 
		$soapCliente = new SoapClient(null, $config);
		print_r($soapCliente->authenticateUser('instalacion', 'instalacion'));
		die;*/
		
		//Creando un cliente de soap en modo <wsdl>
		$wsdl = 'http://10.7.15.65/repo2/aplicaciones/seguridad/services/SeguridadSoapService.wsdl';
		$soapCliente = new SoapClient($wsdl, array());
		$identidad = 100000001;
		//Prueba de la autenticacion
		$certificado = $soapCliente->authenticateUser('instalacion', 'instalacion');
		if ($certificado) {		
			echo '<pre>Servicio de autenticacion.<br>'; print_r($certificado);
			//Prueba de la carga de los dominios
			echo '<hr>Servicio para cargar el dominio de entidades a los que el usuario tiene acceso.<br>'; print_r($soapCliente->loadDomain($certificado,0 ));
			
			//Prueba de la carga del perfil de usuario
			echo '<hr>Servicio para cargar el perfil de usuario.<br>'; print_r($soapCliente->getProfile($certificado));
			
			//Prueba de la carga de los sistemas para una entidad
			echo '<hr>Servicio para cargar los sistemas.<br>'; print_r($soapCliente->getSystems($certificado, $identidad));
			
			//Prueba de la carga de las funcionalidades par un sistema en una entidad
			$idsistema = 10000000002;
			echo '<hr>Servicio para cargar los sistemas y las funcionalidades.<br>'; print_r($soapCliente->getSystemsFunctions($certificado, $idsistema, $identidad));
			
			//Prueba de la carga de los sistemas y modulos para una entidad
			echo '<hr>Servicio para cargar los sistemas y los modulos.<br>'; print_r($soapCliente->getSystemsDesktopModules($certificado, $identidad));
			
			//Prueba de la carga de los sistemas, modulos y funcionalidades para una entidad
			echo '<hr>Servicio para cargar los sistemas, los modulos y las funcionalidades.<br>'; print_r($soapCliente->getSystemsFunctionsDesktopModules($certificado, $identidad));
		}
	} catch (SoapFault $e) {
		echo '<hr>' . $e->faultcode . '<br>' . $e->faultstring;
	}
