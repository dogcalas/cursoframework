<?php

class PgAuthid extends BasePgAuthid {

    static public function getRolBD($conn, $limit, $start) {
        $conn->exec("set search_path = pg_catalog;");
        $query = Doctrine_Query::create();
        return $query->select('oid, rolname, rolsuper, rolinherit, rolcreaterole, rolcreatedb, rolcatupdate, rolpassword, rolcanlogin, rolvaliduntil')
                        ->from('PgAuthid')
                        ->orderby('rolname')
                        ->limit($limit)
                        ->offset($start)
                        ->execute();
    }

    static public function cantRolBD($conn) {
        $conn->exec("set search_path = pg_catalog;");
        $query = Doctrine_Query::create();
        return $query->from('PgAuthid')
                        ->count();
    }

    static public function getRolBDbyName($conn, $nombreRol, $limit, $start) {
        $conn->exec("set search_path = pg_catalog;");
        $query = Doctrine_Query::create();
        return $query->select('oid, rolname, rolsuper, rolinherit, rolcreaterole, rolcreatedb, rolcatupdate, rolpassword, rolcanlogin, rolvaliduntil')
                        ->from('PgAuthid')
                        ->where("rolname like '%$nombreRol%' ESCAPE '!'")
                        ->orderby('rolname')
                        ->limit($limit)
                        ->offset($start)
                        ->execute();
    }

    static public function cantRolBDbyName($conn, $nombreRol) {
        $conn->exec("set search_path = pg_catalog;");
        $query = Doctrine_Query::create();
        return $query->from('PgAuthid')
                        ->where("rolname like '$nombreRol%' ESCAPE '!'")
                        ->count();
    }

    static public function verifyRolBD($conn, $user) {
        $conn->exec("set search_path = pg_catalog;");
        $query = Doctrine_Query::create();
        $result = $query->select('rolcatupdate')
                ->from('PgAuthid')
                ->where("rolname like '$user'")
                ->execute();
        return $result[0]->rolcatupdate;
    }

    static public function getRoles($conn) {
        $conn->exec("set search_path = pg_catalog;");
        $query = Doctrine_Query::create();
        return $query->select('oid, rolname,rolpassword')
                        ->from('PgAuthid')
                        ->execute();
    }

    //--------------------
    static public function existRolname($conn, $rolname) {
        $conn->exec("set search_path = pg_catalog;");
        $query = Doctrine_Query::create();
        return $query->select('oid, rolname, rolsuper, rolinherit, rolcreaterole, rolcreatedb, rolcatupdate, rolpassword, rolcanlogin, rolvaliduntil')
                        ->from('PgAuthid')
                        ->where('rolname=?', array($rolname))
                        ->orderby('rolname')
                        ->execute();
    } 

}