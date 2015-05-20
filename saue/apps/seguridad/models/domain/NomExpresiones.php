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

class NomExpresiones extends BaseNomExpresiones {

    public function setUp() {
        parent::setUp();
        $this->hasMany('NomCampo', array('local' => 'idexpresiones', 'foreign' => 'idexpresiones'));
    }

    static public function cargarexpresion($limit, $start) {
        $query = Doctrine_Query::create();
        $fndes = $query->from('NomExpresiones a')->orderby('a.idexpresiones')->limit($limit)->offset($start)->execute();
        return $fndes;
    }

    static function obtenerexpresion() {
        $query = Doctrine_Query::create();
        $cantFndes = $query->from('NomExpresiones')->count();
        return $cantFndes;
    }

    static function obtenerexpresionRegular() {
        $query = Doctrine_Query::create();
        $datos = $query->select('idexpresiones')->from('NomExpresiones')->execute();
        return $datos;
    }

    static function eliminarExpresiones($arrayElim) {
        $query = Doctrine_Query::create();
        $query->delete()->from('NomExpresiones')->whereIn('idexpresiones', $arrayElim)->execute();
    }

    static public function cargarexpresionBuscar($expresiones, $limit, $start) {
        $query = Doctrine_Query::create();
        $datos = $query->select('e.idexpresiones, e.denominacion,e.descripcion')->from('NomExpresiones e')->where("denominacion like '%$expresiones%'")->limit($limit)->offset($start)->execute();
        return $datos;
    }

    static function obtenerexpresionBuscar($expresiones) {
        $query = Doctrine_Query::create();
        $cantFndes = $query->from('NomExpresiones')->where("denominacion like '$expresiones%'")->count();
        return $cantFndes;
    }

    public static function verificarExpresiones($denominacion) {
        $query = Doctrine_Query::create();
        $var = $query->from('NomExpresiones e')
                        ->where("e.denominacion = ?", $denominacion)->count();
        return $var;
    }

}