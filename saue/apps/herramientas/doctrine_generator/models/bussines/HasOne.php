<?php

/**
 * @access public
 */
class HasOne {
	/**
	 * @AttributeType Table
	 */
	protected $_foreignTable;
	/**
	 * @AttributeType Field
	 */
	protected $_localField;
	/**
	 * @AttributeType Field
	 */
	protected $_foreignField;

        protected $_inverse;

        protected $_mappeable;

        public function get_inverse() {
            return $this->_inverse;
        }

        public function set_inverse($_inverse) {
            $this->_inverse = $_inverse;
        }

        
        public function get_foreign_table() {
            return $this->_foreignTable;
        }

        public function set_foreign_table($_foreignTable) {
            $this->_foreignTable = $_foreignTable;
        }

        public function get_local_field() {
            return $this->_localField;
        }

        public function set_local_field($_localField) {
            $this->_localField = $_localField;
        }

        public function get_foreign_field() {
            return $this->_foreignField;
        }

        public function set_foreign_field($_foreignField) {
            $this->_foreignField = $_foreignField;
        }

        public function toString () {
            $str = '$this->hasOne (' . "'";
            $str .= Table :: classname($this->_foreignTable);
            
            $str .= "', array ('local' => '{$this->_localField}', 'foreign' => '{$this->_foreignField}'));";
            
            return $str;
        }

        public function to_array () {
            return array (
                'ft' => Table :: classname ($this->_foreignTable),
                'ff' => $this->_foreignField,
                'lf' => $this->_localField,
                'it' => '',
                'type' => 'Tiene un(a)'
            );
        }

        function __construct() {
            $this->_mappeable = true;
        }

        /**
         *
         * @param Table $pTable
         * @return HasMany
         */
        function construct_inverse_relation ($pTable) {
            $inverse = new HasMany();

            $inverse->set_foreign_table($pTable->get_name());
            $tmp = $this->get_local_field();
            $inverse->set_local_field($this->get_foreign_field());
            $inverse->set_foreign_field($tmp);

            return $inverse;
        }

        public function get_mappeable() {
            return $this->_mappeable;
        }

        public function set_mappeable($_mappeable) {
            $this->_mappeable = $_mappeable;
        }

        public function  hash () {
            return "{$this->_foreignField}_ {$this->_foreignTable}_ {$this->_localField}}";
        }
        
}
?>