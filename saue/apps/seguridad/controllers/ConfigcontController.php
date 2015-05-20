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

class ConfigcontController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function configcontAction() {
        $this->render();
    }

    function modificarclaveAction() {        
        $clave = new SegRestricclaveacceso();
        $clave = Doctrine::getTable('SegRestricclaveacceso')->find($this->_request->getPost('idrestricclaveacceso'));
        $clave->diascaducidad = $this->_request->getPost('diascaducidad');
        $clave->minimocaracteres = $this->_request->getPost('minimocaracteres');
        $clave->canthistorico = $this->_request->getPost('canthistorico');
        if ($clave->minimocaracteres < 8)
            throw new ZendExt_Exception('SEGPASS02');
        $clave->numerica = '1';        
        $clave->alfabetica = '1';       
        if ($this->_request->getPost('signos') == "on")
            $clave->signos = '1';
        else {
            $clave->signos = '0';
        }        
        $model = new SegRestricclaveaccesoModel();
        if ($model->modificarclave($clave))            
        echo("{'codMsg':1,mensaje:perfil.etiquetas.lbMsgInfModificar}");
    }

    function cargarclavesAction() {
        $start = $this->_request->getPost("start");
        $limit = $this->_request->getPost("limit");
        $model = new SegRestricclaveaccesoModel();
        $datosacc = $model->cargarclave($limit, $start);
        $canfilas = $model->obtenerclave();
        $datos = $datosacc->toArray();
        $result = array('cantidad_filas' => $canfilas, 'datos' => $datos);
        echo json_encode($result);
        return;
    }

}

?>