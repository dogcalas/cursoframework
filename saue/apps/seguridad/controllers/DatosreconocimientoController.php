<?php

class DatosreconocimientoController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function datosreconocimientoAction() {
        $this->render();
    }

    function insertardatosAction() {
        $datos = new SegDatosReconocimiento();
        $datos->metododistancia = $this->_request->getPost('metododistancia');
        $datos->metodoknn = $this->_request->getPost('metodoknn');
        $metodo = $this->_request->getPost('metodorec');
        $datos->metodorec = $metodo;
        if ($metodo != 'PCA') {
            $nivel = $this->_request->getPost('ndescomposicion');
            $nivel = explode(' ', $nivel);
            $datos->ndescomposicion = 3;
        }
        $model = new SegDatosReconocimientoModel();
        if ($model->insertardatos($datos))
            $this->showMessage('Los datos fueron insertados satisfactoriamente.');
    }

    function modificardatosReconocimientoAction() {
        $datos = new SegDatosReconocimiento();
        $datos = Doctrine::getTable('SegDatosReconocimiento')->find($this->_request->getPost('iddatosreconocimiento'));
        $datos->metododistancia = $this->_request->getPost('metododistancia');
        $datos->metodoknn = $this->_request->getPost('metodoknn');
        $metodo = $this->_request->getPost('metodorec');
        $datos->metodorec = $metodo;
        $datos->ndescomposicion = 3;        
        $model = new SegDatosReconocimientoModel();
        if ($model->modificardatos($datos))
        echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgFunModificarMsgI}");          
    }

    function cargardatosAction() {
        $start = $this->_request->getPost("start");
        $limit = $this->_request->getPost("limit");
        $datosrec = SegDatosReconocimiento::cargardatoreconocimiento($limit, $start);
        $canfilas = SegDatosReconocimiento::obtenerdatos();
        $datos = $datosrec->toArray();
        if (count($datos))
            $datos[0]['ndescomposicion'] = 'Nivel ' . $datos[0]['ndescomposicion'];
        $result = array('cantidad_filas' => $canfilas, 'datos' => $datos);
        echo json_encode($result);
        return;
    }

}

?>
