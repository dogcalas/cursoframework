<?php
         /**
	 * Fichero de inicio al PEP
	 * 
	 * @author Quirog@
	 * @copyright Acaxia Cuba
	 * @version 1.0
	 */     

require_once('../../saml/lib/_autoload.php');
//Direccion de la servidora
	$dir_index = __FILE__;

	//Direccion del fichero de configuracion
	$config_file = substr($dir_index, 0, strrpos($dir_index, 'web')) . 'apps/comun/config.php';
    
	if (!file_exists($config_file)) //Si no existe el fichero de configuracion
	{
		//Se dispara una excepcion
		throw new Exception('El fichero de configuracion no existe');
	}
	elseif (!is_readable($config_file)) //Si no se puede leer
	{
		//Se dispara una excepcion
		throw new Exception('No se pudo leer el fichero de configuracion. Acceso denegado.');
	}
	else //Si existe el fichero y se puede leer
	{
		//Se inicializa la variable de configuración
		$config = array();

		//Se incluye el fichero
		include_once ($config_file);
		
		if (!isset($config['include_path']))
			throw new Exception('El framework no esta configurado correctamente.');

		//Se inicializa el include path de php a partir de la variable de configuracion
		set_include_path($config['include_path']);
		
		//Se inicia la carga automatica de clases y ficheros
		$loader_file = 'Zend/Loader/Autoloader.php';
		if (!@include_once($loader_file))
			throw new Exception('El framework no esta configurado correctamente.');
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->setFallbackAutoloader(true);              
		//Se inicia la aplicacion
		$as = new SimpleSAML_Auth_Simple('ecotec');
		$as->requireAuth();     
	}
 
$response=$_REQUEST['ResponseXML']; 

 require_once '../../../lib/ZendExt/XACML/PEP/MainPeP.php';
 //require_once '../../../lib/ZendExt/Exception.php';
$pep= ZendExt_XACML_PEP_MainPeP::getInstance();
 try {
     $pep->ValidateAuthoriceResponseXML($response);
         } catch (Exception $exc) {
             echo $exc->getMessage();
         }  
       
?>
