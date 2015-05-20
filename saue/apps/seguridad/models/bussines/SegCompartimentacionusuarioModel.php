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

class SegCompartimentacionusuarioModel extends ZendExt_Model {

    public function SegCompartimentacionusuarioModel() {
        parent::ZendExt_Model();
    }

    public function cargarusuario($filtroDominio, $nombreusuario, $limit, $start) {
        $datosusuario = SegUsuario::cargarusuario($filtroDominio, $nombreusuario, $limit, $start);
        return $datosusuario;
    }
    public function obtenercantusuarios($filtroDominio, $nombreusuario){
        $cantf = SegUsuario::obtenercantusuarios($filtroDominio, $nombreusuario);
        return $cantf; 
    }
    
    public function cargardominioUsuario($idusuario){
        $dominiosUser = SegCompartimentacionusuario::cargardominioUsuario($idusuario);
        return $dominiosUser;        
    }

    public function insertarUsuarioDominio($arrayIns, $arrayElim) {
        if (count($arrayElim))
            foreach ($arrayElim as $objElim) {
                SegCompartimentacionusuario::eliminarUsuarioDominio($objElim->idusuario, $objElim->iddominio);
            }
        if (count($arrayIns))
            foreach ($arrayIns as $obj) {
                $obj->save();
            }
        return true;
    }

}

?>