<?php

class ZendExt_History_Concrete_HistoryManager implements ZendExt_History_Interfaces_IHistory {

    private $_db, $_free_tables, $_exist_tables;

    public function __construct() {
        $this->_db = ZendExt_History_Db_Singleton::getInstance ();
        $this->_sql = new ZendExt_History_Sql_History ( );
        $this->_free_tables = array();
        $this->_exist_tables = array();
    }

    public function getTables($limit, $offset) {
        return $this->_db->query($this->_sql->GetTables($limit, $offset));
    }

    public function getTablesbyschema($schema, $limit, $start) {
        return $this->_db->query($this->_sql->GetTablesbySchema($schema, $limit, $start));
    }

    public function getHistorial($limit, $offset) {
        return $this->_db->query($this->_sql->gethistorial($limit, $offset));
    }

    public function CreateHistorial($tables, $user) {

	
        if (count($tables)) {
            foreach ($tables as $tab) {
		
                $schema = $tab->table_schema;
                $table = $tab->table_name;
                $query = $this->_db->query($this->_sql->tableExist($table));

                if (count($query) == 0) {
                    $this->_db->query($this->_sql->registrarcatalogo($table, $user, $schema));
                    $this->_db->query($this->_sql->createHistorial($table));
		    $this->_db->query($this->_sql->Permisos($table));
                    $fields = $this->_db->query($this->_sql->fieldTableAll($schema, $table));
                    foreach ($fields as $field) {
                        $this->addFields($table, $field ['column_name'], $field ['data_type'], $field ['column_default'], false);
                    }
                    $this->addFields($table, 'fecha_creacion', 'time without time zone', 'now()', false);
                    $this->addFields($table, 'operacion', 'varchar', 'null', false);

                    //$this->uploadData($schema, $table);
                    $function = $this->functionh($table);
                    $functionD = $this->functionhD($table);
                    $users = array ();
                    $users = $this->getAccess ( $schema );
                    $this->_db->query ( $this->_sql->getAccess($users,$table) );
                    $this->_db->query($this->_sql->createfunction($schema, $function, $functionD, $table));
                    $this->_db->query($this->_sql->createtrigger($schema, $table));
                } else {
                    $this->_exist_tables [] = $table;
                }
            }

            return $this->existTables();
        }
    }

    public function DropHistorial($tables) {
        $esquema = array();
        foreach ($tables as $table) {
            $cant = $this->_db->query($this->_sql->count($table->table_name));
            if ($cant [0] ['cantidad'] == 0) {
                $esquema [] = $table->esquema;
                $this->_db->query($this->_sql->deletecatalogo($table->id_historial));
                $this->_db->query($this->_sql->dropHistorial($table->table_name));
                $this->_db->query($this->_sql->eliminartrigger($table->esquema, $table->table_name));
                $this->_db->query($this->_sql->eliminartriggerfunction($table->esquema, $table->table_name));
            } else
                $this->_free_tables [] = $table;
        }
        return $this->freeTables();
    }

    private function setPK($table) {
        $this->_db->query($this->_sql->pK($table));
    }

    private function addFields($table, $field, $type, $default, $bandera) {
        $this->_db->query($this->_sql->addfields($table, $field, $type, $default, $bandera));
    }

    private function createTableRelation($table, $schema) {
        $field = $this->_db->query($this->_sql->TablePk($table, $schema));
        $this->_db->query($this->_sql->createTableRelation($schema, $table, $field [0] ['column_name']));

        /* $fields = $this->_db->query ( $this->_sql->TablePk ( $table, $schema ) );
          foreach ( $fields as $field ) {
          $this->_db->query ( $this->_sql->createTableRelation ( $schema, $table, $field ['column_name'] ) );

          } */
    }

    private function freeTables() {
        return $this->_free_tables;
    }

    private function existTables() {
        return $this->_exist_tables;
    }

    private function uploadData($schema, $table) {
        $this->_db->query($this->_sql->uploadData($schema, $table));
    }

    private function functionh($table) {
        $values = "";
        $fields = $this->_db->query($this->_sql->fieldTable($table, false));
        foreach ($fields as $field) {
            $name = $field ['column_name'];
            $values .= ",NEW.$name";
        }
        $values = substr($values, 1, strlen($values));
        $values .= ",fecha";
        $function = "INSERT INTO mod_historial.$table VALUES ($values";
        return $function;
    }

    private function functionhD($table) {
        $values = "";
        $fields = $this->_db->query($this->_sql->fieldTable($table, false));
        foreach ($fields as $field) {
            $name = $field ['column_name'];
            $values .= ",OLD.$name";
        }
        $values = substr($values, 1, strlen($values));
        $values .= ",fecha";
        $function = "INSERT INTO mod_historial.$table VALUES ($values";
        return $function;
    }

    private function getAccess($schema) {
        $register = Zend_Registry::getInstance();
        $dirNomConfig = $register->config->xml->nomconfig;

        $nomConfig = new SimpleXMLElement($dirNomConfig, null, true);
        $arr = array();
        $pos = 0;

        foreach ($nomConfig->esquemas->datos as $row) {
            if ((string) $row['nombre'] == $schema) {
                $arr[$pos]['usuario'] = $row['usuario'];
                $pos++;
            }
        }
        return $arr;
    }

    public function CountTables($schema) {
        return $this->_db->query($this->_sql->countTables($schema));
    }

    public function counthistorial($table) {
        return $this->_db->query($this->_sql->count($table));
    }

    public function Table($table) {
        return $this->_db->query($this->_sql->table($table));
    }

    public function Schema() {
        return $this->_db->query($this->_sql->schemas());
    }

    public function TableAll($table, $limit, $offset) {
        return $this->_db->query($this->_sql->tableAll($table, $limit, $offset));
    }

    public function FieldTable($table, $bandera) {
        return $this->_db->query($this->_sql->fieldTable($table, $bandera));
    }

}
?>
