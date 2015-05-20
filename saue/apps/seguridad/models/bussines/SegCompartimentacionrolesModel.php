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

class SegCompartimentacionrolesModel extends ZendExt_Model {

    public function SegCompartimentacionrolesModel() {
        parent::ZendExt_Model();
    }

    public function obtenerrolBuscado($filtroDominio, $denominacion, $limit, $start) {
        $result = SegRol::obtenerrolBuscado($filtroDominio, $denominacion, $limit, $start);
        return $result;
    }
    public function cantrolBuscados($filtroDominio,$denominacion){ 
    $cantrol = SegRol::cantrolBuscados($filtroDominio,$denominacion);
    return $cantrol; 
    }
    
    public function obtenerrol($filtroDominio,$limit,$start){ 
            $result = SegRol::obtenerrol($filtroDominio,$limit,$start);
            return $result;
    }
    public function cantrol($filtroDominio) {
        $cantrol = SegRol::cantrol($filtroDominio);
        return $cantrol; 
    }
    
    public function cargardominioRoles($idrol){        
        $dominiosRoles = SegCompartimentacionroles::cargardominioRoles($idrol);
        return $dominiosRoles; 
    }
    

    public function insertarRolesDominio($arrayIns, $arrayElim) {
        if (count($arrayElim))
            foreach ($arrayElim as $objElim) {
                SegCompartimentacionroles::eliminarRolesDominio($objElim->idrol, $objElim->iddominio);
            }
        if (count($arrayIns))
            foreach ($arrayIns as $obj) {
                $obj->save();
            }
        return true;
    }

}

?>