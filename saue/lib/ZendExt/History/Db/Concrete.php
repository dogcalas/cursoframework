<?php
class ZendExt_History_Db_Concrete {
	private $_conn;
	private $_rsa;
	public function __construct() {
		$this->_rsa =  new ZendExt_RSA();
                $register = Zend_Registry::getInstance();
		$dirModulesConfig = $register->config->xml->modulesconfig;
		$modulesConfig = new SimpleXMLElement($dirModulesConfig, null, true);

                $host = $modulesConfig->conn['host'];
                $bd = $modulesConfig->conn['bd'];
		$usuario = $modulesConfig->conn['usuario'];
		$pass = $this->_rsa->decrypt($modulesConfig->conn['password'], '85550694285145230823', '99809143352650341179');

                $this->_conn = new PDO("pgsql:dbname=$bd host=$host", $usuario, $pass);
                $this->_conn->exec ("set search_path = 'mod_historial';");
	}

	public function query($pSQL) {
		$pSQL .= ";";
		
		$query = $this->_conn->query ( $pSQL );
		
		return ($query != null) ? $query->fetchAll ( PDO::FETCH_ASSOC ) : false;
	}
}
?>
