<?php

/*
 * Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo
 * @author Darien Garc�a Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */

class GestionarconexionController extends ZendExt_Controller_Secure {

    protected $conn;
    protected $proceso;

    function init() {
        parent::init();
        $this->proceso = ZendExt_ConfProceso_ProcessConfiguration::getInstance();
    }

    public function getProceso() {
        return $this->proceso;
    }

    public function setProceso($proceso) {
        $this->proceso = $proceso;
    }

    public function getConn() {
        return $this->conn;
    }

    public function setConn($conn) {
        $this->conn = $conn;
    }

    function GestionarconexionAction() {
        $this->render();
    }

    private function gen_cache_id() {
        $str = "project_{$_SERVER ['REMOTE_ADDR']}";
        return str_replace('.', '_', $str);
    }

    function getconexionsAction() {
        $start = $this->_request->getPost('start');
        $limit = $this->_request->getPost('limit');
        $cantidad = count(DatConexion::getconexions($offset, $limit));
        $result = DatConexion::getconexions($start,$limit);

        $data = array();
        foreach ($result as $row) {
            $data [] = array("id" => $row['id'], "name" => $row['nombre'], "user" => $row['usuario'], "host" => $row['host'], "db" => $row['bd'], "port" => $row['puerto']);
        }

        $result = array('cantidad_filas' => $cantidad, 'datos' => $data);
        $_SESSION['hasvaluesconexion'] = true;
        echo json_encode($result);
        return;
    }

    function addconexionAction() {
        //Adicionar la conexion a la tabla de la base de datos
        $register = Zend_Registry::getInstance();
        $dirModulesConfig = $register->config->xml->modulesconfig;
        $modulesConfig = new SimpleXMLElement($dirModulesConfig, null, true);
	

        $interface = $this->getRequest()->getPost('interface');
        $id = $this->getRequest()->getPost('id');
        $name = $this->getRequest()->getPost('name');
        if ($this->getRequest()->getPost('host') == "default")
            $host = $modulesConfig->conn['host'];
        else {
            $host = $this->getRequest()->getPost('host');
        }
        if ($this->getRequest()->getPost('host') == "default")
            $port = $modulesConfig->conn['port'];
        else {
            $port = $this->getRequest()->getPost('port');
        }
        if ($this->getRequest()->getPost('host') == "default")
            $db = $modulesConfig->conn['bd'];
        else {
            $db = $this->getRequest()->getPost('db');
        }
        $user = $this->getRequest()->getPost('user');
        $rsa = new ZendExt_RSA_Facade();
        $encSeed = $rsa->encrypt($this->getRequest()->getPost('passwd'));
        $pass = $encSeed;
		


        $DatConexionM = new DatConexionModel();
		
        if ($interface == "mod") {
            $DatConex = Doctrine::getTable('DatConexion')->find($id);
            $DatConex->nombre = $name;
            $DatConex->bd = $db;
            $DatConex->host = $host;
            $DatConex->usuario = $user;
            $DatConex->puerto = $port;
            $DatConex->contrasenna = $pass;
            $DatConex->id = $id;
            $DatConexionM->Modificar($DatConex);
        } else {
		
            $DatConex = new DatConexion();
            $DatConex->nombre = $name;
            $DatConex->bd = $db;
            $DatConex->host = $host;
            $DatConex->usuario = $user;
            $DatConex->puerto = $port;
            $DatConex->contrasenna = $pass;

            $DatConexionM->Insertar($DatConex);
			
        }


        echo"{'conectado':1}";
        return;
    }

    function setconexionAction() {
        //Adicionar la conexión a la tabla de la base de datos
        $id = $this->getRequest()->getPost('id');
        $name = $this->getRequest()->getPost('name');
        $host = $this->getRequest()->getPost('host');
        $db = $this->getRequest()->getPost('db');
        $user = $this->getRequest()->getPost('user');
        $rsa = new ZendExt_RSA_Facade();
        $encSeed = $rsa->encrypt($this->getRequest()->getPost('passwd'));
        $pass = $encSeed;
        $port = $this->getRequest()->getPost('port');

        $DatConex = new DatConexion();
        $DatConex->id = $id;
        $DatConex->bd = $db;
        $DatConex->nombre = $name;
        $DatConex->host = $host;
        $DatConex->usuario = $user;
        $DatConex->puerto = $port;
        $DatConex->contrasenna = $pass;


        $DatConexionM = new DatConexionModel();
        $DatConexionM->Modificar($DatConex);

        echo"{'conectado':1}";
        return;
    }

    function delconexionAction() {
        $procesos = DatProcess::getproceso($offset, $limit);
        $id = $this->getRequest()->getPost('id');

        foreach ($procesos as $row) {
            if ($row['idconexion'] == $id) {
                echo"{'conexion':0}";
                return;
            }
        }



        $DatConexion = Doctrine::getTable('DatConexion')->find($id);
        $DatConexionModel = new DatConexionModel();
        $DatConexionModel->Eliminar($DatConexion);
        echo"{'conexion':1}";
        return;
    }

    function hasvaluesconexionAction() {
        ///verificar si se retorna alguna conexión
        if ($_SESSION['hasvaluesconexion']) {
            echo"{'conectado':1}";
            return;
        }
    }

    function loaddbmsAction() {
        $dbms_path = str_replace('controllers' . DIRECTORY_SEPARATOR . 'DefinirconversionController.php', 'models' . DIRECTORY_SEPARATOR . 'bussines', __FILE__);
        //echo($dbms_path);die("OK");
        $classes = ClassLoader::getClassesImplements($dbms_path, 'Driver');

        $data = array();

        foreach ($classes as $class) {
            $item->dbms = $class->getName();
            $data[] = $item;
            $item = null;
        }

        $json->data = $data;
        echo json_encode($json);
    }

    function connectAction() {
        $register = Zend_Registry::getInstance();
        $dirModulesConfig = $register->config->xml->modulesconfig;
        $modulesConfig = new SimpleXMLElement($dirModulesConfig, null, true);
        if ($this->getRequest()->getPost('host') == "default")
            $host = $modulesConfig->conn['host'];
        else {
            $host = $this->getRequest()->getPost('host');
        }
        if ($this->getRequest()->getPost('host') == "default")
            $port = $modulesConfig->conn['port'];
        else {
            $port = $this->getRequest()->getPost('port');
        }
        if ($this->getRequest()->getPost('host') == "default")
            $db = $modulesConfig->conn['bd'];
        else {
            $db = $this->getRequest()->getPost('db');
        }


        $user = $this->getRequest()->getPost('user');
        $pass = $this->getRequest()->getPost('passwd');

        $conn = new Connection($host, $port, $db, $user, $pass, null);

        $d = new PostgreSQL($conn);
        $conn->set_driver($d);

        $Pgsql = new ZendExt_Db_Role_Pgsql($conn);
        try {
            $state = $Pgsql->PgsqlVerificarDisponibilidadServidor("pgsql", $user, $pass, $host, $db, $port);
        } catch (Exception $exc) {
            echo"{'conectado':1}";
            return;
        }


        if ($state == "1") {
            echo"{'conectado':1}";
            return;
        } else {
            echo"{'conectado':0}";
            return;
        }
    }

    function getschemasAction() {
        echo json_encode($_SESSION['schemas']);
    }

    function createprojectAction() {
        //$cache = ZendExt_Cache :: getInstance();
        //$project = $cache->load ($this->gen_cache_id ());
        echo "<pre>";
        print_r($this->getRequest()->getPost);
        die();


        if ($project) {
            
        }
        else
            ZendExt_MessageBox::show('Vuelva a conectarse.', ZendExt_MessageBox::ERROR);
    }

    /* function crearprojectoAction() {
      $Sschemas = $this->_request->getPost('Schemas');
      $schemas = explode(".", $Sschemas);
      $this->findTable($schemas);
      return;
      } */

    function findTable($schemas) {
        //$Pgsql = new ZendExt_Db_Role_Pgsql($conn);

        foreach ($schemas as $chema) {
            $sql = "select tablename from pg_tables where schemaname = '$chema'";
            $tables = $this->getConn()->execute($sql)->fetchAll();
            $this->addScheTabl($chema, $tables);
        }
    }

    function addScheTabl($schema, $tables) {
        $this->proceso->addSchemaTables($schema, $tables);
        $tables = $this->proceso->getTables();
        $_SESSION['proceso'] = $this->proceso;
        return;
    }

    function getTablesAction() {

        $tables = $_SESSION['proceso']->getTables();

        try {
            $tmp = array();

            foreach ($tables as $t) {
                foreach ($t as $valuetabla) {
                    $tt->tables = $valuetabla;
                    $tmp[] = $tt;
                    $tt = null;
                }
            }
            echo json_encode(array('data' => $tmp));
        } catch (Exception $e) {
            //ZendExt_MessageBox :: show ($e->getMessage(), ZendExt_MessageBox :: ERROR);
            ZendExt_MessageBox::show('Debe verificar los datos introducidos.', ZendExt_MessageBox::ERROR);
        }
    }

    function crearprocesoAction() {
        $tables = $this->_request->getPost('tables');
        $tables = explode(" ", $tables);
        $Nproceso = $this->_request->getPost('Nproceso');
        $fdatos = $this->_request->getPost('fdatos');
        $descripcion = $this->_request->getPost('descripcion');
        $proceso = $_SESSION['proceso'];
        $proceso->setTablesS($tables);
        $proceso->setName($Nproceso);
        $proceso->setFuentededatos($fdatos);
        $proceso->setDescripcion($descripcion);
        $_SESSION['proceso'] = $proceso;
    }

    function getnamePAction() {
        $_SESSION['proceso']->getName();

        echo"{'mensaje': 'Configurar traza del proceso \"" . $_SESSION['proceso']->getName() . "\".'}";
        return;
    }

}

?>
