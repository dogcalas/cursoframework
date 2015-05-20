<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Yoel Hernández Mendoza
 * @version 3.0-0
 */
    class GestprocdinamController extends ZendExt_Controller_Secure
    {
        function init ()
        {
            parent::init();
        }
                
        function gestprocdinamAction()
        {
            $this->render();
        }
        
        function insertarprocesoAction()
        {
            $proceso = new DatProcesos();
            $proceso->denominacion = $this->_request->getPost('denominacion');
            $proceso->descripcion = $this->_request->getPost('descripcion');
            $proceso->save();
            echo"{'codMsg':1,'mensaje': 'Proceso(s) insertado(s) satisfactoriamente.'}";

        }

        function modificarprocesoAction()
        {

            $idproceso = $this->_request->getPost('idproceso');
            $denominacion = $this->_request->getPost('denominacion');
            $descripcion = $this->_request->getPost('descripcion');
            $proceso1 = new DatProcesos();
            $proceso = new DatProcesos();
            $proceso->obtenerProcesos($idproceso);
            $proceso->denominacion = $denominacion;
            $proceso->idproceso = $idproceso;
            $proceso->descripcion = $descripcion;
            $proceso1->ModificarProcesos($proceso);
            echo"{'codMsg':1,'mensaje': 'Proceso(s) modificado(s) satisfactoriamente.'}";
            
           
        }

        function eliminarprocesoAction()
        {
            $procesos = new DatProcesos();
            $arr=array();
            $arr = json_decode(stripslashes($this->_request->getPost('arrayProcElim')));
            $bandera = false;
            foreach($arr as $proc)
            {
             $procesos->eliminarProcesos($proc);
             $bandera = true;
            }
            if($bandera==true)
          echo"{'codMsg':1,'mensaje': 'Proceso(s) eliminado(s) satisfactoriamente.'}";

        }
        function listarprocesosAction()
        {
            $start = $this->_request->getPost('start');
            $limit = $this->_request->getPost('limit');
            $arreglo = array();
            $arr = array();
            
            $proceso = new DatProcesos();
            $canproc = $proceso->cantProcesos();
            $datosproc = $proceso->datosProcesos($limit, $start);

            if ($datosproc != null) {
                    foreach($datosproc as $key=>$valor)
                        {
                            $arreglo[$key]['idproceso'] = $valor->idproceso;
                            $arreglo[$key]['denominacion'] = $valor->denominacion;
                            $arreglo[$key]['descripcion'] = $valor->descripcion;
                        }
            }
            $arr['cantidad'] = $canproc;
            $arr['datos'] = $arreglo;
            echo json_encode($arr);
        }
        
       
    }
?>