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

class NomObjetospermisos extends BaseNomObjetospermisos {

    public function setUp() {
        parent :: setUp();
    }

    static public function comprobarobjeto($objeto) {
        $query = Doctrine_Query::create();
        $cant = $query->select('count(o.id) cant')->from('NomObjetospermisos o')->where("o.nombreobjeto = ?", array($objeto))->execute()->toArray();
        return $cant[0]['cant'];
    }

    static public function cargarnomobjetos($limit, $start) {

        $query = Doctrine_Query::create();

        $objeto = $query->select('o.id, o.nombreobjeto, o.descripcion')->from('NomObjetospermisos o')->limit($limit)->offset($start)->execute();

        return $objeto;
    }
    //Posiblemente no utilizado
    static function obtenercantnomobjetos() {

        $query = Doctrine_Query::create();

        $cantFndes = $query->select('count(o.id) cant')->from('NomObjetospermisos o')->execute()->toArray();
        
        return $cantFndes[0]['cant'];
    }

    static public function buscarnomobjetos($objeto, $limit, $start) {
        $query = Doctrine_Query::create();
        $datos = $query->select('id, nombreobjeto, descripcion,idobj')->from('NomObjetospermisos')->where("nombreobjeto like '%$objeto%'")->limit($limit)->offset($start)->execute();
        return $datos;
    }

    static public function obtenercantnomobjetosbuscados($objeto) {
        $query = Doctrine_Query::create();
        $cant = $query->select('count(o.id) cant')->from('NomObjetospermisos')->where("nombreobjeto like '$objeto%'")->execute()->toArray();
        return $cant[0]['cant'];
    }

    static public function obtenerIdCriterio($criterio) {
        $query = Doctrine_Query::create();
        $cant = $query->select('idobj')->from('NomObjetospermisos')->where("nombreobjeto=?",$criterio)->execute()->toArray();
        return $cant[0]['idobj'];
    }
 
}
