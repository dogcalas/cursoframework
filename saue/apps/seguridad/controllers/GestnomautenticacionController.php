<?php

class GestnomautenticacionController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestnomautenticacionAction() {
        $this->render();
    }
//Funcionalidad que permite adicionar un tipo de autenticación
    function insertarautenticacionAction() {
        $tautenticacion = new NomAutenticacion();
        $modelautenticacion = new NomAutenticacionModel;
        $tautenticacion->denominacion = $this->_request->getPost('denominacion');
        $tautenticacion->abreviatura = $this->_request->getPost('abreviatura');
        $tautenticacion->descripcion = $this->_request->getPost('descripcion');
        if ($modelautenticacion->verificartAutenticacion($tautenticacion->denominacion, ''))
            throw new ZendExt_Exception('SEG063');
        if ($modelautenticacion->verificartAutenticacion('', $tautenticacion->abreviatura))
            throw new ZendExt_Exception('SEG064');
        $modelautenticacion->insertartipoAutenticacion($tautenticacion);
        echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgAddAut}";
    }
//Funcionalidad que permite modificar un tipo de autenticación existente
    function modificarautenticacionAction() {
        $tAutenticacion = new NomAutenticacion();
        $model = new NomAutenticacionModel();
        $denominacion = $this->_request->getPost('denominacion');
        $tAutenticacion = Doctrine::getTable('NomAutenticacion')->find($this->_request->getPost('idautenticacion'));
        $tAutenticacion->descripcion = $this->_request->getPost('descripcion');

        if ($tAutenticacion->denominacion != $denominacion) {
            if ($model->verificartAutenticacion($denominacion, '-1'))
                throw new ZendExt_Exception('SEG063');
        }
        if ($tAutenticacion->abreviatura != $abreviatura) {
            if ($model->verificartAutenticacion('-1', $abreviatura))
                throw new ZendExt_Exception('SEG064');
        }

        $tAutenticacion->denominacion = $denominacion;
        $modelautenticacion = new NomAutenticacionModel();
        $modelautenticacion->modificartipoAutenticacion($tAutenticacion);
        echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgModAut}";
    }
//Funcionalidad que permite eliminar un tipo de autenticación
    function eliminarnomautenticacionAction() {
        $modelautenticacion = new NomAutenticacionModel();
        $tautenticacion = Doctrine::getTable('NomAutenticacion')->find($this->_request->getPost('idautenticacion'));
        if ($modelautenticacion->estaenuso($tautenticacion->idautenticacion))
            echo"{'codMsg':3,'mensaje': perfil.etiquetas.lbMsgDelAutEr}";
        else {
            $modelautenticacion->eliminartipoAutenticacion($tautenticacion);
            echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgDelAut}";
        }
    }
//Funcionalidad que permite cargar las autenticaciones y mostrarlas
    function cargarnomautenticacionAction() {
        $start = $this->_request->getPost("start");
        $limit = $this->_request->getPost("limit");
        $datosautenticacion = NomAutenticacion::cargarnomautenticacion($limit, $start)->toArray();
        $canfilas = NomAutenticacion::obtenercantnomautenticacion();
        $datos = array();
        foreach ($datosautenticacion as $key => $value) {
            $datos[$key]['idautenticacion'] = $value['idautenticacion'];
            $datos[$key]['denominacion'] = $value['denominacion'];
            $datos[$key]['abreviatura'] = $value['abreviatura'];
            $datos[$key]['descripcion'] = $value['descripcion'];
            if ($value['activo'] == 1)
                $datos[$key]['activo'] = 'Si';
            else
                $datos[$key]['activo'] = 'No';
        }
        $result = array('cantidad_filas' => $canfilas, 'datos' => $datos);
        echo json_encode($result);
        return;
    }
//Funcionalidad que permite activar una autenticación que no esté activa
    function ActivarTAutAction() {
        $tAutenticacion = new NomAutenticacion();
        $modelautenticacion = new NomAutenticacionModel();
        $tAutenticacion = Doctrine::getTable('NomAutenticacion')->find($this->_request->getPost('activar'));
        $tAutenticacion->activo = 1;
        if ($tAutenticacion->abreviatura == 'rf' && !$modelautenticacion->ComprobarLibreria())
            echo"{'codMsg':3,'mensaje':perfil.etiquetas.lbMsgNolibrary}";
        else {            
            $modelautenticacion->modificartipoAutenticacion($tAutenticacion);
            echo"{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgActivados}";
        }
    }
//Funcionalidad que permite desactivar una autenticación que esté activa
    function desctivarTAutAction() {
        $tAutenticacion = new NomAutenticacion();
        $tAutenticacion = Doctrine::getTable('NomAutenticacion')->find($this->_request->getPost('activar'));
        $tAutenticacion->activo = 0;

        $modelautenticacion = new NomAutenticacionModel();
        $modelautenticacion->modificartipoAutenticacion($tAutenticacion);
        echo"{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgDesactivados}";
    }
}
    ?>
