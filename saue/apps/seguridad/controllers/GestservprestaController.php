<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo
 * @author Darien Garc?a Tejo
 * @author Julio Cesar Garc?a Mosquera
 * @version 1.0-0
 */
	class GestservprestaController extends ZendExt_Controller_Secure
	{
		function init ()
		{
			parent::init();
		}

		public $arreglo_xml;
		function gestservprestaAction()
		{
			$this->render();
		}

		
		function cargarsistemashojasAction() {
            $sistemas = DatSistema::cargarsistema($this->_request->getPost('node'));
            if($sistemas->count())
            {
                foreach($sistemas as $valores=>$valor)
                {
                    $sistemaArr[$valores]['id'] = $valor->id;
                    $sistemaArr[$valores]['text'] = $valor->text;
                    $sistemaArr[$valores]['abreviatura'] = $valor->abreviatura;
                    $sistemaArr[$valores]['descripcion'] = $valor->descripcion;
                    $sistemaArr[$valores]['idpadre'] = $valor->idpadre;
                    $sistemaArr[$valores]['icono'] = $valor->icono;
                    $sistemaArr[$valores]['servidorweb'] = $valor->externa;
                    $sistemaArr[$valores]['leaf'] = true;
                    //$sistemaArr[$valores]['checked'] = false;
                }
                echo json_encode ($sistemaArr);return;
            }
            else
            {
                $sist = $sistemas->toArray();
                echo json_encode($sist);return;
            }
        } 
		
		
		function insertarservicioAction()
		{
		    $servicio = new DatServicio();
			$servicio->idproceso=$this->_request->getPost('idprocess');
			$servicio->externo=true;
			$servicio->denominacion=$this->_request->getPost('denominacion');
            $servicio->descripcion=$this->_request->getPost('descripcion');
			$servicio->wsdl=$this->_request->getPost('wsdl');
			$servicio->idsistema=$this->_request->getPost('idsistema');
			$denominacion=$this->_request->getPost('denominacion');
			$servicio->activo=false; 
			$serviciomodel= new DatServPrestaModel();
			$arrayServPresta = array();
			$arrayServPresta = DatServicio::obtenerServPrestaSistema($servicio->idsistema);
                        $arrayServPrestaWSDL = DatServicio::obtenerWSDLSistema($servicio->idsistema);
			$denom = true;
                        $wsdl = true;
			foreach($arrayServPresta as $ServPresta)
			{
				if($ServPresta['denominacion'] == $servicio->denominacion)
				{
					$denom = false;
					break;
				}
			}
                        foreach($arrayServPrestaWSDL as $ServPresta)
			{
				if($ServPresta['wsdl'] == $servicio->wsdl)
				{
					$wsdl = false;
					break;
				}
			}
			if(!$denom)
				throw new ZendExt_Exception('SEG032');
                        else if(!$wsdl)
                                throw new ZendExt_Exception('SEG052');
			else
			{
				$serviciomodel->insertarservicio($servicio);
				echo"{'codMsg':1,'mensaje': 'El servicio fue insertado satisfactoriamente.'}";
			}
		}
		
		
		
		function insertarservicioIntAction()
		{
		 $servicios = json_decode(stripslashes($this->_request->getPost('arraycheck')));
		 $idproceso=$this->_request->getPost('idprocess');
		 $idsistema=$this->_request->getPost('idsistema');
		 $ext = FALSE;
		 $act = FALSE;
		 DatServicio::eliminarServciosInternos($idsistema,$ext,$idproceso);
		 if(count($servicios))
		 {
            $arrayservicios =  array();
		    foreach($servicios as $serv)
            {            
            $servicio = new DatServicio();
            $servicio->idproceso=$idproceso;
			$servicio->externo=false;
			$servicio->denominacion=$serv[0];
            $servicio->descripcion=$serv[1];
			$servicio->wsdl="";
			$servicio->idsistema = $idsistema;
			$servicio->activo=$act; 
            $arrayservicios[] = $servicio;
            }
			$serviciomodel= new DatServPrestaModel();
			$serviciomodel->insertarservicioint($arrayservicios);
			echo"{'codMsg':1,'mensaje': 'Los servicios fueron insertados satisfactoriamente..'}";
                        
                 }
		 else
		echo"{'codMsg':1,'mensaje': 'Los servicios fueron eliminados satisfactoriamente..'}";
		}

		
		function eliminarservicioAction()
		{
		   $servicio= new DatServicio();
		   $servicio= Doctrine::getTable('DatServicio')->find($this->_request->getPost('idservicio'));
		   $serviciomodel= new DatServPrestaModel();
		   $serviciomodel->eliminarservicio($servicio);
		   echo"{'codMsg':1,'mensaje': 'El servicio fue eliminado satisfactoriamente.'}";
		}

		function modificarservicioAction()
		{
		  // die('1');
			$wsdl = $this->_request->getPost('wsdl');
		    /*if($tabactivo == 'tab1')
			{*/
			$nodoselected = $this->_request->getPost('nodoseleccionado');
			$parentnodoselected = $this->_request->getPost('padrenodoseleccionado');
		    $register = Zend_Registry::getInstance();
            $dirModulesConfig = $register->config->xml->ioc;
			$s_xml = new SimpleXMLElement($dirModulesConfig, null, true);
			$wsdldata = 'wsdl';
			$dir='dir';
			$s_xmlu = $s_xml->$parentnodoselected->$nodoselected;
			if(!$s_xmlu->wsdl)
			{
			$s_xmld = $s_xmlu->addChild('wsdl');
			$s_xmld->addAttribute("dir",$wsdl);
			}
			else
            $s_xmlu->$wsdldata->attributes()->$dir = $wsdl;		
           // echo"<pre>"; print_r($s_xmlu);die;			
			file_put_contents($dirModulesConfig,$s_xml->asXML());
			echo"{'codMsg':1,'mensaje': 'El servicio fue modificado satisfactoriamente.'}";
			//}
            /*else
			{
		    $servicio = new DatServicio();
			$servicio= Doctrine::getTable('DatServicio')->find($this->_request->getPost('idservicio'));
			$servicio->denominacion=$this->_request->getPost('denominacion');
		    $servicio->descripcion=$this->_request->getPost('descripcion');
			$servicio->wsdl=$wsdl;
			$servicio->idsistema=$this->_request->getPost('idsistema');
			$idservicio = $this->_request->getPost('idservicio');
			$serviciomodel= new DatServPrestaModel();
			$arrayServPresta = array();
			$arrayServPresta = DatServicio::obtenerServPrestaSistema($servicio->idsistema);
			$denom = true;
			$auxden = 0;
			foreach($arrayServPresta as $aux2)
			{
				if($aux2['idservicio'] == $idservicio)
				{
					$auxden = $aux2['denominacion'];
				}
			}
			if($servicio->denominacion != $auxden)
			{

				foreach($arrayServPresta as $ServPresta)
				{
					if($ServPresta['denominacion'] == $servicio->denominacion)
					{
						$denom = false;
						break;
					}
				}
			}

			if(!$denom)
				throw new ZendExt_Exception('SEG032');
			else
			{
				$serviciomodel->modificarservicio($servicio);
				$this->showMessage('El servicio fue modificado satisfactoriamente.');
			}
		   }*/
		}

		function cargarservicioAction(){
				$idsistema = $this->_request->getPost('idsistema');
				$start =$this->_request->getPost("start");
	            $limit =$this->_request->getPost("limit");
				$externo = true;
				$datservicio = DatServicio::obtenerServicio($idsistema,$externo,$limit,$start);
				$cantserv = DatServicio::cantserviciop($idsistema,$externo);
				$datos = $datservicio->toArray ();
				$result = array ('cantidad_filas' => $cantserv, 'datos' => $datos);
				 echo json_encode($result);return;
		}

		function cargarsubXMLAction($nodounico){
			$register = Zend_Registry::getInstance();
                        $dirModulesConfig = $register->config->xml->ioc;
						$s_xml = new SimpleXMLElement($dirModulesConfig, null, true);
						if($nodounico)
						 $s_xml = $s_xml->$nodounico;
                        return 	$s_xml;
		}
		
		function cargarXMLAction(){
			$register = Zend_Registry::getInstance();
                        $dirModulesConfig = $register->config->xml->ioc;
						$s_xml = new SimpleXMLElement($dirModulesConfig, null, true);
						
                        return 	$s_xml;
		}

		function cargarSUBAction(){
                     $nodo = $this->_request->getPost('node');
					 $nodounico = $this->_request->getPost('abreviatura');
					 $idproceso = $this->_request->getPost('idproceso');
                     $arr = Array();
                   
                     if($nodo)
                      $arr = $this->cargarservicios($nodo,$idproceso);
                     else
                      $arr = $this->cargarsubsistemas($nodounico);
                      
                   echo json_encode($arr);return;
                }
				

        function cargarsubsistemas($nodounico){
			$s_xml = $this->cargarsubXMLAction($nodounico);
			$cont_id = 1;
			$arr_subsistemas = Array();
                        $arreglo = Array();
						if(!$nodounico)
                        $arr_subsistemas = $s_xml->children();
						else
						 $arr_subsistemas = $s_xml;
                         
                        foreach($arr_subsistemas as $valores => $value){
                             $arreglo[$cont_id-1]['id'] = $value->getName();
                             $arreglo[$cont_id-1]['text'] = $value->getName();
                             $cont_id++;
                        }
                       return $arreglo;
		}
		
		
		function buscarEnArreglo($arreglo,$denominacion)
		{
		 foreach($arreglo as $proc)
			{
				if($proc['denominacion'] == $denominacion)
				{
					return true;
					break;
				}
			}
			return false;
		}
		
				
        function cargarservicios($nodo,$idproceso){
                    $s_xml = $this->cargarXMLAction();
                    $arr = $s_xml->$nodo;
                    $arreglo = array();
                    $x = 1;
					$servi = new DatServicio();
					$procesos = DatServicio::obtenerProcesosDistintos($idproceso);
					/* echo"<pre>"; print_r(count($procesos));echo"</pre>";
					     die();*/
                    foreach ($arr->children() as $valores => $value){
					    if(!$this->buscarEnArreglo($procesos,$value->getName()))
						{
                        $arreglo[$x-1]['id'] = $value->getName();
                        $arreglo[$x-1]['text'] = $value->getName();
						if($value->wsdl)
						$arreglo[$x-1]['wsdl'] = (string)$value->wsdl['dir'];
                        $arreglo[$x-1]['leaf'] = true;
						/*$cant = $servi->verificarserviciop($value->getName());
						if($cant > 0)
						$arreglo[$x-1]['checked'] = true;
						else
						$arreglo[$x-1]['checked'] = false;*/
						$x++;
						}
                    }
					
                     return $arreglo;
                   
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
