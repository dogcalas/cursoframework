
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

class DatEsquema extends BaseDatEsquema {

    public function setUp() {
        parent::setUp();

        $this->hasMany('DatSistemaDatServidores', array('local' => 'idesquema', 'foreign' => 'idesquema'));
    }

    static function cargaresquemas($idservidor, $idgestor, $idbd, $limit, $start) {
        $query = Doctrine_Query::create();
        $fndes = $query->select('e.idesquema id, e.denominacion text,e.descripcion')->from('DatEsquema e,e.DatServidorDatGestorDatBdDatEsquema b')->where("b.idservidor = ? and b.idgestor=? and b.idbd=?", array($idservidor, $idgestor, $idbd))->orderby('e.idesquema')->limit($limit)->offset($start)->execute();
        return $fndes;
    }

    static function obtenercantesqsistema($idservidor, $idgestor, $idbd, $idesquema) {
        $query = Doctrine_Query::create();
        $cantesqsist = $query->from('DatSistemaDatServidores s')->where("s.idservidor = ? and s.idgestor = ? and s.idbd = ? and s.idesquema = ?", array($idservidor, $idgestor, $idbd, $idesquema))->count();
        return $cantesqsist;
    }

    static public function obtenercantesquemas($idservidor, $idgestor, $idbd) {
        $query = Doctrine_Query::create();
        $cantFndes = $query->from('DatEsquema e,e.DatServidorDatGestorDatBdDatEsquema b')->where("b.idservidor = ? and b.idgestor=? and b.idbd=?", array($idservidor, $idgestor, $idbd))->count();
        return $cantFndes;
    }

    static function cargarcomboesquemas($idservidor, $idgestor, $idbd) {
        $query = Doctrine_Query::create();
        $query1 = Doctrine_Query::create();
        $adentro = $query->select('e.idesquema')->from('DatEsquema e,e.DatServidorDatGestorDatBdDatEsquema a')->where("a.idservidor=? and a.idgestor=? and  a.idbd=?", array($idservidor, $idgestor, $idbd))->execute();
        $adentro1 = array();
        foreach ($adentro as $obj)
            $adentro1[] = $obj->idesquema;
        $afuera = $query1->select('e.idesquema, e.denominacion')->from('DatEsquema e')->whereNotIn('e.idesquema', $adentro1)->execute();
        return $afuera;
    }

    static function obtenercantesqsistemaserv($idesquema) {
        $query = Doctrine_Query::create();
        $cantesqsist = $query->from('DatSistemaDatServidores s')->where("s.idesquema = ?", $idesquema)->count();
        return $cantesqsist;
    }

    static function cargarnomesquemas() {
        $query = Doctrine_Query::create();
        $esquemas = $query->select('e.denominacion')
                ->from('DatEsquema e ')               
                ->execute()->toArray();
        return $esquemas;
    }

    static function obtenercantnomesquemas() {
        $query = Doctrine_Query::create();
        $cant = $query->from('DatEsquema')->count();
        return $cant;
    }

    static public function buscarnomesquemas($denominacion, $limit, $start) {
        $query = Doctrine_Query::create();
        $datos = $query->select('idesquema, denominacion, descripcion')->from('DatEsquema')->where("denominacion like '$denominacion%'")->limit($limit)->offset($start)->execute();
        return $datos;
    }

    static public function obtenercantnomesquemasbuscados($denominacion) {
        $query = Doctrine_Query::create();
        $cant = $query->from('DatEsquema')->where("denominacion like '$denominacion%'")->count();
        return $cant;
    }

    static public function borrarEsqFisicamente($arrEsq) {
        $query = Doctrine_Query::create();
        $datos = $query->delete()
                ->from('DatEsquema')
                ->whereIn('idesquema', $arrEsq)
                ->execute();
    }

    static public function getSchemasConfig($idsistema, $idservidor, $idgestor, $idbd) {
        $query = Doctrine_Query::create();
        $datos = $query->select('e.denominacion text, e.idesquema id')
                ->from('DatEsquema e')
                ->innerJoin('e.DatSistemaDatServidores ss')
                ->where("ss.idsistema = ? and ss.idservidor = ? and ss.idgestor = ? and ss.idbd = ?", array($idsistema, $idservidor, $idgestor, $idbd))
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();
        return $datos;
    }

    static public function deleteSchemas($arrayNotUsed) {
        $query = Doctrine_Query::create();
        $datos = $query->delete()
                ->from('DatEsquema')
                ->whereIn('denominacion', $arrayNotUsed)
                ->execute();
    }

    ////-----------------------------------------------------
    static public function obtenerNomEsquemaXId($idesquema) {
        $query = Doctrine_Query::create();
        $esquemas = $query->select('e.denominacion')
                        ->from('DatEsquema e')
                        ->where('e.idesquema=?', array($idesquema))->execute();
        return $esquemas[0]['denominacion'];
    }

}
