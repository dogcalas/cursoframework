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

class GestfuncionalidadController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestfuncionalidadAction() {
        $this->render();
    }

    function insertarfuncionalidadAction() {
        $funcionalidad = new DatFuncionalidad();
        $model = new DatFuncionalidadModel();
        $funcionalidad->idsistema = $this->_request->getPost('idsistema');
        $funcionalidad->denominacion = $this->_request->getPost('text');
        $funcionalidad->referencia = $this->_request->getPost('referencia');
        $funcionalidad->descripcion = $this->_request->getPost('descripcion');
        $funcionalidad->iddominio = $this->global->Perfil->iddominio;
        $funcionalidad->index = ($this->_request->getPost('index')) ? $this->_request->getPost('index') : 0;
        $funcionalidad->icono = $this->_request->getPost('icono');
        $denominacion = $this->_request->getPost('text');
        $arrayFuncionalidades = $model->obtenerFuncionalidadesSistema($funcionalidad->idsistema);
        $denom = true;
        $referencia = true;
        foreach ($arrayFuncionalidades as $funcionalidades) {
            if ($funcionalidades['denominacion'] == $funcionalidad->denominacion) {
                $denom = false;
                break;
            }
        }
        foreach ($arrayFuncionalidades as $funcionalidades) {
            if ($funcionalidades['referencia'] == $funcionalidad->referencia) {
                $referencia = false;
                break;
            }
        }
        $ruta = $model->ExisteRuta($funcionalidad->referencia);
        if (!$ruta) {
            throw new ZendExt_Exception('SEG065');
        }
        if (!$denom) {
            throw new ZendExt_Exception('SEG030');
        }
        if (!$referencia) {
            throw new ZendExt_Exception('SEG066');
        } else {
            $idfuncionalidad = $model->insertarfuncionalidad($funcionalidad);
            $objFuncCompart = new DatFuncionalidadCompartimentacion();
            $objFuncCompart->iddominio = $this->global->Perfil->iddominio;
            $objFuncCompart->idfuncionalidad = $idfuncionalidad;
            $objFuncCompart->save();
            echo"{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfAdicionar}";
           ;
            
        }
    }

    function modificarfuncionalidadAction() {
        $idfuncionalidad = $this->_request->getPost('idfuncionalidad');
        $funcionalidad = new DatFuncionalidad();
        $model = new DatFuncionalidadModel();
        $funcionalidad = Doctrine::getTable('DatFuncionalidad')->find($idfuncionalidad);
        $funcionalidad->idsistema = $this->_request->getPost('idsistema');
        $funcionalidad->denominacion = $this->_request->getPost('text');
        $funcionalidad->referencia = $this->_request->getPost('referencia');
        $funcionalidad->descripcion = $this->_request->getPost('descripcion');
        $funcionalidad->index = ($this->_request->getPost('index')) ? $this->_request->getPost('index') : 0;
        $funcionalidad->icono = $this->_request->getPost('icono');
        $arrayFuncionalidades = $model->obtenerFuncionalidadesSistema($funcionalidad->idsistema);
        $ruta = $model->ExisteRuta($funcionalidad->referencia);
        if (!$ruta) {
            throw new ZendExt_Exception('SEG065');
        }
        $denom = true;
        $referencia = true;
        $auxden = 0;
        $auxreferencia = "";
        foreach ($arrayFuncionalidades as $aux2) {
            if ($aux2['idfuncionalidad'] == $idfuncionalidad) {
                $auxden = $aux2['denominacion'];
                $auxreferencia = $aux2['referencia'];
            }
        }
        if ($funcionalidad->denominacion != $auxden) {

            foreach ($arrayFuncionalidades as $funcionalidades) {
                if ($funcionalidades['denominacion'] == $funcionalidad->denominacion) {
                    $denom = false;
                    break;
                }
            }
        }

        if ($funcionalidad->referencia != $auxreferencia) {

            foreach ($arrayFuncionalidades as $funcionalidades) {
                if ($funcionalidades['referencia'] == $funcionalidad->referencia) {
                    $referencia = false;
                    break;
                }
            }
        }
    
        if (!$denom)
            throw new ZendExt_Exception('SEG030');
        if (!$referencia)
            throw new ZendExt_Exception('SEG066');
        else {
            $model->modificarfuncionalidad($funcionalidad);
           echo"{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfModificar}";
        }
   
    }
    function eliminarfuncionalidadAction() {
        $funcionalidad = new DatFuncionalidad();
        $funcionalidad = Doctrine::getTable('DatFuncionalidad')->find($this->_request->getPost('idfuncionalidad'));
        $model = new DatFuncionalidadModel();
        $model->eliminarfuncionalidad($funcionalidad);
       echo"{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfEliminar}";
    }

    function cargarsistemaAction() {        
        $sistemas = DatSistema::cargarsistema($this->_request->getPost('node'));        
        if (count($sistemas)) {
            $sistemaArr = array();            
            foreach ($sistemas as $valores => $valor) {                
                $sistemaArr[$valores]['id'] = $valor['id'];
                $sistemaArr[$valores]['text'] = $valor['text'];                
                $sistemaArr[$valores]['abreviatura'] = $valor['abreviatura'];
                $sistemaArr[$valores]['descripcion'] = $valor['descripcion'];
                $sistemaArr[$valores]['icono'] = $valor['icono'];
                $sistemaArr[$valores]['leaf'] = $valor['leaf'];
            }
            echo json_encode($sistemaArr);
            return;
        } else {
            echo json_encode($sistemas);
            return;
        }
    }

    function cargarfuncionalidadesAction() {        
        $idsistema = $this->_request->getPost('idsistema');
        $limit = $this->_request->getPost("limit");        
        $start = $this->_request->getPost("start");
        $denominacion = $this->_request->getPost("denominacion");
        $model=new DatFuncionalidadModel();
        if ($denominacion) {
            $datosfunc = $model->buscarfuncionalidades($idsistema, $denominacion, $limit, $start);
            $cantf = $model->obtenercantfuncdenominacion($idsistema, $denominacion);
        } else {
            $datosfunc = $model->buscarfuncionalidadesgrid($idsistema, $limit, $start);
            $cantf = $model->obtenercantfunc($idsistema);
        }
        if ($datosfunc) {
            $datos = $datosfunc->toArray();
            $result = array('cantidad_filas' => $cantf, 'datos' => $datos);
            echo json_encode($result);
            return;
        }
    }

}

?>
