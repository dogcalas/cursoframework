<?php

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
    private $_user;

    /**
     * @AttributeType string
     */
    private $_pass;
    
    private $_conn;

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

    public function get_user() {
        return $this->_user;
    }

    public function set_user($_user) {
        $this->_user = $_user;
    }

    public function get_pass() {
        return $this->_pass;
    }

    public function set_conn($_conn) {
        $this->_conn = $_conn;
    }

    public function get_conn() {
        return $this->_conn;
    }

    public function set_pass($_pass) {
        $this->_pass = $_pass;
    }

    function __construct($_host, $_port, $_db, $_user, $_pass) {
        $this->_host = $_host;
        $this->_port = $_port;
        $this->_db = $_db;
        $this->_user = $_user;
        $this->_pass = $_pass;
        $dsn = "pgsql:host={$this->_host};port={$this->_port};dbname={$this->_db}";
        $this->_conn = new PDO($dsn, $this->_user, $this->_pass);
    }
            
    function schemasAll() {
        $sql = "Select information_schema.tables.table_schema
                FROM information_schema.tables WHERE (table_schema != 'information_schema')
                AND (table_schema != 'pg_catalog') 
                GROUP BY information_schema.tables.table_schema ";

        $schemas = $this->_conn->query($sql)->fetchAll();
        
        return $schemas;
    }

    function TableForSchema($schema) {
        $sql = "SELECT tablename FROM pg_tables WHERE schemaname='$schema' ORDER BY tablename";

        $schemas = $this->_conn->query($sql)->fetchAll();
        return $schemas;
    }

    function FunctionForSchema($schema) {
        $sql = "SELECT proname, oid FROM pg_proc 
                WHERE pronamespace IN (SELECT oid FROM pg_namespace WHERE nspname='$schema') ORDER BY proname";

        $schemas = $this->_conn->query($sql)->fetchAll();
        return $schemas;
    }

    function TriggerForSchema($schema) {
        $sql = "SELECT trigger_name, event_object_table FROM information_schema.triggers 
                WHERE trigger_schema='$schema' ORDER BY trigger_name";

        $schemas = $this->_conn->query($sql)->fetchAll();
        return $schemas;
    }

    function IndexForSchema($schema) {
        $sql = "SELECT indexname FROM pg_indexes WHERE schemaname = '$schema' ORDER BY indexname";

        $schemas = $this->_conn->query($sql)->fetchAll();
        return $schemas;
    }

    function IndexDefForSchema($schema, $indexname) {
        $sql = "SELECT indexdef FROM pg_indexes WHERE schemaname = '$schema' AND indexname = '$indexname'";

        $index = $this->_conn->query($sql)->fetchAll();
        return $index;
    }

    function SequencesForSchema($schema) {
        $sql = "SELECT sequence_name FROM information_schema.sequences 
                WHERE sequence_schema='$schema' ORDER BY sequence_name";

        $schemas = $this->_conn->query($sql)->fetchAll();
        return $schemas;
    }

    function SelectAllData($schema, $table) {
        $sql = "SELECT * FROM $schema.$table";

        $data = $this->_conn->query($sql)->fetchAll();
        return $data;
    }
    
    function SelectWhereAllData($schema, $table, $pk) {
        $sql = "SELECT * FROM $schema.$table WHERE $pk[0] = '$pk[1]'";

        $data = $this->_conn->query($sql)->fetchAll();
        return $data;
    }
    
    

    function ExistNomSecGeneral() {
        $sql = "SELECT tablename FROM pg_tables WHERE tablename='nom_secuenciasgeneral'";

        $exist = $this->_conn->query($sql)->fetchAll();
        return $exist;
    }

    function SelectSequenceInfo($obj) {
        $sequence = preg_split("/\^/", $obj);
        $sql = "SELECT sequence_name, sequence_schema, start_value, minimum_value, maximum_value, increment FROM information_schema.sequences 
                WHERE sequence_schema = '$sequence[0]' AND sequence_name = '$sequence[1]'";

        $seq = $this->_conn->query($sql)->fetchAll();
        return $seq;
    }

    function SelectServerId() {
        $sql = "SELECT idservidor FROM mod_datosmaestros.nom_servidor";

        $idserver = $this->_conn->query($sql)->fetchAll();
        return $idserver;
    }

    function SelectTableInfo($obj) {
        $table = preg_split("/\^/", $obj);
        $sql = "SELECT  column_name, column_default, data_type, is_nullable, numeric_precision 
                FROM information_schema.columns 
                WHERE information_schema.columns.table_schema != 'pg_catalog' AND 
                information_schema.columns.table_schema != 'information_schema' AND 
                information_schema.columns.table_schema = '$table[0]' AND 
                information_schema.columns.table_name = '$table[1]'";

        $tbl = $this->_conn->query($sql)->fetchAll();
        return $tbl;
    }

    function SelectPkInfo($obj) {
        $table = preg_split("/\^/", $obj);
        $sql = "SELECT  constraint_schema, constraint_name, table_name, column_name 
                FROM information_schema.key_column_usage 
                WHERE constraint_name IN (SELECT constraint_name 
                FROM information_schema.table_constraints WHERE constraint_type = 'PRIMARY KEY')
                AND constraint_schema = '$table[0]'
                AND table_name = '$table[1]'";

        $fk = $this->_conn->query($sql)->fetchAll();
        return $fk;
    }

    function SelectFieldsForTable($table) {
        $sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '$table'";

        $tabla = $this->_conn->query($sql)->fetchAll();
        return $tabla;
    }

    function SelectPrimaryKey($table) {
        $sql = "SELECT conname AS restriccion, relname AS tabla, pg_catalog.pg_attribute.attname AS columna
                FROM pg_catalog.pg_constraint, pg_catalog.pg_class, pg_catalog.pg_attribute
                WHERE contype = 'p'
                AND conrelid = pg_catalog.pg_class.oid
                AND conrelid = pg_catalog.pg_attribute.attrelid
                AND pg_catalog.pg_attribute.attnum = pg_catalog.pg_constraint.conkey[1]
                AND relname = '$table'
                ORDER BY conname, relname, pg_catalog.pg_attribute.attname";

        $pkey = $this->_conn->query($sql)->fetchAll();
        return $pkey;
    }

    function SelectPkeyData($llave, $schema, $table) {
        $sql = "SELECT $llave FROM $schema.$table";

        $tabla = $this->_conn->query($sql)->fetchAll();
        return $tabla;
    }

    function RestricForSchema($schema) {
        $sql = "SELECT  constraint_schema, constraint_name, table_name, column_name 
                FROM information_schema.key_column_usage 
                WHERE constraint_name IN (SELECT constraint_name 
                FROM information_schema.table_constraints WHERE constraint_type = 'UNIQUE')
                AND constraint_schema = '$schema'";

        $rest = $this->_conn->query($sql)->fetchAll();
        return $rest;
    }

    function RestricForSchemaTable($schema, $table) {
        $sql = "SELECT  constraint_schema, constraint_name, table_name, column_name 
                FROM information_schema.key_column_usage 
                WHERE constraint_name IN (SELECT constraint_name 
                FROM information_schema.table_constraints WHERE constraint_type = 'UNIQUE')
                AND constraint_schema = '$schema'
                AND table_name = '$table'";

        $rest = $this->_conn->query($sql)->fetchAll();
        return $rest;
    }

    function FkForSchema($schema) {
        $sql = "SELECT  constraint_schema, constraint_name, table_name, column_name 
                FROM information_schema.key_column_usage 
                WHERE constraint_name IN (SELECT constraint_name 
                FROM information_schema.table_constraints WHERE constraint_type = 'FOREIGN KEY')
                AND constraint_schema = '$schema'";

        $rest = $this->_conn->query($sql)->fetchAll();
        return $rest;
    }

    function SelectReferences($constraint_name) {
        $sql = "SELECT constraint_schema, table_name, column_name, constraint_name FROM information_schema.key_column_usage 
                WHERE constraint_name IN (SELECT constraint_name FROM information_schema.table_constraints 
                WHERE constraint_type = 'PRIMARY KEY') 
                AND constraint_name IN (SELECT unique_constraint_name 
                FROM information_schema.referential_constraints 
                WHERE constraint_name = '$constraint_name')";

        $ref = $this->_conn->query($sql)->fetchAll();
        return $ref;
    }

    function SelectRuleForCOnstraintName($constraint_name) {
        $sql = "SELECT update_rule, delete_rule, match_option FROM information_schema.referential_constraints
                WHERE constraint_name = '$constraint_name'";

        $rule = $this->_conn->query($sql)->fetchAll();
        return $rule;
    }

    function SelectVersion() {
        $sql = "SELECT VERSION()";

        $version = $this->_conn->query($sql)->fetchAll();
        return $version;
    }

    function SelectFunctionDef9($function) {
        $sql = "SELECT pg_get_functiondef(oid) FROM pg_proc WHERE proname = '$function'";

        $functiondef = $this->_conn->query($sql)->fetchAll();
        return $functiondef;
    }

    function SelectFunctionDef($function) {
        $sql = "SELECT prosrc, proargtypes, proargnames, prorettype FROM pg_proc WHERE proname = '$function'";

        $functiondef = $this->_conn->query($sql)->fetchAll();
        return $functiondef;
    }

    function SelectTypeForArg($proargtypes) {
        $sql = "SELECT typname FROM pg_type WHERE oid = '$proargtypes'";

        $arg = $this->_conn->query($sql)->fetchAll();
        return $arg;
    }

    function SelectTypeForReturn($return) {
        $sql = "SELECT typname FROM pg_type WHERE oid = $return";

        $ret = $this->_conn->query($sql)->fetchAll();
        return $ret;
    }

    function SelectTriggerForSchema($schema) {
        $sql = "SELECT trigger_name, action_timing, event_manipulation, event_object_schema, event_object_table, action_orientation, action_statement
                    FROM information_schema.triggers WHERE trigger_schema = '$schema'";

        $trigger = $this->_conn->query($sql)->fetchAll();
        return $trigger;
    }

    function SelectTriggerForSchemaTriggernameEvent($schema, $trigger, $event) {
        $sql = "SELECT trigger_name, action_timing, event_manipulation, event_object_schema, event_object_table, action_orientation, action_statement
                FROM information_schema.triggers WHERE trigger_schema = '$schema' AND trigger_name = '$trigger' AND event_object_table = '$event'";

        $trigger = $this->_conn->query($sql)->fetchAll();
        return $trigger;
    }
    
    function SelectArqTypes($function){
        $sql = "SELECT proname, proargtypes FROM pg_proc WHERE proname='$function'";
        
        $argtypes = $this->_conn->query($sql)->fetchAll();
        return $argtypes;
    }

    function SelectTypeName($proargtypes){
        $sql = $sql = "SELECT typname FROM pg_type WHERE oid = '$proargtypes'";
        
        $typename = $this->_conn->query($sql)->fetchAll();
        return $typename;
    }
    
    function SelectSequences($schema, $sequence){
        $sql = "SELECT sequence_schema, sequence_name FROM information_schema.sequences WHERE sequence_schema='$schema' AND sequence_name='$sequence'";
           
        $seq = $this->_conn->query($sql)->fetchAll();
        return $seq;
    }
    
    function SelectTypesForSchema($schema) {
        $sql = "select distinct ty.object_name
                from information_schema.data_type_privileges ty
                join information_schema.attributes att on ty.object_name = att.udt_name
                where ty.object_schema = '$schema' and ty.object_type = 'USER-DEFINED TYPE'
                order by object_name;";
        
        $types = $this->_conn->query($sql)->fetchAll();
        return $types;
    }
    
    function SelectAllDataTypes($obj, $typename) {
        $schema = preg_split("/\^/", $obj);
        $sql = "select ty.object_name, att.attribute_name, att.data_type, att.character_maximum_length, att.numeric_precision, att.numeric_scale               
                from information_schema.data_type_privileges ty
                join information_schema.attributes att on ty.object_name = att.udt_name
                where ty.object_schema = '$schema[0]' and ty.object_type = 'USER-DEFINED TYPE'
                and ty.object_name = $typename
                order by object_name;";
        
        $types = $this->_conn->query($sql)->fetchAll();
        return $types;
    }
}

?>