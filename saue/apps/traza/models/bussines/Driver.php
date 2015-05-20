<?php
abstract class Driver {
    protected $_conn;  
	
    public function  __construct($pConn) {
        $this->_conn = $pConn;
    }
	
    public abstract function getShortName ();
    public abstract function getTables ($pDb);
    public abstract function get_fields ($pTable);
    public abstract function get_relations ($pTable);
}
?>