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

class NomObjetospermisosModel extends ZendExt_Model {

    public function NomObjetospermisosModel() {
        parent::ZendExt_Model();
    }

    function insertarnomobjeto($objeto) {

        $objeto->save();
    }

    function modificarnomobjeto($instance) {
        $instance->save();
    }

    function eliminarnomobjeto($instance) {
        $instance->delete();
    }

}

?>
