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

class NomIdiomaModel extends ZendExt_Model {

    public function NomIdiomaModel() {
        parent::ZendExt_Model();
    }

    function insertaridioma($idioma) {
        $idioma->save();
    }

    function modificaridioma($idioma) {
        $idioma->save();
    }

    function eliminaridioma($idioma) {
        $idioma->delete();
    }

    function comprobaridioma($denominacion, $abreviatura) {
        $datosservidor = NomIdioma::comprobaridioma($denominacion, $abreviatura);
        if ($datosservidor)
            return 1;
        else
            return 0;      
    }

    function cargarnomidioma($limit, $start) {
        $nomidioma = NomIdioma::cargarnomidioma($limit, $start);
        return $nomidioma;
    }

    function obtenercantnomidioma() {
        $canfilas = NomIdioma::obtenercantnomidioma();
        return $canfilas;
    }

    function cargarcomboidioma() {
        $comboidioma = NomIdioma::cargarcomboidioma();
        return $comboidioma;
    }
     function IsIdiomaInUso($idioma) {
        $id = $idioma->ididioma;
        $resu = Doctrine::getTable('SegUsuario')->findbyDql('ididioma=?', array($id));
        if (count($resu) > 0) {
            return true;
        }
        return false;
    }

}