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

class NomDesktop extends BaseNomDesktop {

    public function setUp() {
        parent::setUp();
        $this->hasMany('SegUsuario', array('local' => 'iddesktop', 'foreign' => 'iddesktop'));
    }

    static public function cargarnomdesktop($limit, $start) {
        $query = new Doctrine_Query ();
        $result = $query->select('d.iddesktop,d.denominacion,d.abreviatura, d.descripcion')->from('NomDesktop d')
                ->limit($limit)
                ->offset($start)
                ->execute();
        return $result;
    }

    static public function obtenercantnomdesktop() {
        $query = new Doctrine_Query ();
        $cant = $query->from('NomDesktop')->count();
        return $cant;
    }

    static public function cargarcombodesktop() {
        $query = new Doctrine_Query ();
        $result = $query->select('iddesktop,denominacion')->from('NomDesktop')->execute();
        return $result;
    }

    static public function verificarnombredesktop($denominacion, $abreviatura) {
        $query = new Doctrine_Query ();
        $var = $query->from('NomDesktop n')
                        ->where("n.denominacion = ? or n.abreviatura = ? ", array($denominacion, $abreviatura))->count();
        return $var;
    }

    static public function obtenerAccionesDesktop($iddesktop) {
        $query = Doctrine_Query::create();
        $datos = $query->select('d.denominacion, d.abreviatura')->from('NomDesktop d')->where("d.iddesktop = ?", $iddesktop)->setHydrationMode(Doctrine :: HYDRATE_ARRAY)->execute();
        return $datos;
    }

}