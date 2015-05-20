<?php
require_once(realpath(dirname(__FILE__)) . '/Table.php');

/**
 * @access public
 */
class Field {
	/**
	 * @AttributeType string
	 */
	private $_name;
	
	/**
	 * @AttributeType string
	 */
	private $_type;
	/**
	 * @AttributeType bool
	 */
	private $_primaryKey;

        private $_null;
        
        private $_sequence;
        
        private $_length;
        
        private $_default;
        
        public function get_default() {
            return $this->_default;
        }

        public function set_default($_default) {
            $this->_default = $_default;
        }
        
        public function get_name() {
            return $this->_name;
        }

        public function set_name($_name) {
            $this->_name = $_name;
        }

        public function get_type() {
            return $this->_type;
        }

        public function set_type($_type) {
            $this->_type = $_type;
        }

        public function get_primary_key() {
            return $this->_primaryKey;
        }

        public function set_primary_key($_primaryKey) {
            $this->_primaryKey = $_primaryKey;
        }

        public function get_null() {
            return $this->_null;
        }

        public function set_null($_null) {
            $this->_null = $_null;
        }

        public function get_sequence() {
            return $this->_sequence;
        }

        public function set_sequence($_sequence) {
            $this->_sequence = $_sequence;
        }

        public function get_length() {
            return $this->_length;
        }

        public function set_length($_length) {
            $this->_length = $_length;
        }

        //'name', 'length', 'sequence', 'pk', 'type'
        public function to_array () {
            return array (
                'name' => $this->_name,
                'length' => $this->_length,
                'sequence' => ($this->_sequence) ? $this->_sequence : '',
                'pk' => $this->_primaryKey,
                'type' => $this->_type
            );
        }
}
?>