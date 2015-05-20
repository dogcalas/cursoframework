<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */
	class GestservicioconsController extends ZendExt_Controller_Secure
	{
		function init ()
		{
			parent::init();
		}

		function gestservicioconsAction()
		{
			$this->render();
		}

                function cargarsistemasAction() {
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
                        }
                        echo json_encode ($sistemaArr);return;
                    }
                    else
                    {
                        $sist = $sistemas->toArray();
                        echo json_encode($sist);return;
                    }
                }

		function insertarservicioconsAction()
		{
                    $servicio = $this->_request->getPost('servicio');
                    $subsistema = $this->_request->getPost('subsistema');
                    $proceso = $this->_request->getPost('proceso');
                    $protocolo = $this->_request->getPost('protocolo');
                    $idsistema = $this->_request->getPost('idsistema');
                    While (!count($sistema)){
                        $hijos = DatSistema::cargarsistemahijjos($idsistema);
                        if(!count($hijos->toArray())){
                            print_r("joder tio");die;//aki va una exception
                        }
                        $idsistema = $hijos[0]['idsistema'];
                        $sistema = DatFuncionalidad::cargarfuncionalidades($idsistema,1,0)->toArray();
                    }

                    $sistema = $sistema[0]['referencia'];
                    $sistema = explode('/',$sistema);
                    $sistema = ($sistema[0])?$sistema[0]:$sistema[1];
                    $direccion = explode('seguridad',realpath(dirname(__FILE__)));
                    $direccion = $direccion[0].$sistema.DIRECTORY_SEPARATOR."comun".DIRECTORY_SEPARATOR."recursos".DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."consumos.xml";
                    if (!file_exists($direccion)) {
                        //lanzar excepcion
                    }
                    $xml = simplexml_load_file($direccion);
                    if($xml->$servicio){
                        unset($xml->$servicio);
                    }
                    $hijo = $xml->addChild($servicio);
                    $hijo->addAttribute('subsistema',$subsistema);
                    $hijo->addAttribute('proceso',$proceso);
                    $hijo->addAttribute('protocolo',$protocolo);
                    file_put_contents($direccion, $xml->asXml());
                    $this->showMessage('El servicio fue insertado satisfactoriamente.');
		}

		function eliminarservicioconsAction()
		{
			$servicio = $this->_request->getPost('servicio');
			$idsistema = $this->_request->getPost('idsistema');
			While (!count($sistema)){
                            $hijos = DatSistema::cargarsistemahijjos($idsistema);
                            if(!count($hijos->toArray())){
                                print_r("joder tio");die;//aki va una exception
                            }
                            $idsistema = $hijos[0]['idsistema'];
                            $sistema = DatFuncionalidad::cargarfuncionalidades($idsistema,1,0)->toArray();
                        }
                        $sistema = $sistema[0]['referencia'];
                        $sistema = explode('/',$sistema);
                        $sistema = ($sistema[0])?$sistema[0]:$sistema[1];
                        $direccion = explode('seguridad',realpath(dirname(__FILE__)));
                        $direccion = $direccion[0].$sistema.DIRECTORY_SEPARATOR."comun".DIRECTORY_SEPARATOR."recursos".DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."consumos.xml";
                        if (!file_exists($direccion)) {
                            //lanzar excepcion
                        }
                        $xml = simplexml_load_file($direccion);
                        unset($xml->$servicio);
                        file_put_contents($direccion, $xml->asXml());
			$this->showMessage('El servicio fue eliminado satisfactoriamente.');
		}

		function cargarservicioconsAction()
		{
                    $idsistema = $this->_request->getPost('idsistema');
                    $limit = $this->_request->getPost('limit');
                    $start = $this->_request->getPost('start');
                    $sistema = DatFuncionalidad::cargarfuncionalidades($idsistema,1,0)->toArray();

                    While (!count($sistema)){
                        $hijos = DatSistema::cargarsistemahijjos($idsistema);
                        if(!count($hijos->toArray())){
                            print_r("joder tio");die;//aki va una exception
                        }
                        $idsistema = $hijos[0]['idsistema'];
                        $sistema = DatFuncionalidad::cargarfuncionalidades($idsistema,1,0)->toArray();
                    }
                    $sistema = $sistema[0]['referencia'];
                    $sistema = explode('/',$sistema);
                    $sistema = ($sistema[0])?$sistema[0]:$sistema[1];
                    $direccion = explode('seguridad',realpath(dirname(__FILE__)));
                    $direccion = $direccion[0].$sistema.DIRECTORY_SEPARATOR."comun".DIRECTORY_SEPARATOR."recursos".DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."consumos.xml";
                    
                    if (!file_exists($direccion)) {
                        $dom = new DOMDocument('1.0', 'utf-8');
                        $xml = $dom->createElement("servicios",'');
                        $dom->appendChild($xml);
                        $fs = fopen ( $direccion, "a+");
                        $f = fputs($fs, $dom->saveXML());
                        $f = fclose($fs);
                    }
                    $xml = simplexml_load_file($direccion);
                    $servicios = $xml->children();
                    $cantf = count($servicios);
                    $arrayServicios = array();
//                    for ($i = $start; $i < $cantf; $i++) {
//                        $item = array();
//                        $item['denominacion'] = $servicios[$i]->getName();
//                        foreach($servicios[$i]->attributes() as $at_name=>$at_value){
//
//                            $item[$at_name] = (string) $at_value;
//                        }
//                        $arrayServicios[] = $item;
//                    }
                    foreach ($servicios as $serv){
                        $item = array();
                        $item['denominacion'] = $serv->getName();
                        foreach($serv->attributes() as $at_name=>$at_value){

                            $item[$at_name] = (string) $at_value;
                        }
                        $arrayServicios[] = $item;
                    }
                    $result =  array('cantidad_filas' => $cantf, 'datos' => $arrayServicios);
                    echo json_encode($result);return;


		}

		function cargarprocesosAction()
		{
                    $procesos = DatProcesos::obtenerTodosProcesos()->toArray();
                    $result = array ('cantidad_filas' => sizeof($procesos), 'datos' => $procesos);
                    echo json_encode($result);
                    return;
		}
                function cargarprotocolosAction()
		{
                    $protocolos[0]['idprotocolo']= 1;
                    $protocolos[0]['denominacion']= 'ioc';
                    $protocolos[1]['idprotocolo']= 2;
                    $protocolos[1]['denominacion']= 'soap';

                    $result = array ('cantidad_filas' => 2, 'datos' => $protocolos);
                    echo json_encode($result);
                    return;
		}




                function cargarXMLAction(){
                    $register = Zend_Registry::getInstance();
                    $dirModulesConfig = $register->config->xml->ioc;
                    $iocxml = new SimpleXMLElement($dirModulesConfig, null, true);
                    return 	$iocxml;
		}
                function cargarserviciosioc($nodo){
                    $s_xml = $this->cargarXMLAction();
                    $arr = $s_xml->$nodo;
                    $arreglo = array();
                    foreach ($arr->children() as $valores => $value){
                        $item = array();
                        $item['id'] = $value->getName();
                        $item['text'] = $value->getName();
                        $item['wsdl'] = ($value->wsdl)?true:false;
                        $item['leaf'] = true;
                        $arreglo[] = $item;
                    }
                    return $arreglo;
                }
                function cargarsubsistemas(){
			$s_xml = $this->cargarXMLAction();
                        $arr_subsistemas = $s_xml->children();
                        $arreglo = array();
                        foreach($arr_subsistemas as $valores => $value){
                            $item = array();
                            $item['id'] = $value->getName();
                            $item['text'] = $value->getName();
                            $arreglo[] = $item;
                        }
                       return $arreglo;
		}

                function cargarIOCAction(){
                     $nodo = $this->_request->getPost('node');
                     $arr = Array();
                     if($nodo)
                      $arr = $this->cargarserviciosioc($nodo);
                     else
                      $arr = $this->cargarsubsistemas();
                   echo json_encode($arr);return;
                }
	}
?>