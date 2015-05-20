<?php
/**
 * Fichero de configuracion del modulo de gestion del Portal
 * @author Equipo Aquitectura ERP-CUBA, Grupo I+D UCID
 * @version 1.0-0
 */

	//Direccion de la servidora
	$dir_index = $_SERVER['SCRIPT_FILENAME'];
	
	//Direccion del dirctorio donde estan las aplicaciones
	$dir_aplication = substr($dir_index, 0, strrpos($dir_index, 'web')) . 'apps';
		
	//Direccion del fichero de configuracion
	$dir_config = substr($dir_index, 0, strrpos($dir_index, 'web')) . '/apps/config.php';
	
	//Inclusion del Fichero de Configuracion del Marco de Trabajo
	include($dir_config);

	$config ['uri_aplication'] = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], 'web')) . 'web';
	$config ['dir_aplication'] = $dir_aplication;
	
	//Configuracion de los xml
	$dirSistema = $dir_aplication . '/';
	$dirConfigModules = $dirSistema . 'comun/recursos/xml/';
	$config ['xml']['excepciones'] 		 	= $dirConfigModules . 'exception.xml';
	$config ['xml']['validation'] 	 		= $dirConfigModules . 'validation.xml';
	$config ['xml']['managerexception'] 	= $dirConfigModules . 'managerexception.xml';
	$config ['xml']['weaver'] 			 	= $dirConfigModules . 'weaver.xml';
	$config ['xml']['ioc'] 			 	 	= $dirConfigModules . 'ioc.xml';
	$config ['xml']['modulesconfig'] 		= $dirConfigModules . 'modulesconfig.xml';
$config ['xml']['mailconfig'] = $dirConfigModules . 'mailconfig.xml';
	$config ['xml']['rules'] 		 		= $dirConfigModules . 'rules.xml';
	$config ['xml']['nomconfig'] 			= $dirConfigModules . 'nomconfig.xml';
	$config ['xml']['subsistemas'] 			= $dirConfigModules . 'subsistemas.xml';
	$config ['xml']['subsistemasinstalados']= $dirConfigModules . 'subsistemasinstalados.xml';
	$config ['xml']['dependenciasinstaladas']= $dirConfigModules . 'dependenciasinstaladas.xml';
    $config ['xml']['configuracionservidor']= $dirConfigModules . 'configuracionservidor.xml';  
	$config ['xml']['actualizar']			= $dirConfigModules . 'actualizar.xml';
        $config ['xml']['bundles']			= $dirConfigModules . 'bundles.xml';
        $config ['xml']['configConection']			= $dirSistema . 'seguridad/comun/recursos/xml/configConection.xml';
	
	$module_reference = substr($dir_index, strrpos($dir_index, 'web') + strlen('web/'), strrpos($dir_index, 'index.php') - strrpos($dir_index, 'web') - strlen('web/') - 1);
	$array_module = explode('/', $module_reference);
	$config ['module_reference'] = $module_reference;
	$config ['xml']['iocinter']  = $dirSistema . $array_module[0] . '/comun/recursos/xml/ioc.xml';

	$config['expimp'] = $dir_aplication . '/comun';

	$config['enable_security'] = true;
	
	        /*
         * Workflow purpose
         */
        $preffix = substr($dir_index, 0, strrpos($dir_index, 'web'));        
        $workflowIncludePath = $preffix . "lib/ZendExt/WF/WFExecute/lib/workflow/Base/src/base.php";
        include ($workflowIncludePath);
        spl_autoload_register(array('ezcBase', 'autoload'));
