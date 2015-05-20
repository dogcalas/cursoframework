<?php

/*
 * Componente para gestionar las acciones y los reportes asociados a estas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @autor Oiner Gomez Baryolo
 * @author Noel Jesus Rivero Pino    
 * @version 1.0-0
 */

class GestaccrepController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestaccrepAction() {
        $this->render();
    }

    function cargarsistfuncAction() {
        $idnodo = $this->_request->getPost('node');
        $idsistema = $this->_request->getPost('idsistema');
        if ($idnodo == 0)
            $sistemas = DatSistema::cargarsistema($idnodo);
        else
            $sistemas = DatSistema::cargarsistema($idsistema);
        $contador = 0;
        $sistemafunArr = array();
        if ($sistemas->count()) {
            foreach ($sistemas as $valores => $valor) {
                $sistemafunArr[$contador]['id'] = $valor->id . '_' . $idnodo;
                $sistemafunArr[$contador]['idsistema'] = $valor->id;
                $sistemafunArr[$contador]['text'] = $valor->text;
                $contador++;
            }
        }
        $funcionalidad = DatFuncionalidad::cargarfuncionalidades($idsistema, 0, 0);
        if ($funcionalidad->getData() != NULL) {
            foreach ($funcionalidad as $valores => $valor) {
                $sistemafunArr[$contador]['id'] = $valor->id . '_' . $idnodo;
                $sistemafunArr[$contador]['idfuncionalidad'] = $valor->id;
                $sistemafunArr[$contador]['text'] = $valor->text;
                $sistemafunArr[$contador]['leaf'] = true;
                $contador++;
            }
        }
        echo json_encode($sistemafunArr);
        return;
    }

    function cargargridaccionesAction() {
        $idfuncionalidad = $this->_request->getPost('idfuncionalidad');
        $denominacion = $this->_request->getPost('denominacion');
        $start = $this->_request->getPost("start");
        $limit = $this->_request->getPost("limit");
        if ($denominacion) {
            $datosacc = DatAccion::buscaraccion($idfuncionalidad, $denominacion, $limit, $start);
            $canfilas = DatAccion::obtenercantaccionbuscadas($idfuncionalidad, $denominacion);
        } else {
            $datosacc = DatAccion::cargaraccion($idfuncionalidad, $limit, $start);
            $canfilas = DatAccion::obtenercantaccion($idfuncionalidad);
        }
        $datos = array();
        if (count($datosacc))
            $datos = $datosacc->toArray();
        $result = array('cantidad_filas' => $canfilas, 'datos' => $datos);
        echo json_encode($result);
        return;
    }

    function cargarreportesAction() {
        $direccion = 'Location: http://' . $_SERVER['HTTP_HOST'] . '/' . 'report_generator.php/api/getReportsCatalog';
        echo header($direccion);
    }

    function reportesasociadosarepAction() {
        $idaccion = $this->_request->getPost("idaccion");
        $acciones = DatAccionDatReporte::cargaraccionesasociadasrep($idaccion);
        $datos = array('datos' => $acciones);
        echo json_encode($datos);
        return;
    }

    function buscarparametrosAction() {
        $idreporte = $this->_request->getPost("idreporte");
        $direccion = 'Location: http://' . $_SERVER['HTTP_HOST'] . '/' . 'report_generator.php/api/getReportMeta?id=' . $idreporte;
        echo header($direccion);
    }

    function relacionaraccrepAction() {
        $arrayrepacc = array();
        $arrayobj = array();
        $idaccion = $this->_request->getPost("idaccion");
        $arrayrepaccadd = json_decode(stripslashes($this->_request->getPost('reportesAdd')));
        $arrayrepaccelim = json_decode(stripslashes($this->_request->getPost('reportesElim')));
        if (count($arrayrepaccadd)) {
            foreach ($arrayrepaccadd as $valoradd) {
                $arrayRepoAccAdd = new DatAccionDatReporte();
                $arrayRepoAccAdd->idaccion = $idaccion;
                $arrayRepoAccAdd->idreporte = $valoradd[0];
                $arrayRepoAccAdd->denominacion = $valoradd[1];
                $arrayobjAdd[] = $arrayRepoAccAdd;
            }
        }
        if (count($arrayrepaccelim))
            DatAccionDatReporte::eliminar($idaccion, $arrayrepaccelim);
        if (count($arrayrepaccadd)) {
            $model = new DatAccionDatReporteModel();
            if ($model->insertaraccionreporte($arrayobjAdd))
                echo "{'bien':1}";
        }
        else
            echo "{'bien':2}";
    }

}

?>