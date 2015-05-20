<?php
/**
 * @access public
 */
class Project {
	/**
	 * @AttributeType string
	 */
	private $_name;
	/**
	 * @AttributeType string
	 */
	private $_path;
	/**
	 * @AssociationType Mapper
	 */
	private $_mapper;
	
	
	/**
	 * @AssociationType Table
	 * @AssociationKind Composition
	 */
	private $_tables;
	/**
	 * @AssociationType Connection
	 * @AssociationKind Composition
	 */
	private $_connection;
	/**
	 * @AssociationType DBAL
	 */
	public $_dbal;

        public function get_name() {
            return $this->_name;
        }

        public function set_name($_name) {
            $this->_name = $_name;
        }

        public function get_path() {
            return $this->_path;
        }

        public function set_path($_path) {
            $this->_path = $_path;
        }

        public function get_mapper() {
            return $this->_mapper;
        }

        public function set_mapper($_mapper) {
            $this->_mapper = $_mapper;
        }

        public function get_tables() {
            return $this->_tables;
        }

        public function set_tables($_tables) {
            $this->_tables = $_tables;
        }

        public function get_connection() {
            return $this->_connection;
        }

        public function set_connection($_connection) {
            $this->_connection = $_connection;
        }

        public function get_dbal() {
            return $this->_dbal;
        }

        public function set_dbal($_dbal) {
            $this->_dbal = $_dbal;
        }

        public function table_exists ($pTable) {
            return isset ($this->_tables [$pTable]);
        }

        public function find_table ($pTable) {
            return $this->_tables [$pTable];
        }

        /**
         *
         * @param <type> $pTables
         * @return <type> array
         * @deprecated
         */
        public function map_tables ($pTables) {
            //Si no estoy conectado me conecto a la BD
            if (! $this->_connection->is_connected ())
                $this->_connection->connect ();


            //Tabla hash con todas las tablas y sus relaciones
            $result = array ();

            //Paso por todas las tablas seleccionadas creando si instancia y mapeando sus campos.
            foreach ($pTables as $table) {
                $tmp = new Table();
                $tmp->set_name($table);
                $tmp->set_fields($tmp->map_fields ($this->_connection));
                $result[$table] = $tmp; $tmp = null;
            }

            //Itero por la tabla hash
            foreach ($result as $table_name => $table) {
                //Saco las relaciones de cada tabla
                $relations = $table->map_relations ($this->_connection);
                $table->set_relations($relations);
            }

            //Me muevo por todas las tablas del hash actualizando las relaciones inversas
            foreach ($result as $table_name => $table) {
                //Todas las relaciones de la tabla
                $relations = $table->get_relations();

                foreach ($relations as $r) {
                    //Nombre de la tabla foranea
                    $ft_name = $r->get_foreign_table ();
                    $ft = $result [$ft_name]; //Tabla foranea

                    /**
                     * Esta condidicion existe pq puede ser q se detecten relaciones entre tabla q no desean
                     * ser mapeadas
                     */
                    if ($ft) {
                        //Obtengo las relaciones de esa tabla foranea
                        $ft_relations = $ft->get_relations ();

                        //Si la relacion inversa no esta ya emtre las relaciones de la tabla
                        if (! in_array ($r->get_inverse (), $ft_relations, true)) {
                            //Se adiciona
                            $ft_relations [] = $r->get_inverse (); $ft->set_relations ($ft_relations);
                        }
                    }
                }
            }

            $this->_connection = null; return array_values($result); 
        }


        /**
         *
         * @param Table $pTable
         */
        public function add_table ($pTable) {
            $this->_tables [$pTable->get_name()] = $pTable;
        }
}
?>