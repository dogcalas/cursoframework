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

class GestgestorController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestgestorAction() {
        $this->render();
    }

    function insertargestorservidorAction() {
        $gestorservidor = new DatGestorDatServidorbd();
        $gestorservidor->idgestor = $this->_request->getPost('idgestor');
        $gestorservidor->idservidor = $this->_request->getPost('idservidor');
        $gestorservidor->codigosid = $this->_request->getPost('sid');
        $model = new DatGestorDatServidorbdModel();
        $model->insertargestorervidor($gestorservidor);
        echo"{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgAddGest}";
    }

    function modificargestorAction() {
        $idgestor = $this->_request->getPost('idgestor');
        $nomgestor = $this->_request->getPost('gestor');
        $puertogestor = $this->_request->getPost('puerto');
        $descgestor = $this->_request->getPost('descripcion');
        $model = new DatGestorModel();
        $cantgestsist = $model->obtenercantgestsist($idgestor);
        $cantgestbd = $model->obtenercantgestbd($idgestor);
        if (($cantgestsist == 0) && ($cantgestbd == 0)) {
            $gestor = new DatGestor();
            $gestor = Doctrine::getTable('DatGestor')->find($idgestor);
            $gestor->gestor = $nomgestor;
            $gestor->puerto = $puertogestor;
            $gestor->descripcion = $descgestor;
            $model->modificargestor($gestor);
            echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgModGest}";
           
        }
    }

    function cargargestoresAction() {
        $idservidor = $this->_request->getPost('idservidor');
        $start = $this->_request->getPost("start");
        $limit = $this->_request->getPost("limit");
        $model = new DatGestorModel();
        $datosgest = $model->cargargestores($idservidor, $limit, $start);
        $canfilas = $model->obtenercantgest($idservidor);
        $datos = $datosgest->toArray();
        $result = array('cantidad_filas' => $canfilas, 'datos' => $datos);

        echo json_encode($result);
        return;
    }

    function cargarservidoresAction() {
        $idnodo = $this->_request->getPost('node');
        if ($idnodo == 0) {
            $model = new DatGestorModel();
            $servidores = $model->cargarservidores();
            if ($servidores->count()) {
                foreach ($servidores as $valores => $valor) {
                    $servidoresArr[$valores]['id'] = $valor->id;
                    $servidoresArr[$valores]['text'] = $valor->text;
                    $servidoresArr[$valores]['leaf'] = true;
                }
                echo json_encode($servidoresArr);
                return;
            } else {
                $serv = $servidores->toArray();
                echo json_encode($serv);
                return;
            }
        }
    }
    function comprobargestoresAction() {
        $idgestor = $this->_request->getPost('idgestor');
        $idservidor = $this->_request->getPost('idservidor');
        $model = new DatGestorModel();
        $comprobar = $model->obtenercantgestsistema($idservidor, $idgestor);
        if ($comprobar > 0)
            throw new ZendExt_Exception('SEG011');
        if ($model->eliminargestorservidor($idservidor, $idgestor))
            echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgDelGest}";
    }

    function cargarcombogestoresAction() {
        $idservidor = $this->_request->getPost('idservidor');
        $model = new DatGestorModel();
        $gestores = $model->cargarcombogestores($idservidor);
        $arregloGestoresPuerto = array();
        $arregloGestores = $gestores->toArray(true);
        foreach ($arregloGestores as $key => $value) {
            $arregloGestoresPuerto[$key]['idgestor'] = $value['idgestor'];
            $arregloGestoresPuerto[$key]['gestorpuerto'] = $value['gestor'] . "-" . $value['puerto'];
            $arregloGestoresPuerto[$key]['gestor'] = $value['gestor'];
            $arregloGestoresPuerto[$key]['puerto'] = $value['puerto'];
        }
        echo json_encode($arregloGestoresPuerto);
        return;
    }

}

?>