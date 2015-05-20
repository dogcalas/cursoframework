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

class DatGestorModel extends ZendExt_Model {

    public function DatGestorModel() {
        parent::ZendExt_Model();
    }

    function insertarnomgestor($gestor) {
        $gestor->save();
    }

    function modificarnomgestor($instance) {
        $instance->save();
    }

    function eliminarnomgestor($instance) {
        $instance->delete();
    }

    function obtenercantgestsist($idgestor) {
        $cantgestsist = DatGestor::obtenercantgestsist($idgestor);
        return $cantgestsist;
    }

    function obtenercantgestbd($idgestor) {
        $cantgestbd = DatGestor::obtenercantgestbd($idgestor);
        return $cantgestbd;
    }

    function cargargestores($idservidor, $limit, $start) {
        $datosgest = DatGestor::cargargestores($idservidor, $limit, $start);
        return $datosgest;
    }

    function obtenercantgest($idservidor) {
        $canfilas = DatGestor::obtenercantgest($idservidor);
        return $canfilas;
    }

    function cargarservidores() {
        $servidores = DatServidor::cargarservidores(0, 0);
        return $servidores;
    }

    function obtenercantgestsistema($idservidor, $idgestor) {
        $comprobar = DatGestor::obtenercantgestsistema($idservidor, $idgestor);
        return $comprobar;
    }

    function eliminargestorservidor($idservidor, $idgestor) {
        $gestserv = DatGestorDatServidorbd::eliminargestorservidor($idservidor, $idgestor);
        return $gestserv;
    }

    function cargarcombogestores($idservidor) {
        $gestores = DatGestor::cargarcombogestores($idservidor);
        return $gestores;
    }
    function obtenercantnomgestsist($idgestor){
         $cant = DatGestor::obtenercantnomgestsist($idgestor); 
         return $cant; 
    }
    function buscarnomgestores($gestor, $limit, $start){
       $datosgest = DatGestor::buscarnomgestores($gestor, $limit, $start);
       return $datosgest; 
    }
     function obtenercantnomgestoresbuscados($gestor){
       $canfilas = DatGestor::obtenercantnomgestoresbuscados($gestor);
       return $canfilas; 
    }
    
      function cargarnomgestores($limit, $start){
       $datosgest = DatGestor::cargarnomgestores($limit, $start);
       return $datosgest; 
    }
     function obtenercantnomgestores(){
         $canfilas = DatGestor::obtenercantnomgestores();
       return $canfilas; 
    }
    
          
            

}

?>