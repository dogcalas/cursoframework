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

class GestnomidiomaController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestnomidiomaAction() {
        $this->render();
    }

    function insertarnomidiomaAction() {
        $idioma = new NomIdioma();
        $modelidioma = new NomIdiomaModel();
        $idioma->denominacion = $this->_request->getPost('denominacion');
        $idioma->abreviatura = $this->_request->getPost('abreviatura');
        if ($modelidioma->comprobaridioma($idioma->denominacion, ''))
            throw new ZendExt_Exception('SEG014');
        if ($modelidioma->comprobaridioma('', $idioma->abreviatura))
            throw new ZendExt_Exception('SEG015');        
        $modelidioma->insertaridioma($idioma);
        echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgAddLang}";
    }

    function modificarnomidiomaAction() {
        $modelidioma = new NomIdiomaModel();
        $ididioma = $this->_request->getPost('ididioma');
        $denominacion = $this->_request->getPost('denominacion');
        $abreviatura = $this->_request->getPost('abreviatura');
        $idioma_mod = Doctrine::getTable('NomIdioma')->find($ididioma);
        if ($idioma_mod->denominacion != $denominacion) {
            if ($modelidioma->comprobaridioma($denominacion, ''))
                throw new ZendExt_Exception('SEG014');
        }
        if ($idioma_mod->abreviatura != $abreviatura) {
            if ($modelidioma->comprobaridioma('', $abreviatura))
                throw new ZendExt_Exception('SEG015');
        }
        $idioma_mod->denominacion = $denominacion;
        $idioma_mod->abreviatura = $abreviatura;        
        $modelidioma->modificaridioma($idioma_mod);
        echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgModLang}";
    }
 
    function eliminarnomidiomaAction() {
        $modelidioma = new NomIdiomaModel();
        $idioma = Doctrine::getTable('NomIdioma')->find($this->_request->getPost('ididioma'));
        if (!$modelidioma->IsIdiomaInUso($idioma)) {
            $modelidioma->eliminaridioma($idioma);
            echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgDelLang}";
        } else {
            echo"{'codMsg':3,'mensaje':perfil.etiquetas.lbMsgDelLangErr}";
        }
    }   

    function cargarnomidiomaAction() {
        $start = $this->_request->getPost("start");
        $limit = $this->_request->getPost("limit");
        $modelidioma = new NomIdiomaModel();
        if ($limit > 0) {
            $datosidioma = $modelidioma->cargarnomidioma($limit, $start);
            $canfilas = $modelidioma->obtenercantnomidioma();
            $datos = $datosidioma->toArray();
            $result = array('cantidad_filas' => $canfilas, 'datos' => $datos);
        } else {
            $comboidioma = $modelidioma->cargarcomboidioma();
            $result = $comboidioma->toArray();
        }
        echo json_encode($result);
        return;
    }

}

?>
