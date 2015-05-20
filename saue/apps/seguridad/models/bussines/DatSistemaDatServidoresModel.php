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

class DatSistemaDatServidoresModel extends ZendExt_Model {

    public function DatSistemaDatServidoresModel() {
        parent::ZendExt_Model();
    }

    public function registrarConexiones($arrayObjServidores) {

        if (count($arrayObjServidores) > 0) {
            foreach ($arrayObjServidores as $servidor)
                $servidor->save();
        }
    }
    
    public function ExisteConexion($idsistema, $sistemaservidores){
        $sistemaservidores=new DatSistemaDatServidores();
        $sistemaservidores=  DatSistemaDatServidores::getSistemaServidores($idsistema);
        return $sistemaservidores;        
    }

}

?>