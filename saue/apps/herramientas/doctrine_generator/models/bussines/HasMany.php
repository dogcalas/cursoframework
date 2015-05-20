<?php

/**
 * @access public
 */
class HasMany extends HasOne {
	private $_intermediateTable;
	
        public function toString () {
            $str = '$this->hasMany (' . "'";
            $str .= Table :: classname($this->_foreignTable);

            if (!$this->_intermediateTable)
                $str .= "', array ('local' => '{$this->_localField}', 'foreign' => '{$this->_foreignField}'))";
            else {
                $it = Table::classname($this->_intermediateTable);
                $str .= "', array ('local' => '{$this->_localField}', 'foreign' => '{$this->_foreignField}', 'refClass' => '$it'))";
            }

            $str .= ';';

            return $str;
        }

        public function to_array () {
            return array (
                'ft' => Table :: classname ($this->_foreignTable),
                'ff' => $this->_foreignField,
                'lf' => $this->_localField,
                'it' => Table :: classname ($this->_intermediateTable),
                'type' => 'Tiene muchos(as)'
            );
        }

        function __construct() {
            parent :: __construct ();
        }

        public function get_intermediate_table() {
            return $this->_intermediateTable;
        }

        public function set_intermediate_table($_intermediateTable) {
            $this->_intermediateTable = $_intermediateTable;
        }

        /**
         *
         * @param Table $pTable
         * @return HasMany
         */
        function construct_inverse_relation ($pTable) {
            $inverse = new HasOne();

            $inverse->set_foreign_table($pTable->get_name());
            $tmp = $this->get_local_field();
            $inverse->set_local_field($this->get_foreign_field());
            $inverse->set_foreign_field($tmp);

            return $inverse;
        }
}
?>