<?php

/**
 * ZendExt_Aspect_Validation
 * 
 * Administra las transacciones
 * 
 * @author: Yoandry Morejon Borbon
 * @copyright UCID-ERP Cuba
 * @package: ZendExt
 * @version 1.0-0
 */
class ZendExt_Aspect_TransactionManager implements ZendExt_Aspect_ISinglenton {

    /**
     * Coneccion activa
     * 
     * @var Doctrine_Connection
     */
    private $conection;

    /**
     * Constructor de la clase, es privado para impedir que pueda 
     * ser instanciado y de esta forma garantizar que la instancia 
     * sea un singlenton
     * 
     * @return void
     */
    private function ZendExt_Aspect_TransactionManager() {
        
    }

    /**
     * Obtencion de la instancia de la clase, ya que esta no puede ser 
     * instanciada directamente debido a que es un singlenton
     * 
     * @return ZendExt_Aspect_TransactionManager - instancia de la clase
     */
    static public function getInstance() {
        static $instance;
        if (!isset($instance))
            $instance = new self();
        return $instance;
    }

    /**
     * Inicia una transaccion por cada conection activa
     * 
     * @return void
     */
    public function initTransactions() {
        $doctrineManager = Doctrine_Manager::getInstance();
        $connections = $doctrineManager->getConnections();
        if (is_array($connections) && count($connections)) {
            foreach ($connections as $connection) {
                $connection->beginTransaction();
            }
        }
    }

    /**
     * Salva todos los records creados o modificados y acepta
     * la transaccion abierta por cada conection activa
     * 
     * @ignore Ignora la salva de todos los records (temporalmente)
     * @return void
     */
    public function commitTransactions() {
        $doctrineManager = Doctrine_Manager::getInstance();
        $connections = $doctrineManager->getConnections();
        if (is_array($connections) && count($connections)) {
            foreach ($connections as $connection) {
                try {
                    $connection->commit();
                } catch (Doctrine_Transaction_Exception $e) {
                    //Guardar un log
                }
            }
        }
    }

    /**
     * Cancela la transaccion abierta por cada conection activa
     * 
     * @return void
     */
    public function rollbackTransactions(Exception $e) {
        $doctrineManager = Doctrine_Manager::getInstance();
        $connections = $doctrineManager->getConnections();
        if (is_array($connections) && count($connections)) {
            foreach ($connections as $connection) {
                try {
                    $connection->rollback();
                } catch (Doctrine_Transaction_Exception $e) {
                    //Guardar un log
                }
            }
        }
    }

    /**
     * Abriendo las conexiones de un modulo
     * 
     * @param string $module - Modulo al cual se le quiere abrir una conexion
     * @return Doctrine_Conexion - Conexion
     */
    public function openConections($module = null, $current = false) {
        $doctrineManager = Doctrine_Manager::getInstance();
        try {
            $this->conection = $doctrineManager->getConnection('0conn');
        }
        catch (Doctrine_Manager_Exception $e) {
            try {
                $modulesconfig = ZendExt_FastResponse::getXML('modulesconfig');
                $bdconfig = $modulesconfig->conn;
                $gestor = $bdconfig['gestor'];
                $usuario = $bdconfig['usuario'];
                $RSA = new ZendExt_RSA();
                $password = $RSA->decrypt ($bdconfig['password'], '85550694285145230823', '99809143352650341179');
                $host = $bdconfig['host'];
                $port = $bdconfig['port'];
                $basedatos = $bdconfig['bd'];
                $esquema = $bdconfig['esquema'];
                $connStr = "$gestor://$usuario:$password@$host:$port/$basedatos";
                $this->conection = $doctrineManager->openConnection($connStr,'0conn');
                if ($gestor == 'pgsql' && $esquema)
                    $this->conection->exec("set search_path=pg_catalog,$esquema;");
                $this->conection->beginTransaction();
            }
            catch (Exception $e) {
                if ($e instanceof Doctrine_Connection_Exception)
                    throw new ZendExt_Exception('E014', $e, null);
                else
                    throw $e;
            }
        }
        return $this->conection;
    }

    private function datosConexion($auth) {
        $tipo = $this->TipoConexion();
        $nameConnection = "";
        $registry = Zend_Registry::getInstance();
        $dirmodulesconfig = $registry->config->xml->modulesconfig;
        $DOM_XML_Modules = new DOMDocument();
        $contentfile = file_get_contents($dirmodulesconfig);
        $DOM_XML_Modules->loadXML($contentfile);
        $RSA = new ZendExt_RSA_Facade();

        if ($auth) {

            $DOMconn = $this->getElementByID($DOM_XML_Modules, "authconn");
            $nameConnection = "AuthConn";

            $gestor = $DOMconn->getAttribute('gestor');
            $usuario = $DOMconn->getAttribute('usuario');
            $password = $RSA->decrypt($DOMconn->getAttribute('password'));
            $host = $DOMconn->getAttribute('host');
            $port = $DOMconn->getAttribute('port');
            $basedatos = $DOMconn->getAttribute('bd');
        } else if ($tipo == 0 || $tipo == 1) {
            if ($tipo == 0) {
                $DOMconn = $this->getElementByID($DOM_XML_Modules, "0conn");
                $nameConnection = "Sauxe";
            } else
                if ($tipo == 1) {

                    $idsubs = $this->idSubsistemaUse();
                    $DOMconn = $this->getElementByID($DOM_XML_Modules, $idsubs . "conn");
                    $nameConnection = $DOMconn->parentNode->tagName;
                }
            $gestor = $DOMconn->getAttribute('gestor');
            $usuario = $DOMconn->getAttribute('usuario');
            $password = $RSA->decrypt($DOMconn->getAttribute('password'));
            $host = $DOMconn->getAttribute('host');
            $port = $DOMconn->getAttribute('port');
            $basedatos = $DOMconn->getAttribute('bd');
        } else {
            $idsubs = $this->idSubsistemaUse();
            $DOMconn = $this->getElementByID($DOM_XML_Modules, $idsubs . "conn");
            $host = $DOMconn->getAttribute('host');
            $gestor = $DOMconn->getAttribute('gestor');
            $port = $DOMconn->getAttribute('port');
            $basedatos = $DOMconn->getAttribute('bd');
            if ($tipo == 2) {
                $usuario = "rol_" . $_SESSION['denominacion_transaction'] . "_acaxia$tipo";
                $password = $this->getPassWord($tipo);
                $nameConnection = $usuario;
            } else {
                $usuario = "usuario_" . $_SESSION['username_transaction'] . "_acaxia$tipo";
                $password = $this->getPassWord($tipo);
                $nameConnection = $usuario;
            }
        }
        return array("$gestor://$usuario:$password@$host:$port/$basedatos", $nameConnection);
    }

    private function getPassWord($tipo) {
        $RSA = new ZendExt_RSA_Facade();
        if ($tipo == 3) {
            $password = $_SESSION['password_transaction'];
            $password = md5($password);
            $usuario = $_SESSION['username_transaction'];
            $usuario = "usuario_$usuario" . "_acaxia$tipo";
            return $this->EncritarPass($tipo, $usuario, $password);
        } else {
            $usuario = $_SESSION['denominacion_transaction'];
            $usuario = "rol_$usuario" . "_acaxia$tipo";
            $registry = Zend_Registry::getInstance();
            $dirfile = $registry->config->dir_aplication;
            $dirfile.=DIRECTORY_SEPARATOR . 'seguridad' . DIRECTORY_SEPARATOR . 'comun' . DIRECTORY_SEPARATOR . 'recursos' . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'securitypasswords.xml';
            $DOM_XML = new DOMDocument('1.0', 'UTF-8');
            $content = file_get_contents($dirfile);
            $content = $RSA->decrypt($content);
            $DOM_XML->loadXML($content);
            $Element = $this->getElementsByAttr($DOM_XML, 'name', $usuario);
            $Element = $Element->item(0);
            $passWordUser = $Element->getAttribute('password');
            return $this->EncritarPass($tipo, $usuario, $passWordUser);
        }
    }

    public function EncritarPass($tipoConex, $RoleName, $passWordUser) {
        $RSA = new ZendExt_RSA_Facade();
        $CV = $this->KOI($passWordUser, 0);
        $CC = $this->KOI($passWordUser, 1);
        $CD = $this->KOI($passWordUser, 2);
        $valor = ($CC == 0 ? 1 : $CC) * ($CD == 0 ? 1 : $CD) * ($CV == 0 ? 1 : $CV);
        $passWordUser.=$passWordUser . $CC . $CD . $CE . $CV . $passWordUser;
        $passWordUser.=$valor;
        $passWordUser = md5($passWordUser);
        $passWordUser = "Acaxia2.2" . $tipoConex . $passWordUser;
        $passWordUser = md5($passWordUser);
        $passWordUser = $this->O9($passWordUser);
        $passWordUser = $RSA->encrypt($RSA->encrypt($passWordUser));
        $passWordUser = str_replace(' ', '', $passWordUser);
        return $passWordUser;
    }

    private function O9($K) {
        $LL = "";
        $T = 0;
        for ($i = 0; $i < strlen($K); $i++) {
            $c = $K[$i];
            $G = ord($c);
            $LL.=$G;
            $T+=$G;
        }
        return $T . $LL;
    }

    private function KOI($Y, $J) {
        $P = "aeiou";
        $UUI = "bcdfghjklmnñpqrstvwxyz";
        $TH = "0123456789";
        $KKOP = 0;
        for ($i = 0; $i < strlen($Y); $i++) {
            if ($J == 0) {
                if (strpos($P, $Y[$i]) !== false || strpos(strtoupper($P), $Y[$i]) !== false) {
                    $KKOP++;
                }
            } else
            if ($J == 1) {
                if (strpos($UUI, $Y[$i]) !== false || strpos(strtoupper($UUI), $Y[$i]) !== false) {
                    $KKOP++;
                }
            } else
            if ($J == 2) {
                if (strpos($TH, $Y[$i]) !== false) {
                    $KKOP++;
                }
            } else
            if ($J == 3) {
                if (strpos($TH, $Y[$i]) === false &&
                        strpos($UUI, $Y[$i]) === false && strpos(strtoupper($UUI), $Y[$i]) === false &&
                        strpos($P, $Y[$i]) === false && strpos(strtoupper($P), $Y[$i]) === false) {
                    $KKOP++;
                }
            }
        }
        return $KKOP;
    }

    private function idSubsistemaUse() {
        $doctrineManager = Doctrine_Manager::getInstance();
        $config = Zend_Registry::get('config');
        $pathTransaction = $config->modulo_path;

        $start = strripos($pathTransaction, "apps") + 5;
        $length = strlen($pathTransaction) - $start - 1;

        $basePath = substr($pathTransaction, 0, $start - 5);
        $pathTransaction = substr($pathTransaction, $start, $length);
        $arrayModules = explode("/", $pathTransaction);
        return $this->getidModulo($arrayModules, $basePath);
    }

    /*
     * Modificar este m'etodo para obtener los modulos hijos
     * cuando tenga una manera de estaleerle una conexion a ellos
     * tambien.
     */

    private function getidModulo($arrayModules, $basePath, $pos = 0) {
        $DOM_XML_Components = new DOMDocument();
        $contentfile = file_get_contents($basePath . "config/xml/components.xml");
        $DOM_XML_Components->loadXML($contentfile);
        $path = "/" . $arrayModules[$pos];

        $modulos = $this->getElementsByAttr($DOM_XML_Components, "path", $path);

        if ($modulos->length > 0) {
            $id = $modulos->item(0)->getAttribute('id');
            return $id;
        } else {
            throw new ZendExt_Exception('E015');
        }
    }

    private function TipoConexion() {
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->configConection;
        $DOM_XML_Conex = new DOMDocument();
        $contentfile = file_get_contents($dirconfigConection);
        $DOM_XML_Conex->loadXML($contentfile);
        $elements = $this->getElementsByAttr($DOM_XML_Conex, "seleccion", "true");
        $element = $elements->item(0);
        return $element->getAttribute('tipo');
    }

    private function getElementsByAttr($DOM, $nameAtrr, $value) {
        $xpath = new DOMXpath($DOM);
        $elements = $xpath->query("//*[@$nameAtrr='$value']");
        if ($elements->length > 0) {
            return $elements;
        }
        return false;
    }

    private function getElementByID($DOM, $id) {
        $xpath = new DOMXpath($DOM);
        $elements = $xpath->query("//*[@id='$id']");
        if ($elements->length > 0) {
            return $elements->item(0);
        }
        return false;
    }

    public function getConnection($module) {
        return $this->openConections($module);
    }

    /**
     * Salva los modelos de dominio creados o modificados
     * 
     * @return void
     */
    public function saveModels() {
        $doctrineManager = Doctrine_Manager::getInstance();
        $connections = $doctrineManager->getConnections();
        if (is_array($connections)) {
            foreach ($connections as $connection) {
                $connection->flush();
            }
        }
    }

    public function initModuleConnection() {
        $conn = $this->openConections(null, true);
    }

    /**
     * Inicializa la conexion a la base de datos
     * 
     * @throws ZendExt_Exception - excepcion declarada en el xml de excepciones
     * @return void
     */
    protected function initConexion() {
        //Creo la conexion activa
        $conexion = $this->openConections(null, true);
        //Se guarda en el registro la conexion activa.
        $register = Zend_Registry::getInstance();
        $register->conexion = $conexion;
    }

}
