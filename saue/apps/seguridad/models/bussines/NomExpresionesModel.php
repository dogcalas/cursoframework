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

class NomExpresionesModel extends ZendExt_Model {

    public function NomExpresionesModel() {
        parent::ZendExt_Model();
    }

    function insertarexpresion($expresion) {
        $expresion->save();
    }

    function modificarexpresion($expresion) {
        $expresion->save();
    }

    function verificarExpresiones($denominacion) {
        $verificarExpre = NomExpresiones::verificarExpresiones($denominacion);
        if ($verificarExpre)
            return 1;
        else
            return 0;
    }

    function CantCamposXIdExpresion($arrayElim) {
        $cantCmp = NomCampo::CantCamposXIdExpresion($arrayElim);
        return $cantCmp;
    }

    function eliminarExpresiones($arrayElim) {
        NomExpresiones::eliminarExpresiones($arrayElim);
    }

    function cargarexpresionBuscar($expresiones, $limit, $start) {
        $datosacc = NomExpresiones::cargarexpresionBuscar($expresiones, $limit, $start);
        return $datosacc;
    }

    function obtenerexpresionBuscar($expresiones) {
        $canfilas = NomExpresiones::obtenerexpresionBuscar($expresiones);
        return $canfilas;
    }

    function cargarexpresion($limit, $start) {
        $datosacc = NomExpresiones::cargarexpresion($limit, $start);
        return $datosacc;
    }

    function obtenerexpresion() {
        $canfilas = NomExpresiones::obtenerexpresion();
        return $canfilas;
    }

}

?>