<?php

class NomAutenticacion extends BaseNomAutenticacion {

    public function setUp() {
        parent :: setUp();
    }

    static public function cargarnomautenticacion($limit, $start) {

        $query = new Doctrine_Query ();
        $result = $query->select('t.idautenticacion,t.denominacion,t.abreviatura,t.descripcion,t.activo')->from('NomAutenticacion t')->limit($limit)
                ->offset($start)
                ->execute();
        return $result;
    }

    static public function obtenercantnomautenticacion() {
        $query = new Doctrine_Query ();
        $cant = $query->from('NomAutenticacion')->count();
        return $cant;
    }

    static public function comprobarautenticacion($denominacion, $abreviatura) {
        $query = Doctrine_Query::create();
        $cantidadautent = $query->from('NomAutenticacion')->where("denominacion = ? OR abreviatura = ?", array($denominacion, $abreviatura))->count();
        return $cantidadautent;
    }

    static public function cargarcomboautenticacion() {

        $query = new Doctrine_Query ();
        $result = $query->select('t.idautenticacion,t.denominacion,t.abreviatura, t.descripcion,t.activo')->from('NomAutenticacion t')->execute();
        return $result;
    }

    static public function cargaridautenticacion($abrev) {
        $query = new Doctrine_Query ();
        $result = $query->select('t.idautenticacion')->from('NomAutenticacion t')->where('abreviatura = ?', array($abrev))->execute();
        return $result[0]['idautenticacion'];
    }

    static public function cargarautenticacion($abrev) {
        $query = new Doctrine_Query ();
        $result = $query->select('t.idautenticacion,t.activo')->from('NomAutenticacion t')->where('abreviatura = ?', array($abrev))->execute();
        return $result;
    }

    static public function buscarautenticacionporid($idautenticacion) {
        $query = new Doctrine_Query ();
        $result = $query->select('t.idautenticacion,t.activo')->from('NomAutenticacion t')->where('idautenticacion = ?', array($idautenticacion))->execute();
        return $result[0];
    }

}

