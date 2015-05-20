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

class GestnomgestorController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestnomgestorAction() {
        $this->render();
    }

    function insertargestorAction() {
        $gestor = new DatGestor();
        $gestor->gestor = $this->_request->getPost('gestor');
        $gestor->puerto = $this->_request->getPost('puerto');
        $gestor->descripcion = $this->_request->getPost('descripcion');
        $model = new DatGestorModel();
        $model->insertarnomgestor($gestor);
        echo"{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgAddSatis}";
        
    }

    function modificaromgestorAction() {
        $idgestor = $this->_request->getPost('idgestor');
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $descripcion = $this->_request->getPost('descripcion');
        $gestor_mod = Doctrine::getTable('DatGestor')->find($idgestor);
        $gestor_mod->gestor = $gestor;
        $gestor_mod->puerto = $puerto;
        $gestor_mod->descripcion = $descripcion;
        $model = new DatGestorModel();
        $model->modificarnomgestor($gestor_mod);
        echo"{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgModSatis}";
           }

    function comprobargestoresAction() {
        $model = new DatGestorModel();
        $idgestor = $this->_request->getPost('idgestor');
        $cant =$model->obtenercantnomgestsist($idgestor);                        
        if ($cant > 0)
            throw new ZendExt_Exception('SEGNOMG1');
        else {            
            $gestor = Doctrine::getTable('DatGestor')->find($idgestor);
            $model->eliminarnomgestor($gestor);
            echo"{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgDelSatis}";
            
        }
    }
    function cargarnomgestoresAction() {
        $model = new DatGestorModel();
        $start = $this->_request->getPost("start");
        $limit = $this->_request->getPost("limit");
        $gestor = $this->_request->getPost("gestor");
        if ($gestor) {
            $datosgest =$model->buscarnomgestores($gestor, $limit, $start);                     
            $canfilas = $model->obtenercantnomgestoresbuscados($gestor);                    
        } else {
            $datosgest = $model->cargarnomgestores($limit, $start);                    
            $canfilas = $model->obtenercantnomgestores();                   
        }
        $datos = $datosgest->toArray();
        $result = array('cantidad_filas' => $canfilas, 'datos' => $datos);
        echo json_encode($result);
        return;
    }

}

?>