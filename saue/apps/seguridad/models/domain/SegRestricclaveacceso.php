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

class SegRestricclaveacceso extends BaseSegRestricclaveacceso {

    public function setUp() {
        parent::setUp();
    }

    static public function cargarclave($limit, $start) {
        $query = Doctrine_Query::create();
        $fndes = $query->select('a.idrestricclaveacceso,a.minimocaracteres,a.numerica,a.signos,a.alfabetica,a.diascaducidad,a.canthistorico')->from('SegRestricclaveacceso a')->orderby('idrestricclaveacceso')->limit($limit)->offset($start)->execute();
        return $fndes;
    }

    static public function cargarrestriccion() {
        $query = Doctrine_Query::create();
        $fndes = $query->select('a.idrestricclaveacceso,a.minimocaracteres,a.numerica,a.signos,a.alfabetica,a.diascaducidad,a.canthistorico')
                ->from('SegRestricclaveacceso a')
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();
        return $fndes;
    }

    static function obtenerclave() {
        $query = Doctrine_Query::create();
        $cantFndes = $query->select('COUNT(a.idrestricclaveacceso) cant')->from('SegRestricclaveacceso a')
                ->execute();
        return $cantFndes[0]['cant'];
    }

}