<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien García Tejo
 * @author Julio Cesar García Mosquera  
 * @version 1.0-0
 */
	class GestdatfuncionesController extends ZendExt_Controller_Secure
	{
		function init ()
		{
			parent::init();
		}
				
		function gestdatfuncionesAction()
		{
			$this->render();
		}
		
	    function insertarfuncionAction()
		{
			$funcion=new DatFunciones();
			$funcion->idservicio=$this->_request->getPost('idservicio');
			$funcion->denominacion=$this->_request->getPost('denominacion');
			$funcion->descripcion=$this->_request->getPost('descripcion');
			$model=new DatFuncionesModel();
			$arrayFunciones = array();
			$arrayFunciones = DatFunciones::obtenerFuncionesServicio($funcion->idservicio);
			$denom = true;
			foreach($arrayFunciones as $funciones)
			{
				if($funciones['denominacion'] == $funcion->denominacion)
				{
					$denom = false;
					break;	
				}
			}
			if(!$denom)
				throw new ZendExt_Exception('SEG034');
			else
			{
				$model->insertarfuncion($funcion);
				$this->showMessage('La funci&oacute;n fue insertada satisfactoriamente.');
			}
		}
		
		function modificarfuncionAction()
		{
			$funcion=new DatFunciones();
			$funcion = Doctrine::getTable('DatFunciones')->find($this->_request->getPost('idfunciones'));
			$funcion->idservicio=$this->_request->getPost('idservicio');
			$funcion->denominacion=$this->_request->getPost('denominacion');
			$funcion->descripcion=$this->_request->getPost('descripcion');
			$idfunciones = $this->_request->getPost('idfunciones');
			$model=new DatFuncionesModel();
			$arrayFunciones = array();
			$arrayFunciones = DatFunciones::obtenerFuncionesServicio($funcion->idservicio);
			$denom = true;
			$auxden = 0;
			foreach($arrayFunciones as $aux2)
			{
				if($aux2['idfunciones'] == $idfunciones)
				{
					$auxden = $aux2['denominacion'];
				}	
			}
			if($funcion->denominacion != $auxden)
			{
				foreach($arrayFunciones as $funciones)
				{
					if($funciones['denominacion'] == $funcion->denominacion)
					{
						$denom = false;
						break;	
					}
				}
			}
			if(!$denom)
				throw new ZendExt_Exception('SEG034');
			else
			{
				$model->insertarfuncion($funcion);
				$this->showMessage('La funci&oacute;n fue modificada satisfactoriamente.');
			}
        }
        		
	    function eliminarfuncionAction()
		{
			$funcion=new DatFunciones();
			$funcion= Doctrine::getTable('DatFunciones')->find($this->_request->getPost('idfunciones'));
			$model=new DatFuncionesModel();
			$model->eliminarfuncion($funcion);
			$this->showMessage('La funci&oacute;n fue eliminada satisfactoriamente.');
		}
		
		function cargarserviciosAction()
		{
			$servicios=DatServicio::cargarser(0,0);
			if(count($servicios))
			{
				foreach ($servicios as $valores=>$valor)
				{
                $servicioArr[$valores]['id'] = $valor->idservicio;
                $servicioArr[$valores]['text'] = $valor->denominacion;
                $servicioArr[$valores]['leaf'] = true;
				}
			echo json_encode ($servicioArr);return;
			}
		}
		
		function cargarfuncionesAction()
		{
			$idservicio = $this->_request->getPost('idservicio');
			$limit = $this->_request->getPost("limit");
			$start = $this->_request->getPost("start");
		    $datosfunc=DatFunciones::cargarfunciones($idservicio,$limit,$start);
			$cantf = DatFunciones::obtenercantfunc($idservicio);
			 if($datosfunc)
				{					
				$datos=$datosfunc->toArray();
				$result =  array('cantidad_filas' => $cantf, 'datos' => $datos);
				echo json_encode($result);return;
                }
		}
}	
?>