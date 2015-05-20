<?php
require_once(realpath(dirname(__FILE__)) . '/Project.php');

/**
 * @access public
 */
class Connection {
	/**
	 * @AttributeType string
	 */
	private $_host;
	/**
	 * @AttributeType int
	 */
	private $_port;
	/**
	 * @AttributeType string
	 */
	private $_db;
	/**
	 * @AttributeType string
	 */
	private $_username;
	/**
	 * @AttributeType string
	 */
	private $_password;
	
	/**
	 * @AttributeType string
	 */
	private $_driver;

        private $_doctrine;

        private $_connected;

        public function is_connected () {
            return $this->_connected;
        }


        public function get_host() {
            return $this->_host;
        }

        public function set_host($_host) {
            $this->_host = $_host;
        }

        public function get_port() {
            return $this->_port;
        }

        public function set_port($_port) {
            $this->_port = $_port;
        }

        public function get_db() {
            return $this->_db;
        }

        public function set_db($_db) {
            $this->_db = $_db;
        }

        public function get_username() {
            return $this->_username;
        }

        public function set_username($_username) {
            $this->_username = $_username;
        }

        public function get_password() {
            return $this->_password;
        }

        public function set_password($_password) {
            $this->_password = $_password;
        }

        public function get_driver() {
            return $this->_driver;
        }

        public function set_driver($_driver) {
            $this->_driver = $_driver;
        }

        public function get_doctrine() {
            return $this->_doctrine;
        }

        public function set_doctrine($_doctrine) {
            $this->_doctrine = $_doctrine;
        }

        
        function __construct($_host, $_port, $_db, $_username, $_password, $_driver) {
            $this->_host = $_host;
            $this->_port = $_port;
            $this->_db = $_db;
            $this->_username = $_username;
            $this->_password = $_password;
            $this->_driver = $_driver;
            $this->_connected = false;
        }

        function connect () {
            $dsn =  "{$this->_driver->getShortName ()}://{$this->_username}:{$this->_password}@{$this->_host}:{$this->_port}/{$this->_db}";
            $this->_doctrine = Doctrine_Manager::getInstance()->connection($dsn);

            $this->_status = $this->_doctrine instanceof Doctrine_Connection;
        }

        function __call ($pMethod, $pParam) {
            return call_user_func_array(array ($this->_driver, $pMethod), $pParam);        }
        }
?>