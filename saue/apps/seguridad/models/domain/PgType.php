<?php

class PgType extends BasePgType
{

    public function setUp() {
    parent::setUp();
    $this->hasOne('PgNamespace',array('local'=>'pronamespace','foreign'=>'oid'));    
    }

    static public function TiposParametrosFuncion($value){
        /*
         * "select tp.typname from pg_type tp where tp.oid=$value")->fetchAll();
         */
       $dm = Doctrine_Manager::getInstance();
       $conn = $dm->getCurrentConnection();
       $conn->exec("set search_path = pg_catalog;");

       $query = Doctrine_Query::create();

       $resultado=$query->select('t.typname')
                        ->from('PgType t')
                        ->where('t.oid=?',array($value))->execute()->toArray();
        
        return $resultado;
    }

}
