<?php

class PgProc extends BasePgProc
{

    public function setUp() {
    parent::setUp();
    $this->hasOne('PgNamespace',array('local'=>'pronamespace','foreign'=>'oid'));    
    }

    static public function getInformation($conn, $limit, $offset) {
        $conn->exec("set search_path = pg_catalog;");
        $query = Doctrine_Query::create();
        return  $query  ->select('ns.oid, ns.nspname, p.proowner, p.pronamespace , p.proacl , p.proname, p.oid')
                        ->from('PgProc p')
                        ->innerjoin('p.PgNamespace ns ON ns.oid = p.pronamespace')
                        ->where("ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!'")
                        ->orderby('p.proname')
                        ->limit($limit)
                        ->offset($offset)
                        ->execute();
      }
      
    static public function getCantRecords($conn) {
        $conn->exec("set search_path = pg_catalog;");
        $query = Doctrine_Query::create();
        return  $query  ->select('ns.oid, p.oid')
                        ->from('PgProc p')
                        ->innerjoin('p.PgNamespace ns ON ns.oid = p.pronamespace')
                        ->where("ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!'")
                        ->count();
      }
      
	static public function getInformationByCriteria($conn, $esquema,$objeto, $limit, $offset,$DosMiembros) {
        $conn->exec("set search_path = pg_catalog;");
        $query = Doctrine_Query::create();
        $where="ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND (ns.nspname LIKE '%$esquema%' ESCAPE '!' OR p.proname LIKE '%$objeto%' ESCAPE '!')";
        if($DosMiembros)
            $where="ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND (ns.nspname LIKE '%$esquema' ESCAPE '!' AND p.proname LIKE '$objeto%' ESCAPE '!')";
        return  $query  ->select('ns.oid, ns.nspname, p.proowner, p.pronamespace , p.proacl , p.proname, p.oid')
                        ->from('PgProc p')
                        ->innerjoin('p.PgNamespace ns ON ns.oid = p.pronamespace')
                        ->where($where)
                        ->orderby('p.proname')
                        ->limit($limit)
                        ->offset($offset)
                        ->execute();
      }
      
	static public function getCantRecordsByCriteria($conn, $esquema,$objeto,$DosMiembros) {
        $conn->exec("set search_path = pg_catalog;");
        $query = Doctrine_Query::create();
        $where="ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND (ns.nspname LIKE '%$esquema%' ESCAPE '!' OR p.proname LIKE '%$objeto%' ESCAPE '!')";
        if($DosMiembros)
            $where="ns.nspname NOT LIKE 'pg!_%' ESCAPE '!' AND ns.nspname NOT LIKE 'information!_%' ESCAPE '!' AND (ns.nspname LIKE '%$esquema' ESCAPE '!' AND p.proname LIKE '$objeto%' ESCAPE '!')";
        return  $query  ->select('ns.oid, p.oid')
                        ->from('PgProc p')
                        ->innerjoin('p.PgNamespace ns ON ns.oid = p.pronamespace')
                        ->where($where)
                        ->count();
      }
      
      ///---------------------------------------------
      static public function ObtenerParametrosdeFuncion($esquema,$funcion,$servidor,$gestor,$puerto,$nameBd,$user,$pass){
           
       $dm = Doctrine_Manager::getInstance();
       $NameConnection = $dm->getCurrentConnection()->getName();
       $conn = $dm->openConnection("$gestor://$user:$pass@$servidor:$puerto/$nameBd", 'pg_catalog');
       $conn->exec("set search_path = pg_catalog;");
       $query = Doctrine_Query::create();
        
       $sql=$query->select('p.proargtypes')
                  ->from('PgProc p')
                  ->innerjoin('p.PgNamespace ns ON ns.oid = p.pronamespace')
                  ->where('p.proname=? AND ns.nspname=?',array($funcion,$esquema))
                  ->execute()->toArray();
       
       $id_pg_type=explode(" ", $sql[0]['proargtypes']);
       $parametros=array();
       $arrayparametros=array();
       

       if(!empty ($id_pg_type[0])){

        /*
         * Hacer el Mapeo de la tabla que se emplea debajo
         */
       foreach ($id_pg_type as $value){
            $parametros[]=PgType::TiposParametrosFuncion($value);
         
       }
       
       foreach ($parametros as $value){
           
           $arrayparametros[]=PgProc::TransalateParameter($value[0]['typname']);
       }
       }
       $dm->closeConnection($conn);
       $dm->setCurrentConnection($NameConnection);
       return $arrayparametros=implode(",", $arrayparametros);
}

static public function ObtenerParametrosdeFuncionConn($esquema,$funcion,$conn){
                  
       $conn->exec("set search_path = pg_catalog;");
       $query = Doctrine_Query::create();
        
       $sql=$query->select('p.proargtypes')
                  ->from('PgProc p')
                  ->innerjoin('p.PgNamespace ns ON ns.oid = p.pronamespace')
                  ->where('p.proname=? AND ns.nspname=?',array($funcion,$esquema))
                  ->execute()->toArray();
       
       $id_pg_type=explode(" ", $sql[0]['proargtypes']);
       $parametros=array();
       $arrayparametros=array();
       

       if(!empty ($id_pg_type[0])){

        /*
         * Hacer el Mapeo de la tabla que se emplea debajo
         */
       foreach ($id_pg_type as $value){
            $parametros[]=PgType::TiposParametrosFuncion($value);
         
       }
       
       foreach ($parametros as $value){
           
           $arrayparametros[]=PgProc::TransalateParameter($value[0]['typname']);
       }
       }
      
       return $arrayparametros=implode(",", $arrayparametros);
}

static public function TransalateParameter($parametro){
    $posicion=strpos($parametro,"_");
    if($posicion===false){
        return $parametro;
    }
    return substr($parametro,1).'[]';
}
}
