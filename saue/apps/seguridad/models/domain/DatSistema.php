<?php

/*
 * Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */

class DatSistema extends BaseDatSistema {

    public function setUp() {
        parent::setUp();
        $this->hasMany('DatSistemaSegRol', array('local' => 'idsistema', 'foreign' => 'idsistema'));
        $this->hasMany('DatFuncionalidad', array('local' => 'idsistema', 'foreign' => 'idsistema'));
        $this->hasMany('DatServicio', array('local' => 'idsistema', 'foreign' => 'idsistema'));
        $this->hasMany('DatServicioDatSistema', array('local' => 'idsistema', 'foreign' => 'idsistema'));
        $this->hasMany('DatSistemaDatServidores', array('local' => 'idsistema', 'foreign' => 'idsistema'));
        $this->hasMany('DatSistemaCompartimentacion', array('local' => 'idsistema', 'foreign' => 'idsistema'));
        $this->hasOne('DatSistema as Padre', array('local' => 'idpadre', 'foreign' => 'idsistema'));
        $this->hasMany('DatSistema as Hijos', array('local' => 'idsistema', 'foreign' => 'idpadre'));
    }

    static public function cargarsistema($idnodo) {
        $global = ZendExt_GlobalConcept::getInstance();
        $iddominio = $global->Perfil->iddominio;
        $q = Doctrine_Query::create();
        if ($idnodo) {
            $sqlWhere = "idpadre = ? and idpadre <> idsistema and iddominio = ?";
            $filtro = array($idnodo, $iddominio);
        } else {
            $sqlWhere = "idpadre = idsistema and idsistema <> 0 and iddominio = ?";
            $filtro = $iddominio;
        }
        $result = $q->select('idsistema id, denominacion text, descripcion, icono,abreviatura,idpadre,externa, (rgt - lft = 1) as leaf, index')
                ->from('DatSistema')
                ->where($sqlWhere, $filtro)
                ->orderby('index')
                ->execute()->toArray();
        return $result;
    }

    static public function cargarSistemasCompartimentacion($idnodo, $iddominioComp) {
        $global = ZendExt_GlobalConcept::getInstance();
        $iddominio = $global->Perfil->iddominio;
        $q = Doctrine_Query::create();
        if ($idnodo) {
            $sqlWhere = "s.idpadre = ? and s.idpadre <> s.idsistema and s.iddominio = ? and sc.iddominio = ?";
            $filtro = array($idnodo, $iddominio, $iddominioComp);
        } else {
            $sqlWhere = "s.idpadre = s.idsistema and s.idsistema <> 0 and s.iddominio = ? and sc.iddominio = ?";
            $filtro = array($iddominio, $iddominioComp);
        }
        $result = $q->select('s.idsistema id, s.denominacion text, (s.rgt - s.lft = 1) as leaf, sc.idsistema as checked')
                ->from('DatSistema s')
                ->leftJoin('s.DatSistemaCompartimentacion sc')
                ->where($sqlWhere, $filtro)
                ->orderby('idsistema')
                ->execute();
        return $result;
    }

    static public function cargarArbolSistemasCompartimentacion($idnodo) {
        $global = ZendExt_GlobalConcept::getInstance();
        $iddominio = $global->Perfil->iddominio;
        $q = Doctrine_Query::create();
        if ($idnodo) {            
            $sqlWhere = "s.idpadre <> s.idsistema and s.idpadre = ? and sc.iddominio = ?";            
            $filtro = array($idnodo, $iddominio);
        } else {            
            $sqlWhere = "s.idpadre = s.idsistema and s.idsistema <> 0 and sc.iddominio = ?";            
            $filtro = array($iddominio);
        }
        $result = $q->select('s.idsistema, s.idsistema id, s.denominacion text, (s.rgt - s.lft = 1) as leaf')
                ->from('DatSistema s')
                ->innerJoin('s.DatSistemaCompartimentacion sc ON s.idsistema = sc.idsistema')
                ->where($sqlWhere, $filtro)
                ->orderby('idsistema')
                ->execute()->toArray();        
        return $result;
    }

    static public function cargarsistemasdelrol($idnodo, $idrol) {
        $query = Doctrine_Query::create();
        if ($idnodo)
            $result = $query->select('DISTINCT (s.idsistema ) id, s.denominacion text, s.denominacion ')->from('DatSistema s')->innerJoin('s.DatSistemaSegRol sr')->where("sr.idrol = ? and s.idpadre = ? and s.idsistema <> s.idpadre ", array($idrol, $idnodo))->execute()->toArray(true);
        else
            $result = $query->select('DISTINCT (s.idsistema ) id, s.denominacion text, s.denominacion ')->from('DatSistema s')->innerJoin('s.DatSistemaSegRol sr')->where("sr.idrol = ? and s.idpadre = s.idsistema and s.idsistema <> 0 ", $idrol)->execute()->toArray(true);
        return $result;
    }

    static public function obtenersistemas($idrol) {
        $query = Doctrine_Query::create();
        $result = $query->select('DISTINCT (s.denominacion) text, s.denominacion, s.idsistema id, s.descripcion, s.icono, s.externa, s.index')
        ->from('DatSistema s')
        ->innerJoin('s.DatSistemaSegRol sr')
        ->where("sr.idrol = ? and s.idsistema = s.idpadre", $idrol)
		->orderby('s.index')
		->execute();
        return $result;
    }

    static public function verificarrolsistema($idrol, $idsistema) {
        $query = Doctrine_Query::create();
        $result = $query->select('COUNT(sr.idrol) idrol')->from('DatSistemaSegRol sr')->where("sr.idrol = ? and sr.idsistema = ?", array($idrol, $idsistema))->execute();
        return $result[0]->idrol != 0;
    }

    static public function cargarsistemahijos($idsistema, $idrol) {
        $query = Doctrine_Query::create();
        $result = $query->select('s.denominacion text, s.idsistema id,s.idsistema,s.denominacion,s.descripcion,s.icono,s.externa, s.index')
        ->from('DatSistema s')
        ->innerjoin('s.DatSistemaSegRol sr ON s.idsistema = sr.idsistema')
        ->where("sr.idrol = ? AND s.idpadre = ? AND s.idsistema <> s.idpadre", array($idrol, $idsistema))
        ->orderby('s.index')
        ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)->execute();
        return $result;
    }

    static public function arraysistemasDominio($arrayRoles) {
        $query = Doctrine_Query::create();
        $result = $query->select('DISTINCT (s.denominacion) text, s.denominacion, s.idsistema, s.descripcion, s.icono, s.externa')
                ->from('DatSistema s')
                ->innerJoin('s.DatSistemaSegRol sr')
                ->where("s.idsistema = s.idpadre")
                ->whereIn("sr.idrol", $arrayRoles)
                //->orderby('s.index')
                ->execute();
        return $result;
    }

    static public function arraySistemasHijosDadoArrayRoles($idsistema, $arrayRoles) {
        $query = Doctrine_Query::create();
        $result = $query->select('DISTINCT (s.denominacion) text, s.denominacion, s.idsistema, s.descripcion, s.icono, s.externa')
                ->from('DatSistema s')
                ->innerJoin('s.DatSistemaSegRol sr')
                ->where("s.idpadre = ? AND s.idsistema <> s.idpadre", $idsistema)
                ->whereIn("sr.idrol", $arrayRoles)
                //->orderby('s.index')
                ->execute();
        return $result;
    }

    static public function cargarsistemahijjos($idsistema) {
        $query = Doctrine_Query::create();
        $result = $query->select('DISTINCT (s.idsistema) id, s.denominacion,s.descripcion, s.icono,s.externa')->from('DatSistema s')->where("s.idpadre <> s.idsistema and s.idpadre = ?", $idsistema)->execute();
        return $result;
    }

    static public function obtenersistemasxml($idrol, $modulo) {
        $query = Doctrine_Query::create();
        $result = $query->select('DISTINCT (s.denominacion) text, s.denominacion, s.idsistema id, s.descripcion, s.icono,s.externa')->from('DatSistema s')->innerJoin('s.DatSistemaSegRol sr ON s.idsistema = sr.idsistema')->where("sr.idrol = ? and s.abreviatura = ? and s.idsistema = s.idpadre", array($idrol, $modulo))->execute();
        return $result;
    }

    static public function obtenersistemaexportarxml($idsistema) {
        $query = Doctrine_Query::create();
        $result = $query->select('DISTINCT (s.denominacion) text, s.denominacion, s.idsistema id, s.descripcion, s.icono,s.externa')->from('DatSistema s')->where("s.idsistema = ? and s.idsistema = s.idpadre", $idsistema)->execute();
        return $result;
    }

    static public function obtenersistemashijos($idsistema) {
        $query = Doctrine_Query::create();
        $result = $query->select('DISTINCT (s.denominacion) text, s.denominacion, s.idsistema id,s.idsistema, s.descripcion, s.icono,s.externa')->from('DatSistema s')->where("s.idpadre = ? and  s.idsistema <> s.idpadre", array($idsistema))->execute();
        return $result;
    }

    static public function eliminarsistema($idsistema) {
        $query = Doctrine_Query::create();
        $query->delete()->from('DatSistema')->where("idsistema = ?", $idsistema)->execute();
        return true;
    }

    static public function buscarservidorweb($idpadre) {
        $query = Doctrine_Query::create();
        $result = $query->select('externa')->from('DatSistema')->where("idsistema = ?", $idpadre)->execute();
        return $result;
    }

    static function verificarsistema($denominacion, $abreviatura) {
        $query = Doctrine_Query::create();
        $var = $query->from('DatSistema s')->where("s.denominacion = ? or s.abreviatura = ?", array($denominacion, $abreviatura))->count();
        return $var;
    }

    static public function obtenerSistemasV($idpadre) {
        $query = Doctrine_Query::create();
        $datos = $query->select('a.denominacion, a.abreviatura')->from('DatSistema a')->where("a.idpadre = ?", $idpadre)->setHydrationMode(Doctrine :: HYDRATE_ARRAY)->execute();
        return $datos;
    }

    static public function obtenerSistemasP() {
        $query = Doctrine_Query::create();
        $datos = $query->select('a.denominacion, a.abreviatura')->from('DatSistema a')->where("a.idpadre = a.idsistema")->setHydrationMode(Doctrine :: HYDRATE_ARRAY)->execute();
        return $datos;
    }

    ///-----
    static public function ObtenerPadre($sistema) {
        $query = Doctrine_Query::create();
        $result = $query->select('s.idpadre')
                ->from('DatSistema s')
                ->where('s.idsistema=?', array($sistema))
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();

        return $result;
    }

    static public function obtenerSistemasDominio($domain) {
        $query = Doctrine_Query::create();
        $result = $query->select('DISTINCT (s.denominacion) text, s.denominacion, s.idsistema id, s.abreviatura, s.idpadre')->from('DatSistema s')->where("s.iddominio = ?", $domain)->execute()->toArray(true);
        return $result;
    }

    ///**************************
    static public function ObtenerSistemaXid($idsistema) {
        $sistema = Doctrine::getTable('DatSistema')->find($idsistema);
        return $sistema;
    }

    static public function getsistemas() {
        $sistema = Doctrine::getTable('DatSistema')->findAll();
        return $sistema;
    }

    static public function getsistema($idsistema) {
        $sistema = Doctrine::getTable('DatSistema')->find($idsistema);
        return $sistema;
    }

    static public function ValorMaximo() {
        $query = Doctrine_Query::create();
        $datos = $query->select('MAX(a.rgt)')->from('DatSistema a')->setHydrationMode(Doctrine :: HYDRATE_ARRAY)->execute();
        return $datos;
    }

}
