<?php

/**
 * Instalador
 *
 * @package SIGIS
 * @copyright ERP Cuba
 * @author Oiner Gomez Baryolo
 * @author René R. Bauta Camejo
 * @version 1.5-0
 */
include('Sql.php');

class IndexController extends ZendExt_Controller_Secure
{

    function init()
    {

        parent::init(false);
    }

    function indexAction()
    {
        $this->render();
    }

    //Crea la base de datos y verifica si se creó correctamente.

    function verificarAction()
    {
        $host = $this->_request->getPost('servidor');
        $dbname = $this->_request->getPost('basedatos');
        $user = $this->_request->getPost('usuario');
        $pass = $this->_request->getPost('password');
        $port = $this->_request->getPost('puerto');

        try {
            $dsn = "pgsql://$user:$pass@$host:$port/postgres";
            $conexion = Doctrine_Manager :: connection($dsn);

            $conexion->connect();
        } catch (Doctrine_Exception $e) {
            throw new ZendExt_Exception(F001, $e);
            return;
        }
        try {
            $sql = "CREATE DATABASE $dbname WITH OWNER=$user ENCODING = 'UTF8' TABLESPACE = pg_default TEMPLATE template0 CONNECTION LIMIT=-1;";
            $conexion->execute($sql);

            $cache = ZendExt_Cache::getInstance();
            $cache->save(0, "progreso");

//******************obtengo  los roles ya creados*******************
            $sql = "SELECT * FROM information_schema.enabled_roles";
            $roles = $conexion->execute($sql)->fetchAll();
            $cache->save($roles, "roles");
            $flagS = false;
            $flagL = false;

            foreach ($roles as $rol) {
                if ($rol['role_name'] == 'sauxe') {
                    $flagS = true;
                }
                if ($rol['role_name'] == 'loginuser') {
                    $flagL = true;
                }
            }

            if (!$flagS || !$flagL) {
                if (!$flagS && !$flagL) {
                    $ddmmorigen = file_get_contents('comun/script/1-inicial_L.sql');
                    $conexion->exec($ddmmorigen);
                } elseif (!$flagS) {
                    $login = file_get_contents('comun/script/1-inicial_Sauxe.sql');
                    $conexion->exec($login);
                } else {
                    $sauxe = file_get_contents('comun/script/1-inicial_Login.sql');
                    $conexion->exec($sauxe);
                }
            }

            echo json_encode(array('codMsg' => 0));

        } catch (Doctrine_Exception $de) {
            throw new ZendExt_Exception(F002, $de);
            return;
        }
    }

    //Eliminar la base de datos. DROP
    function eliminardatabaseAction()
    {
        $host = $this->_request->getPost('servidor');
        $dbname = $this->_request->getPost('basedatos');
        $user = $this->_request->getPost('usuario');
        $pass = $this->_request->getPost('password');
        $port = $this->_request->getPost('puerto');
        $this->activarTrazas('false');

        try {
            $dsn = "pgsql://$user:$pass@$host:$port/postgres";
            $conexion = Doctrine_Manager :: connection($dsn);
            $sql = "DROP DATABASE $dbname;";
            $conexion->exec($sql);
        } catch (Exception $e) {
            throw new ZendExt_Exception(F003, $e);
            return;
        }
        echo json_encode(array('codMsg' => 0));
    }

    function instalacionAction()
    {
        $arr_selecc = json_decode(stripslashes($this->_request->getPost('arrSelecc')));
        $host = $this->_request->getPost('servidor');
        $dbname = $this->_request->getPost('basedatos');
        $user = $this->_request->getPost('usuario');
        $pass = $this->_request->getPost('password');
        $port = $this->_request->getPost('puerto');
        $centrodato = $this->_request->getPost('centrodato');

        $error = $this->instalar($host, $dbname, $user, $pass, $port, $arr_selecc, $centrodato);
        if ($error != true) {
            echo json_encode(array('codMsg' => 3, 'mensaje' => "Ha ocurrido un error mientras se corrían los Scripts."));
            return;
        }
        $cache = ZendExt_Cache::getInstance();
        $progreso = $cache->load("progreso");
        $cantScripts = count($cache->load("scripts"));
        $scriptejecutado = $cache->load("scriptN");

        $result = $progreso / $cantScripts;
        if ($result == 1) {
            $this->configurarFicheros($host, $dbname, $port, $centrodato);
        }

        echo json_encode(array('codMsg' => 0, 'progreso' => $result, 'script' => $scriptejecutado));
        return;
    }

    function instalar($host, $dbname, $user, $pass, $port, $arr_selecc, $centrodato)
    {
//**********conexion a la base de datos*************
        $dsn = "pgsql://$user:$pass@$host:$port/$dbname";
        $conexion = Doctrine_Manager :: connection($dsn);
        try {
            $cache = ZendExt_Cache::getInstance();
            $progreso = $cache->load("progreso");
            //**********definir el punto de backtraking**********
            $conexion->beginTransaction();

            if ($progreso == 0) {
//*****************busco lista de scripts*********************
                $scripts = $this->buscarScripts($arr_selecc);
                $cache->save($scripts, "scripts");

                $ddmmorigen = file_get_contents('comun/script/datosmaestros/2-ddmm.sql');
                $ddmm2 = str_replace('?', $centrodato, $ddmmorigen);
                $conexion->exec($ddmm2);
            }

            $result = $cache->load("scripts");

            if ($progreso < count($result)) {

                //****************variable donde guardo el contenido del archivo****************
                $proximo = file_get_contents($result[$progreso]->getUbicacion() . '/' . $result[$progreso]->getNombre());

                $conexion->exec($proximo);
                $progresoN = $progreso + 1;

                $cache = ZendExt_Cache::getInstance();

                $cache->save($progresoN, "progreso");
                $cache->save($result[$progreso]->getNombre(), "scriptN");
            }
            return true;
        } catch
        (Exception $e) {
            $conexion->rollBack();
            throw new ZendExt_Exception(F004, $e);
            //echo '<pre>';
             //print_r($result[$progreso]->getNombre() . $e);
            //die;
            return false;
        }
    }

    //configuracion de los ficheros para la base de datos

    function buscarScripts($arr_selecc)
    {
        //*****************busco cuales son los esquemas*********************
        $arr = array();
        $arrysistemas = array();
        $xml = ZendExt_FastResponse::getXML('subsistemasinstalados');

        if ($xml instanceof SimpleXMLElement) {
            //*************************esquemas basicos***********************
            foreach ($xml->datos as $row) {
                if ((string)$row ['obligatorio'] == "true") {
                    $arr[] = (string)$row ['idinstalador'];
                    $arrysistemas[] = (string)$row ['uri'];
                }
            }
            //********************esquemas seleccionados**********************
            foreach ($arr_selecc as $id) {
                foreach ($xml->datos as $row) {
                    if ((string)$row ['obligatorio'] == "false" && (string)$row['idinstalador'] == $id){
                        $arr[] = (string)$row ['idinstalador'];
                        $arrysistemas [] = (string)$row ['uri'];
                    }
                }
            }

            //$esquemas = array();
            //$sistemas = array();

            //************eliminando esquemas repetidos******************//
            $esquemas = array_unique($arr);
            $sistemas = array_unique($arrysistemas);
            $esquemas = array_values($esquemas);
            $sistemas = array_values($sistemas);

//**************direcion donde estan los scripts**************
            $dir = 'comun/script';
            $objeto = new Sql();
            $result = $objeto->encontrar_scripts($dir, $sistemas);

            return $result;
        }
    }

    function configurarFicheros($host, $dbname, $port, $centrodato)
    {
        $register = Zend_Registry::getInstance();
        $dirSubsistemasinstalador = $register->config->xml->subsistemasinstalados;
        $xml = new SimpleXMLElement($dirSubsistemasinstalador, null, true);
        if ($xml instanceof SimpleXMLElement) {
            foreach ($xml as $row)
                if ((string)$row ['obligatorio'] == "true") {
                    $row ['instalado'] = 'true';
                }
        }
        $xml->asXML($dirSubsistemasinstalador);

        $register = Zend_Registry::getInstance();
        $dirModulesConfig = $register->config->xml->modulesconfig;
        $dirNomConfig = $register->config->xml->nomconfig;
        $dirServidorConfig = $register->config->xml->configuracionservidor;

        $modulesConfig = new SimpleXMLElement($dirModulesConfig, null, true);
        $nomConfig = new SimpleXMLElement($dirNomConfig, null, true);
        $servidorConfig = new SimpleXMLElement($dirServidorConfig, null, true);

        if (isset($modulesConfig->conn ['host'])) {
            $modulesConfig->conn ['host'] = $host;
            $modulesConfig->connAuth['host'] = $host;
        } else {
            $modulesConfig->conn->addAttribute('host', $host);
            $modulesConfig->connAuth->addAttribute('host', $host);
        }

        if (isset($modulesConfig->conn ['port'])) {
            $modulesConfig->conn ['port'] = $port;
            $modulesConfig->connAuth ['port'] = $port;
        } else {
            $modulesConfig->conn->addAttribute('port', $port);
            $modulesConfig->connAuth->addAttribute('port', $port);
        }

        if (isset($modulesConfig->conn ['bd'])) {
            $modulesConfig->conn ['bd'] = $dbname;
            $modulesConfig->connAuth ['bd'] = $dbname;
        } else {
            $modulesConfig->conn->addAttribute('bd', $dbname);
            $modulesConfig->connAuth->addAttribute('bd', $dbname);
        }

        $urlSeguridadModule = 'http://' . $_SERVER ['SERVER_NAME'] . $register->config->uri_aplication . '/seguridad/';

        if (!isset($modulesConfig->security))
            $modulesConfig->addChild('security');

        if (isset($modulesConfig->security->uri))
            $modulesConfig->security->uri = $urlSeguridadModule . 'services.php';
        else
            $modulesConfig->security->addChild('uri', $urlSeguridadModule . 'services.php');

        if (isset($modulesConfig->security->location))
            $modulesConfig->security->location = $urlSeguridadModule . 'services.php';
        else
            $modulesConfig->security->addChild('location', $urlSeguridadModule . 'services.php');

        if (isset($modulesConfig->security->wsdl))
            $modulesConfig->security->wsdl = $urlSeguridadModule . 'services.wsdl';
        else
            $modulesConfig->security->addChild('wsdl', $urlSeguridadModule . 'services.wsdl');

        if (isset($nomConfig->conn ['host']))
            $nomConfig->conn ['host'] = $host;
        else
            $nomConfig->conn->addAttribute('host', $host);

        if (isset($nomConfig->conn ['port']))
            $nomConfig->conn ['port'] = $port;
        else
            $nomConfig->conn->addAttribute('port', $port);

        if (isset($nomConfig->conn ['bd']))
            $nomConfig->conn ['bd'] = $dbname;
        else
            $nomConfig->conn->addAttribute('bd', $dbname);

        if (isset($servidorConfig->idservidor ['numero']))
            $servidorConfig->idservidor ['numero'] = $centrodato;
        else
            $servidorConfig->idservidor->addAttribute('numero', $centrodato);


        $modulesConfig->asXML($dirModulesConfig);
        $nomConfig->asXML($dirNomConfig);
        $servidorConfig->asXML($dirServidorConfig);

        //Configuro el fichero YML del reporteador
        //$salvar = "all:\n  propel:\n    class:          sfPropelDatabase\n    param:\n      dsn:          pgsql://usuariofiscalia:fiscaliasgf@$host:$port/$dbname";

        //file_put_contents('../../config/databases.yml', $salvar);
        //Configuro el fichero YML del reporteador

        //Activar las trazas
        //$this->activarTrazas('true');
        $this->configurarSAML($dbname, $host, $port);

        //eliminar reemplazar index.php del instalador por la del portal
        $result = unlink('../index.php');
        if ($result == false) {
            throw new ZendExt_Exception(F005);
            //echo "{'codMsg':3,'mensaje':'Imposible borrar fichero " . $_SERVER[DOCUMENT_ROOT] . "/index.php'}";
            return false;
        }
        $result = rename('../indexPACSOFT.php', '../index.php');
        if ($result == false) {
            throw new ZendExt_Exception(F006);
            //echo "{'codMsg':3,'mensaje':'Imposible renombrar fichero " . $_SERVER[DOCUMENT_ROOT] . "/indexPACSOFT.php'}";
            return false;
        }
        //return true;
    }

    function getxmlAction()
    {
        $xml = ZendExt_FastResponse::getXML('subsistemasinstalados');
        if ($xml instanceof SimpleXMLElement) {
            $arr = array();
            $pos = 0;
            foreach ($xml as $key => $row) {
                $visible = (string)$row ['visible'];
                if ($visible == 'true') {
                    $arr [$pos] ['idinstalador'] = (string)$row ['idinstalador'];
                    $arr [$pos] ['nombre'] = (string)$row ['nombre'];

                    if ((string)$row ['obligatorio'] == 'true')
                        $arr [$pos] ['estado'] = 1;
                    else
                        $arr [$pos] ['estado'] = 0;
                    $pos++;
                }

            }
            echo json_encode(array('cantidad_filas' => count($arr), 'subsistemas' => $arr));
        }
    }

    function buscarDatosSubsistema($id)
    {
        $xml = ZendExt_FastResponse::getXML('subsistemasinstalados');
        if ($xml instanceof SimpleXMLElement) {
            foreach ($xml as $row) {
                if ($row ['idinstalador'] == $id) {
                    return $row;
                }
            }
        }
    }

    function activarTrazas($estado)
    {
        $register = Zend_Registry::getInstance();
        $diraspect = $register->config->xml->aspect;
        $xmlaspect = new SimpleXMLElement($diraspect, null, true);
        //if ($xmlaspect->beginTraceAction ['active'] == 'false')
        $xmlaspect->beginTraceAction ['active'] = $estado;
        // if ($xmlaspect->endTraceAction ['active'] == 'false')
        $xmlaspect->endTraceAction ['active'] = $estado;
        // if ($xmlaspect->failedTraceAction ['active'] == 'false')
        $xmlaspect->failedTraceAction ['active'] = $estado;
        //if ($xmlaspect->beginTraceIoC ['active'] == 'false')
        $xmlaspect->beginTraceIoC ['active'] = $estado;
        //if ($xmlaspect->failedTraceIoC ['active'] == 'false')
        $xmlaspect->failedTraceIoC ['active'] = $estado;
        $xmlaspect->asXML($diraspect);
    }

    function comprobarTareasAction()
    {
        /*$xml = ZendExt_FastResponse::getXML('subsistemasinstalados');
        if ($xml instanceof SimpleXMLElement) {
            foreach ($xml->datos as $row)
                if ((string) $row ['obligatorio'] == "true" && (string) $row ['instalado'] == "true") {
                    echo json_encode(array('instalado' => true));
                    return;
                }
        }*/
        $myserver = $this->getServerSinPuerto();

        echo json_encode(array('instalado' => false, 'servidor' => $myserver));
    }

    private function getServerSinPuerto()
    {
        $dir = '';
        $server = $_SERVER['SERVER_NAME'];
        $array_protocol = explode('/', $_SERVER['SERVER_PROTOCOL']);
        $port = ':' . $_SERVER['SERVER_PORT'];
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $protocolo = "https";
            //$port = "";
        } else
            $protocolo = strtolower($array_protocol[0]);
        $dir = "{$protocolo}://{$server}";
        return $dir;
    }

    function buscarRol($proximo)
    {
        $cache = ZendExt_Cache::getInstance();
        $roles = $cache->load('roles');
        foreach ($roles as $rol)
            if (strpos("CREATE ROLE $rol[0]", $proximo))
                return true;
        return false;
    }

    function configurarSAML($bd, $host, $port)
    {
        //$register = Zend_Registry::getInstance();
        $dirsaml = '../saml/config/config.php'; //$register->config->xml->saml;
        $myserver = $this->getServer();
        $stringconfig = $this->getSamlConfig($myserver, $myserver, $bd, $host, $port);
        chmod($dirsaml, 0777);
        file_put_contents($dirsaml, $stringconfig);

    }

    function getSamlConfig($sp, $idp, $bd, $host, $port)
    {

        $myconfig = "<?php
	 $" . "config = array (
	'baseurlpath'           => 'acaxia/',
	'certdir'               => 'cert/',
	'loggingdir'            => 'log/',
	'datadir'               => 'data/',


	'acaxia.config'	=> array('appname' => 'acaxia',
							 'idpmetadataonsamlspremote' => '" . $sp . "',
							 'spmetadataonsamlidpremote' => '" . $idp . "',


							 'authtype' => array('sqlauth:SQL',
							 'connparameters' => array('gestor' => 'pgsql',
													'usuario' => '',
													'password' => '',
													'host' => '" . $host . "',
													'port' => '" . $port . "',
													'bd' => '" . $bd . "',
													'query' => ''
											)),
							 'secure' => array('privatekey' => 'cakey.pem',
											   'certificate' => 'cacert.cer',
											   'certFingerprint' => '64e4a96b45ff331c25b97b9bc06d589edeb92a79'
										 ),
							 'IdpAuth' => 'acaxiasql',
							 'authsource' => 'saml:SP'
							 ),
	'tempdir'               => '/tmp/simplesaml',


	'debug' => FALSE,


	'showerrors'            =>	TRUE,


	'debug.validatexml' => FALSE,


	'auth.adminpassword'		=> '1234',
	'admin.protectindexpage'	=> false,
	'admin.protectmetadata'		=> false,


	'secretsalt' => 'daniel123',


	'technicalcontact_name'     => 'Administrator',
	'technicalcontact_email'    => 'na@example.org',


	'timezone' => NULL,

	'logging.level'         => LOG_NOTICE,
	'logging.handler'       => 'syslog',


	'logging.facility' => defined('LOG_LOCAL5') ? constant('LOG_LOCAL5') : LOG_USER,


	'logging.processname' => 'simplesamlphp',


	'logging.logfile'		=> 'simplesamlphp.log',



	'enable.saml20-idp'		=> true,
	'enable.shib13-idp'		=> true,
	'enable.adfs-idp'		=> false,
	'enable.wsfed-sp'		=> false,
	'enable.authmemcookie' => false,


	'session.duration'		=>  1800,
	'session.requestcache'	=>  1800,


	'session.datastore.timeout' => 1800,



	'session.cookie.lifetime' => 0,


	'session.cookie.path' => '/',


	'session.cookie.domain' => NULL,

	'session.cookie.secure' => FALSE,


	'session.phpsession.cookiename'  => null,
	'session.phpsession.savepath'    => null,
	'session.phpsession.httponly'    => FALSE,

	'language.available'	=> array('en', 'no', 'nn', 'se', 'da', 'de', 'sv', 'fi', 'es', 'fr', 'it', 'nl', 'lb', 'cs', 'sl', 'lt', 'hr', 'hu', 'pl', 'pt', 'pt-BR', 'tr', 'ja', 'zh-tw'),
	'language.default'		=> 'en',


	'attributes.extradictionary' => NULL,


	'theme.use' 		=> 'default',



	'default-wsfed-idp'	=> 'urn:federation:pingfederate:localhost',


	'idpdisco.enableremember' => TRUE,
	'idpdisco.rememberchecked' => TRUE,


	'idpdisco.validate' => TRUE,

	'idpdisco.extDiscoveryStorage' => NULL,


	'idpdisco.layout' => 'dropdown',


	'shib13.signresponse' => TRUE,




	'authproc.idp' => array(

 		30 => 'core:LanguageAdaptor',


		45 => array(
			'class' => 'core:StatisticsWithAttribute',
			'attributename' => 'realm',
			'type' => 'saml20-idp-SSO',
		),


		50 => 'core:AttributeLimit',




 		99 => 'core:LanguageAdaptor',
	),

	'authproc.sp' => array(

		50 => 'core:AttributeLimit',


 		60 => array('class' => 'core:GenerateGroups', 'eduPersonAffiliation'),

 		61 => array('class' => 'core:AttributeAdd', 'groups' => array('users', 'members')),


 		90 => 'core:LanguageAdaptor',

	),



	'metadata.sources' => array(
		array('type' => 'flatfile'),
	),



	'store.type' => 'phpsession',



	'store.sql.dsn' => 'sqlite:/path/to/sqlitedatabase.sq3',


	'store.sql.username' => NULL,
	'store.sql.password' => NULL,


	'store.sql.prefix' => 'simpleSAMLphp',



	'memcache_store.servers' => array(
		array(
			array('hostname' => 'localhost'),
		),
	),



	'memcache_store.expires' =>  1800,



	'metadata.sign.enable' => FALSE,


	'metadata.sign.privatekey' => NULL,
	'metadata.sign.privatekey_pass' => NULL,
	'metadata.sign.certificate' => NULL,



	'proxy' => NULL,

);
";

        return $myconfig;
    }

    private function getServer()
    {
        $dir = '';
        $server = $_SERVER['SERVER_NAME'];
        $array_protocol = explode('/', $_SERVER['SERVER_PROTOCOL']);
        $port = ':' . $_SERVER['SERVER_PORT'];
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $protocolo = "https";
            //$port = "";
        } else
            $protocolo = strtolower($array_protocol[0]);
        $dir = "{$protocolo}://{$server}{$port}";
        return $dir;
    }
}
