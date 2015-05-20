<?php

class PgNamespace extends BasePgNamespace
{
    public function setUp() {
    parent::setUp();
    $this->hasMany('PgClass',array('local'=>'oid','foreign'=>'relnamespace'));
    $this->hasMany('PgProc',array('local'=>'oid','foreign'=>'pronamespace'));    
    } 

    static public function getInformation($conn, $criterio, $limit, $offset) {
        $conn->exec("set search_path = pg_catalog;");
        if($criterio == 'Sequences')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='S'";
        if($criterio == 'Tables')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='r'";
        if($criterio == 'Views')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='v'";
        $query = Doctrine_Query::create();
        return  $query  ->select('ns.oid, ns.nspname, cl.relname, cl.relowner, cl.relacl, cl.relname name')
                        ->from('PgClass cl')
                        ->innerjoin('cl.PgNamespace ns ON ns.oid = cl.relnamespace')
                        ->where($where)
                        ->orderby('cl.relname')
                        ->limit($limit)
                        ->offset($offset)
                        ->execute();
      }
      
    static public function getCantRecords($conn, $criterio) {
        $conn->exec("set search_path = pg_catalog;");
        if($criterio == 'Sequences')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='S'";
        if($criterio == 'Tables')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='r' ";
        if($criterio == 'Views')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='v'";
        $query = Doctrine_Query::create();
        return  $query  ->select('ns.oid, ns.nspname, cl.relname, cl.relacl permisos, cl.relname name')
                        ->from('PgClass cl')
                        ->innerjoin('cl.PgNamespace ns ON ns.oid = cl.relnamespace')
                        ->where($where)
                        ->count();
      }
      
    static public function getInformationSchemas($conn, $limit, $offset) {
        $conn->exec("set search_path = pg_catalog;");
        $query = Doctrine_Query::create();
        return  $query  ->select('ns.oid, ns.nspname, ns.nspowner, ns.nspacl')
                        ->from('PgNamespace ns')
                        ->where("ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!'")
                        ->orderby('ns.nspname')
                        ->limit($limit)
                        ->offset($offset)
                        ->execute();
      }
      
    static public function getRecordsSchemas($conn) {
        $conn->exec("set search_path = pg_catalog;");
        $query = Doctrine_Query::create();
        return  $query  ->select('ns.oid, ns.nspname name, ns.nspowner, ns.nspacl')
                        ->from('PgNamespace ns')
                        ->where("ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!'")
                        ->orderby('ns.nspname')
                        ->count();
      }
      
	static public function getInformationByCriteria($conn, $criterio, $esquema,$objeto, $limit, $offset,$DosMiembros) {
        $conn->exec("set search_path = pg_catalog;"); 
        if($DosMiembros){
            if($criterio == 'Sequences')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='S' AND (ns.nspname LIKE '%$esquema' ESCAPE '!' AND cl.relname LIKE '$objeto%' ESCAPE '!')";
        if($criterio == 'Tables')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='r' AND (ns.nspname LIKE '%$esquema' ESCAPE '!' AND cl.relname LIKE '$objeto%' ESCAPE '!')";
        if($criterio == 'Views')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='v' AND (ns.nspname LIKE '%$esquema' ESCAPE '!' AND cl.relname LIKE '$objeto%' ESCAPE '!')";
        }else{  
        if($criterio == 'Sequences')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='S' AND (ns.nspname LIKE '%$esquema%' ESCAPE '!' OR cl.relname LIKE '%$objeto%' ESCAPE '!')";
        if($criterio == 'Tables')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='r' AND (ns.nspname LIKE '%$esquema%' ESCAPE '!' OR cl.relname LIKE '%$objeto%' ESCAPE '!')";
        if($criterio == 'Views')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='v' AND (ns.nspname LIKE '%$esquema%' ESCAPE '!' OR cl.relname LIKE '%$objeto%' ESCAPE '!')";
        }
        $query = Doctrine_Query::create();
        return  $query  ->select('ns.oid, ns.nspname, cl.relname, cl.relowner, cl.relacl, cl.relname name')
                        ->from('PgClass cl')
                        ->innerjoin('cl.PgNamespace ns ON ns.oid = cl.relnamespace')
                        ->where($where)
                        ->orderby('cl.relname')
                        ->limit($limit)
                        ->offset($offset)
                        ->execute();
      }
      
	static public function getCantRecordsByCriteria($conn, $criterio, $esquema,$objeto,$DosMiembros) {
        $conn->exec("set search_path = pg_catalog;");
       if($DosMiembros){
            if($criterio == 'Sequences')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='S' AND (ns.nspname LIKE '%$esquema' ESCAPE '!' AND cl.relname LIKE '$objeto%' ESCAPE '!')";
        if($criterio == 'Tables')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='r' AND (ns.nspname LIKE '%$esquema' ESCAPE '!' AND cl.relname LIKE '$objeto%' ESCAPE '!')";
        if($criterio == 'Views')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='v' AND (ns.nspname LIKE '%$esquema' ESCAPE '!' AND cl.relname LIKE '$objeto%' ESCAPE '!')";
        }else{
        if($criterio == 'Sequences')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='S' AND (ns.nspname LIKE '%$esquema%' ESCAPE '!' OR cl.relname LIKE '%$objeto%' ESCAPE '!')";
        if($criterio == 'Tables')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='r' AND (ns.nspname LIKE '%$esquema%' ESCAPE '!' OR cl.relname LIKE '%$objeto%' ESCAPE '!')";
        if($criterio == 'Views')
            $where = "ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND cl.relkind ='v' AND (ns.nspname LIKE '%$esquema%' ESCAPE '!' OR cl.relname LIKE '%$objeto%' ESCAPE '!')";
        }
        $query = Doctrine_Query::create();
        return  $query  ->select('ns.oid, ns.nspname, cl.relname, cl.relacl permisos, cl.relname name')
                        ->from('PgClass cl')
                        ->innerjoin('cl.PgNamespace ns ON ns.oid = cl.relnamespace')
                        ->where($where)
                        ->count();
      }
      
	static public function getInformationSchemasByCriteria($conn, $esqSelected, $limit, $offset) {
        $conn->exec("set search_path = pg_catalog;");
        $query = Doctrine_Query::create();
        return  $query  ->select('ns.oid, ns.nspname, ns.nspowner, ns.nspacl')
                        ->from('PgNamespace ns')
                        ->where("ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND ns.nspname LIKE '%$esqSelected%' ESCAPE '!'")
                        ->orderby('ns.nspname')
                        ->limit($limit)
                        ->offset($offset)
                        ->execute();
      }
      
	static public function getRecordsSchemasByCriteria($conn, $esqSelected) {
        $conn->exec("set search_path = pg_catalog;");
        $query = Doctrine_Query::create();
        return  $query  ->select('ns.oid, ns.nspname name, ns.nspowner, ns.nspacl')
                        ->from('PgNamespace ns')
                        ->where("ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND ns.nspname LIKE '%$esqSelected%' ESCAPE '!'")
                        ->orderby('ns.nspname')
                        ->count();
      }
}