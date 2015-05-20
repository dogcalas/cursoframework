<?php
require_once(realpath(dirname(__FILE__)) . '/Driver.php');

/**
 * @access public
 */
class PostgreSQL extends Driver {

    public function  __construct($pConn) {
        parent :: __construct ($pConn);
    }

    public function getShortName() {
        return 'pgsql';
    }

    public function getTables ($pDb) {
        $sql = "SELECT table_schema || '.' || table_name as table_name
                FROM information_schema.tables
                where table_catalog = '$pDb' and table_schema != 'information_schema' and table_schema != 'pg_catalog'";

        $result = $this->_conn->get_doctrine ()->execute ($sql)->fetchAll (Doctrine::FETCH_ASSOC);

        $tables = array ();
        foreach ($result as $table) {
            $t = new Table();
            $t->set_name($table['table_name']);
            $tables[] = $t; $t = null;
        }
        return $tables;
    }

    public function get_fields ($pTable) {
        $parts = explode('.', $pTable);
        $schema = $parts[0]; $table = $parts[1];

        $sql = "SELECT column_name, is_nullable, data_type, character_maximum_length, column_default FROM information_schema.columns WHERE table_schema = '$schema' and table_name='$table'";

        $result = $this->_conn->get_doctrine ()->execute ($sql)->fetchAll (Doctrine::FETCH_ASSOC);

        $fields = array ();

        foreach ($result as $f) {
            $ff = new Field ();
            $ff->set_name ($f['column_name']);
            $ff->set_null ($f['is_nullable'] != 'NO');
            $ff->set_type ($f ['data_type']);

            $seq = $f['column_default'];
            $ff->set_default($q);
            
            $seq = substr ($seq, strpos ($seq, "'") + 1, strrpos ($seq, "'") - strpos ($seq, "'") - 5);

            $ff->set_sequence ($seq); 

            $s = "select count(uc.column_name) as pk from information_schema.key_column_usage uc, information_schema.table_constraints ucc where uc.constraint_name = ucc.constraint_name and uc.table_name='$table' and uc.column_name = '{$f['column_name']}' and (ucc.constraint_type='PRIMARY KEY')";
            $r = $this->_conn->get_doctrine ()->execute ($s)->fetchAll (Doctrine::FETCH_ASSOC);
            
            $ff->set_primary_key ($r [0]['pk']);

            $fields [] = $ff; $ff = null;
        }

        return $fields;
    }

    public function get_relations ($pTable) {
        $parts = explode('.', $pTable);
        $schema = $parts[0]; $table = $parts[1];

        $sql = "SELECT kcu.column_name as campo_origen, (ccu.table_schema ||'.'|| ccu.table_name) AS tabla_destino,  ccu.column_name AS columna_destino
                FROM information_schema.table_constraints AS tc
                JOIN information_schema.key_column_usage AS kcu ON tc.constraint_name = kcu.constraint_name
                JOIN information_schema.constraint_column_usage AS ccu ON ccu.constraint_name = tc.constraint_name 
                WHERE constraint_type = 'FOREIGN KEY' AND tc.table_name='$table'";


        $relations = $this->_conn->get_doctrine ()->execute ($sql)->fetchAll (Doctrine::FETCH_ASSOC);

        $result = array ();

        foreach ($relations as $relation) {
            $r = new HasOne (); $rr = new HasMany();

            $r->set_foreign_table ($relation ['tabla_destino']);
            $r->set_local_field ($relation ['campo_origen']);
            $r->set_foreign_field ($relation ['columna_destino']);

            $rr->set_foreign_table ($pTable);
            $rr->set_foreign_field ($relation ['campo_origen']);
            $rr->set_local_field ($relation ['columna_destino']);

            $r->set_inverse ($rr); $rr->set_inverse ($r);
            $result[] = $r;
        }

        return $result;
    }
}
?>