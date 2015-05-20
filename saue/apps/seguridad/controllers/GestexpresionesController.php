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

class GestexpresionesController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestexpresionesAction() {
        $this->render();
    }

    function insertarexpresionAction() {
        $model = new NomExpresionesModel();
        $expresion = new NomExpresiones();
        $expresion->denominacion = $this->_request->getPost('denominacion');
        $expresionaux = stripcslashes($this->_request->getPost('expresion'));
        utf8_decode($expresionaux);
        $expresion->expresion = $expresionaux;
        $expresion->descripcion = $this->_request->getPost('descripcion');
        $denominacion = $this->_request->getPost('denominacion');        
        if ($model->verificarExpresiones($expresion->denominacion))
            throw new ZendExt_Exception('SEG039');
        $model->insertarexpresion($expresion);
         echo"{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgAddExpres}";
    }

    function modificarexpresionAction() {
        $model = new NomExpresionesModel();
        $idexpresion = $this->_request->getPost('idexpresiones');
        $denominacion = $this->_request->getPost('denominacion');
        $expresion = stripcslashes($this->_request->getPost('expresion'));
        $descripcion = $this->_request->getPost('descripcion');
        $expresion_mod = Doctrine::getTable('NomExpresiones')->find($idexpresion);
        if ($expresion_mod->denominacion != $denominacion) {
            if ($model->verificarExpresiones($denominacion))
                throw new ZendExt_Exception('SEG039');
        }
        $expresion_mod->denominacion = $this->_request->getPost('denominacion');
        $expresion_mod->expresion = $this->_request->getPost('expresion');
        $expresion_mod->descripcion = $this->_request->getPost('descripcion');
        $model->modificarexpresion($expresion_mod);
        echo"{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgModExpres}";
    }  

    function eliminarexpresionAction() {
        $model = new NomExpresionesModel();        
        $arrayElim = json_decode(stripslashes($this->_request->getPost('expresionesElim')));
        $cantCmp = $model->CantCamposXIdExpresion($arrayElim);
        if ($cantCmp == 0) {
            $model->eliminarExpresiones($arrayElim);
           echo"{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgDelExpres}";
        } else {
            throw new ZendExt_Exception('SEG059');
        }
    }

    function cargarexpresionesAction() {
        $model = new NomExpresionesModel();
        $start = $this->_request->getPost("start");
        $limit = $this->_request->getPost("limit");
        $expresiones = $this->_request->getPost("denominacion");
        if ($expresiones) {
            $datosacc = $model->cargarexpresionBuscar($expresiones, $limit, $start);
            $canfilas = $model->obtenerexpresionBuscar($expresiones);
        } else {
            $datosacc = $model->cargarexpresion($limit, $start);
            $canfilas = $model->obtenerexpresion();
        }
        $result = array('cantidad_filas' => $canfilas, 'datos' => $datosacc->toArray());
        echo json_encode($result);
        return;
    }

}

?>  