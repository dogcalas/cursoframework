<?php

class NormalizarimgController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function normalizarimgAction() {
        $this->render();
    }

    function insertarDatosAction() {
        $datos = new SegDatosImagenes();
        $datos->brillo = $this->_request->getPost('brillo');
        $datos->contraste = $this->_request->getPost('contraste');
        $datos->cantimg = $this->_request->getPost('cantimg');
        $datos->ancho = $this->_request->getPost('ancho');
        $datos->alto = $this->_request->getPost('alto');
        $datos->forma = $this->_request->getPost('forma');
        $datos->formato = $this->_request->getPost('formato');
        $model = new SegDatosImagenesModel();
        if ($model->insertardatos($datos))
            $this->showMessage('Las datos fueron insertados satisfactoriamente.');
    }

    function modificardatosImagenAction() {
        $datos = new SegDatosImagenes();
        $datos = Doctrine::getTable('SegDatosImagenes')->find($this->_request->getPost('iddatosimg'));
        $model = new SegDatosImagenesModel();
        $dir_img = str_replace('//', '/', dirname($_SERVER['DOCUMENT_ROOT']) . '/' . '/web/seguridad/views/images/reconocimientoFacial/');
        $apl = $this->_request->getPost('apl');

        if ($apl) {

            if ($this->ComprobarLibreria()) {
                $rec = new Rec();

                $datos->brillo = $this->_request->getPost('brillo');
                $datos->contraste = $this->_request->getPost('contraste');
                $datos->forma = $this->_request->getPost('forma');
                $model->modificardatos($datos);

                if ($datos->forma == 'Elipse') {

                    $rec->ellipse($dir_img . 'prueba1.png', $dir_img . 'prueba.png', 1, $datos->brillo, $datos->contraste);
                    echo "{'bien':1}";
                } else {
                    $rec->ellipse($dir_img . 'prueba1.png', $dir_img . 'prueba.png', 0, $datos->brillo, $datos->contraste);
                    echo "{'bien':1}";
                }
            } else {
                echo "{'bien':3}";
            }
        } else {

            $datos->brillo = $this->_request->getPost('brillo');
            $datos->contraste = $this->_request->getPost('contraste');
            $datos->cantimg = $this->_request->getPost('cantimg');
            $datos->ancho = $this->_request->getPost('ancho');
            $datos->alto = $this->_request->getPost('alto');
            $datos->forma = $this->_request->getPost('forma');
            $datos->formato = $this->_request->getPost('formato');

            if ($this->ComprobarLibreria()) {
                $rec = new Rec();
                $rec->histograma($dir_img . 'prueba1.png', $dir_img . 'prueba.png', $datos->brillo, $datos->contraste);
                if ($datos->forma == 'Elipse') {
                    $rec->ellipse($dir_img . 'prueba1.png', $dir_img . 'prueba.png', 1, $datos->brillo, $datos->contraste);
                }
                else
                    $rec->ellipse($dir_img . 'prueba1.png', $dir_img . 'prueba.png', 0, $datos->brillo, $datos->contraste);
            }


            if ($model->modificardatos($datos))
            echo("{'codMsg':1,mensaje:perfil.etiquetas.lbMsgInfModificar}");              
        }
    }

    function cargardatosAction() {
        $start = $this->_request->getPost("start");
        $limit = $this->_request->getPost("limit");
        $datosimg = SegDatosImagenes::cargardatosimg($limit, $start);
        $canfilas = SegDatosImagenes::obtenerdatos();
        $datos = $datosimg->toArray();
        $result = array('cantidad_filas' => $canfilas, 'datos' => $datos);
        echo json_encode($result);
        return;
    }

    function cargardirAction() {
        $dir_img = '../../views/images/reconocimientoFacial/';
        echo json_encode($dir_img . 'prueba.png' . '?' . rand());
    }

    function ComprobarLibreria() {
        if (!extension_loaded('reconocimiento')) {

            return 0;
        }
        else
            return 1;
    }

}

?>
