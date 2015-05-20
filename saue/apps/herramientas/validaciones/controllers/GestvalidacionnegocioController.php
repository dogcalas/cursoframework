<?php

/**
 * Componente para gestinar los sistemas.

 * 

 * @package SIGIS

 * @Copyright UCI

 * @Author Oiner Gomez Baryolo

 * @Author Darien Garc�a Tejo

 * @Author Julio Cesar Garc�a Mosquera 

 * @Author Yoel Hern�ndez Mendoza

 * @Author Daniel Enrique Lopez Mendez

 * @Version 3.0-0
 */
class GestvalidacionnegocioController extends ZendExt_Controller_Secure {

    public function init() {
        parent::init();
    }

    public function gestvalidacionnegocioAction() {
        $this->render();
    }

    function identarXml($direccion) {
        $xml = new DOMDocument();
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        $xml->load($direccion);
        $xml->save($direccion);
    }

    function pathReal($path = "") {
        $dirweb = $_SERVER[DOCUMENT_ROOT];
        $dirapps = str_replace('web', 'apps', $dirweb);
        $final = ($path == "") ? DIRECTORY_SEPARATOR : "";
        return $dirapps . $path . $final;
    }

    function xmlPath() {
        return DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "components.xml";
    }

    function xmlIOCPath() {
        return DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "validaciones.xml";
    }

    public function buscarNodo($xml, $nodo) {
        if ((string) $xml->getName() == $nodo)
            return $xml;
        else {
            foreach ($xml->children() as $hijo) {
                $xml_aux = $this->buscarNodo($hijo, $nodo);
                if ($xml_aux)
                    return $xml_aux;
            }
            return false;
        }
    }

    public function cargarsistemaAction() {
        $nodo = $this->_request->getPost('node');
        $path = $this->_request->getPost('path');
        $arreglo_auxiliar = array();
        if ($nodo == 0) {
            $register = Zend_Registry::getInstance();
            $dirModulesConfig = $register->config->xml->components;

            $xml = new SimpleXMLElement($dirModulesConfig, null, true);
        } else {
            $real_path = $this->pathReal($path);
            $xml_complete = simplexml_load_file($real_path . $this->xmlPath());
            if ($xml_complete == "") {
                $this->showMessage("El sistema no puede hacer uso de validaciones");
                return;
            }
            $xml = $this->buscarNodo($xml_complete, 'cmp' . $nodo);
        }
        $cont = 0;
        $array_return = array();

        foreach ($xml->children() as $child) {
            $arreglo_auxiliar['id'] = (string) $child['id'];
            $arreglo_auxiliar['text'] = (string) $child['text'];
            if ((string) $child['component'] == "1")
                $arreglo_auxiliar['leaf'] = true;
            else
                $arreglo_auxiliar['leaf'] = false;
            $arreglo_auxiliar['path'] = (string) $child['path'];
            $arreglo_auxiliar['component'] = (string) $child['component'];
            $arreglo_auxiliar['abrev'] = (string) $child['abrev'];
            $array_return[$cont] = $arreglo_auxiliar;
            $cont++;
        }
        echo json_encode($array_return);
        return;
    }

    public function cargarvalidacionesAction() {
        $pFile = $this->_request->getPost('path');
        $busqueda = $this->_request->getPost('validacion');
        $direccion = $this->pathReal();
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $direccion = $direccion . $pFile . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "validation.xml";
        $direccion = str_replace('/', DIRECTORY_SEPARATOR, $direccion);
        $direccion = str_replace('//', DIRECTORY_SEPARATOR, $direccion);
        //die($direccion);
        if (!file_exists($direccion)) {
            $dom = new DOMDocument('1.0', 'utf-8');
            $xml = $dom->createElement("validador", '');
            $dom->appendChild($xml);
            $fs = fopen($direccion, "a+");
            $f = fputs($fs, $dom->saveXML());
            $f = fclose($fs);
        }
        if (!file_exists($direccion)) {
            $this->showMessage("Error en permisos de creación de xml. Contacte al administrador");

            die;
        }
        $xml = simplexml_load_file($direccion);
        $val = new SimpleXMLElement($xml->asXml());
        $validacion = $val->children();
        $cantf = count($validacion);

        $arrayValidacion = array();


        if ($busqueda == '') {
            for ($cont = (int) $start; $cont < (int) $start + (int) $limit && $cont < $cantf; $cont++) {
                $accion = $validacion[$cont]->children();
                $cantacc = count($accion);
                $cantval = 0;

                for ($i = 0; $i < $cantacc; $i++) {
                    $arregloval = $accion[$i]->children();
                    $validator = $arregloval->children();
                    //  $p= $validator->children();
                    $cantval = count($validator);
                    //    print_r($p);die;
                    $item = array();
                    $item['controlador_accion'] = (string) $validacion[$cont]->getName() . '_' . $accion[$i]->getName();
                    $item['controlador'] = (string) $validacion[$cont]->getName();
                    $item['accion'] = (string) $accion[$i]->getName();
                    if ($arregloval->getName() == 'precondition') {

                        foreach ($validator as $valid => $val) {
                            $item['denominacion'] = (string) $val['name'];
                            $item['clase'] = (string) $val['class'];
                            $item['metodo'] = (string) $val['method'];
                            $item['codigo'] = (string) $val['error'];
                            $item['descripcion'] = (string) $val->descripcion;
                            //  if(stristr($item['denominacion'],$busqueda)){
                            $canttotal++;
                            $arrayValidacion[] = $item;
                            //  }
                        }
                    }
                }
            }
            $canttotal = $cantf;
        } else {
            for ($cont = (int) $start; $cont < (int) $start + (int) $limit && $cont < $cantf; $cont++) {
                //qwe     
                $accion = $validacion[$cont]->children();
                $cantacc = count($accion);
                $cantval = 0;

                for ($i = 0; $i < $cantacc; $i++) {
                    $arregloval = $accion[$i]->children();
                    $validator = $arregloval->children();
                    $cantval = count($validator);

                    $item = array();
                    $item['controlador_accion'] = (string) $validacion[$cont]->getName() . '_' . $accion[$i]->getName();
                    $item['controlador'] = (string) $validacion[$cont]->getName();
                    $item['accion'] = (string) $accion[$i]->getName();
                    if ($arregloval->getName() == 'precondition') {

                        foreach ($validator as $valid => $val) {
                            $item['denominacion'] = (string) $val['name'];
                            $item['clase'] = (string) $val['class'];
                            $item['metodo'] = (string) $val['method'];
                            $item['codigo'] = (string) $val['error'];
                            $item['descripcion'] = (string) $val->descripcion;
                            if (stristr($item['denominacion'], $busqueda)) {
                                $canttotal++;
                                $arrayValidacion[] = $item;
                            }
                        }
                    }
                }
                //print_r($arrayValidacion);die;
            }
        }



        $result = array('cantidad_filas' => $canttotal, 'datos' => $arrayValidacion);
        echo json_encode($result);
        return;
    }

    public function cargarmetodosAction() {
        $controler = $this->_request->getPost('node');
        $type = $this->_request->getPost('type');
        $pFile = $this->_request->getPost('path');
        $direccion = $this->pathReal();
        $direccion = $direccion . $pFile;
        $direccion = $direccion . DIRECTORY_SEPARATOR . "controllers";
        $direccion = str_replace('//', DIRECTORY_SEPARATOR, $direccion);
        $direccion_subsistemas = str_replace('/', DIRECTORY_SEPARATOR, $direccion);
        $direccionarch = $direccion_subsistemas . DIRECTORY_SEPARATOR . $controler;

        //print_r($direccion);print_r('-'.$direccion_subsistemas);print_r('-'.$direccionarch);die;
        switch ($type) {
            case 'file': {
                    $claseinstrosp = new InstrospectorModel();
                    $clases = $claseinstrosp->getClassesByFile($direccionarch);
                    $metodos = $claseinstrosp->getMethodsByClass(FALSE, $clases[0]->text, $direccionarch);
                    $aux = array();
                    foreach ($metodos as $a) {
                        $valido = substr_count($a->text, 'Action');
                        if ($valido == 1) {
                            $action = substr($a->text, strlen($a->text) - 6);
                            if (substr_count($action, 'Action') == 1) {
                                $a->text = substr($a->text, 0, strlen($a->text) - 6);
                                $aux[] = $a;
                            }
                        }
                    }
                    echo json_encode($aux);
                    return;
                }
            case 'folder': {
                    $claseinstrosp = new InstrospectorModel();
                    $archivos = $claseinstrosp->getFilesByPath($direccion_subsistemas, false);
                    foreach ($archivos as $value) {
                        $value->text = substr($value->text, 0, strlen($value->text) - 14);
                    }
                    echo json_encode($archivos);
                    return;
                }
        }
    }

    public function cargararbolvalidacionesAction() {
        $validators = $this->_request->getPost('node');
        $type = $this->_request->getPost('type');
        $pFile = $this->_request->getPost('path');
        $direccion = $this->pathReal();
        $direccion = $direccion . $pFile;
        $direccion = $direccion . DIRECTORY_SEPARATOR . "validators";
        $direccion = str_replace('//', DIRECTORY_SEPARATOR, $direccion);
        $direccion_subsistemas = str_replace('/', DIRECTORY_SEPARATOR, $direccion);
        $direccionarch = $direccion_subsistemas . DIRECTORY_SEPARATOR . $validators;
        switch ($type) {
            case 'file': {
                    $claseinstrosp = new InstrospectorModel();
                    $clases = $claseinstrosp->getClassesByFile($direccionarch);
                    $metodos = $claseinstrosp->getMethodsByClass(FALSE, $clases[0]->text, $direccionarch);
                    echo json_encode($metodos);
                    return;
                }
            case 'folder': {
                    $claseinstrosp = new InstrospectorModel();
                    $archivos = $claseinstrosp->getFilesByPath($direccion_subsistemas, false);
                    echo json_encode($archivos);
                    return;
                }
        }
    }

    public function cargarcomboerrorAction() {
        $pFile = $this->_request->getPost('path');
        $direccion = $this->pathReal();
        $direccion = $direccion . $pFile . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "exception.xml";
        $direccion = str_replace('/', DIRECTORY_SEPARATOR, $direccion);
        if (!file_exists($direccion)) {
            $this->showMessage("Este sistema no tiene excepciones definidas.");
            die;
        }
        $xml = simplexml_load_file($direccion);
        $excepciones = $xml->children();
        $cantf = count($excepciones);
        $arrayExcepciones = array();

        for ($cont = 0; $cont < $cantf; $cont++) {
            $item = array();
            $item['nombre'] = (string) ($excepciones[$cont]->attributes()->$nombre);
            $item['codigo'] = $excepciones[$cont]->getName();
            $item['tipo'] = (string) $excepciones[$cont]->tipo;
            $item['mensaje'] = (string) ($excepciones[$cont]->es->mensaje);
            $item['descripcion'] = (string) ($excepciones[$cont]->es->descripcion);
            $arrayExcepciones[] = $item;
        }

        $excepciones = $arrayExcepciones;
        $item = array();
        $arraexcep = array();
        $canttotal = 0;
        foreach ($excepciones as $excep) {
            $item['nombre'] = $excep['nombre'];
            $item['codigo'] = $excep['codigo'];
            $canttotal++;
            $arraexcep[] = $item;
        }
        $result = array('cantidad_filas' => $canttotal, 'datos' => $arraexcep);
        echo json_encode($result);
        return;
    }

    public function adicionarvalidacionAction() {
        $pFile = $this->_request->getPost('path');
        $nombreclaseArray = explode(".",$this->_request->getPost('nombreclase'));
        $nombreclase = $nombreclaseArray[0];        
        $nombremetodo = $this->_request->getPost('nombremetodo').'Action';
        $nombreclass = $this->_request->getPost('nombreclass');
        $nombremethod = $this->_request->getPost('nombremethod');
        $denominacion = $this->_request->getPost('denominacion');
        $descripcion = $this->_request->getPost('descripcion');
        $error = $this->_request->getPost('codigo');

        $direccion = $this->pathReal();
        $direccion = $direccion . $pFile . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "validation.xml";
        $xml = simplexml_load_file($direccion);
        $val = new SimpleXMLElement($xml->asXml());
        $contr = $val->children();
        $cantcontr = count($contr);


        if ($cantcontr != 0) {
            $contrencontrado = false;
            for ($i = 0; $i < $cantcontr; $i++) {
                //print_r($contr[$i]->getName());die;
                if ($contr[$i]->getName() == $nombreclase) {
                    //  print_r($contr[$i]);die;
                    $contrencontrado = true;
                    $encontrado = false;
                    $acc = $contr[$i]->children();
                    //print_r($acc);die;
                    // print_r($nombremetodo);die;
                    // $acc=$acc->children();
                    $cantacc = count($acc);
                    // print_r($cantacc);die;
                    for ($j = 0; $j < $cantacc; $j++) {
                        //print_r($acc->getName());die;
                        if ($acc[$j]->getName() == $nombremetodo) {
                            $encontrado = true;
                            $valencont = false;
                            //acc[j]==el metodo seleccionado(cargar estructuras)
                            $precon = $acc[$j]->children();

                            //print_r($precon);die;
                            $valid = $precon->precondition->children();
                            // print_r($valid);die;
                            $cantval = count($valid);
                            // print_r($precon->param["method"]);die;

                            if ((string) $precon->param['method'] == "") {
                                $precondition = 'precondition';
                                //  print_r($precon->getName());die;
                                if ($precon->precondition == '') {
                                    $validator = $val->$nombreclase->$nombremetodo->addChild($precondition);
                                    $validator = $validator->addChild('validator');
                                } else {
                                    $validator = $val->$nombreclase->$nombremetodo->$precondition->addChild('validator');

                                    //  $validator = $val->$nombreclase->$nombremetodo->$precondition->addChild($denominacion);
                                    //  $validator = $val->$nombreclase->$nombremetodo->$precondition->addChild('validator');
                                }
                                $validator->addAttribute('name', $denominacion);
                                $validator->addAttribute('class', $nombreclass);
                                $validator->addAttribute('method', $nombremethod);
                                $validator->addAttribute('error', $error);
                                $descripcion = $validator->addChild("descripcion", $descripcion);
                                //   print_r("eeheheheheh");die;
                                $val->asXml($direccion);
                                $this->identarXml($direccion);
                                $this->showMessage('La validación fue insertada satisfactoriamente.');
                                die;
                            }
                            else
                                foreach ($valid->children() as $pepe) {
                                    foreach ($valid->attributes() as $att) {
                                        // print_r($att);die;
                                        if ($att->getName() == "method") {
                                            if ((string) $att[0] == $nombremethod) {

                                                $valencont = true;
                                                $this->showMessage('Esta validación ya existe en el método ' . $nombremetodo . '.');
                                            }
                                        }
                                    }
                                }
                            if (!$valencont) {  //print_r("eeheheheheh");die;
                                $precondition = 'precondition';
                                // $validator = $val->$nombreclase->$nombremetodo->$precondition->addChild($denominacion);
                                $validator = $val->$nombreclase->$nombremetodo->$precondition->addChild('validator');
                                /// print_r("eeheheheheh");die;
                                //$validator = $precond->addChild($denominacion);
                                $validator->addAttribute('name', $denominacion);
                                $validator->addAttribute('class', $nombreclass);
                                $validator->addAttribute('method', $nombremethod);
                                $validator->addAttribute('error', $error);
                                $descripcion = $validator->addChild("descripcion", $descripcion);
                                //   print_r("eeheheheheh");die;
                                $val->asXml($direccion);
                                $this->identarXml($direccion);
                                $this->showMessage('La validación fue insertada satisfactoriamente.');
                                die;
                            }
                        }
                    }
                    if (!$encontrado) {

                        $nombmethod = $val->$nombreclase->addChild($nombremetodo);
                        $precond = $nombmethod->addChild("precondition");
                        $validator = $precond->addChild('validator');
                        // $validator = $precond->addChild($denominacion);
                        $validator->addAttribute('name', $denominacion);
                        $validator->addAttribute('class', $nombreclass);
                        $validator->addAttribute('method', $nombremethod);
                        $validator->addAttribute('error', $error);
                        $descripcion = $validator->addChild("descripcion", $descripcion);
                        $val->asXml($direccion);
                        $this->identarXml($direccion);
                        $this->showMessage('La validación fue insertada satisfactoriamente.');
                    }
                    else
                        break;
                }
            }

            if (!$contrencontrado) {
                $nombclass = $val->addChild($nombreclase);
                $nombmethod = $nombclass->addChild($nombremetodo);
                $precond = $nombmethod->addChild("precondition");
                //$validator = $precond->addChild($denominacion);
                $validator = $precond->addChild('validator');
                $validator->addAttribute('name', $denominacion);
                $validator->addAttribute('class', $nombreclass);
                $validator->addAttribute('method', $nombremethod);
                $validator->addAttribute('error', $error);
                $descripcion = $validator->addChild("descripcion", $descripcion);
                $val->asXml($direccion);
                $this->identarXml($direccion);
                $this->showMessage('La validación fue insertada satisfactoriamente.');
            }
            // print_r("ssasasas");die;
        } else {
            $nombclass = $val->addChild($nombreclase);
            $nombmethod = $nombclass->addChild($nombremetodo);
            $precond = $nombmethod->addChild("precondition");
            //  $validator = $precond->addChild($denominacion);
            $validator = $precond->addChild('validator');
            $validator->addAttribute('name', $denominacion);
            $validator->addAttribute('class', $nombreclass);
            $validator->addAttribute('method', $nombremethod);
            $validator->addAttribute('error', $error);
            $descripcion = $validator->addChild("descripcion", $descripcion);
            $val->asXml($direccion);
            $this->identarXml($direccion);
            $this->showMessage('La validación fue insertada satisfactoriamente.');
        }
    }

    public function modificarvalidacionAction() {
        $pFile = $this->_request->getPost('path');
        $nombreclase = $this->_request->getPost('padresel');
        $nombremetodo = $this->_request->getPost('hijosel');
        $nombreclass = $this->_request->getPost('claseval');
        $nombremethod = $this->_request->getPost('metodoval');
        $denominacion = $this->_request->getPost('nombreval');
        $descripcion = $this->_request->getPost('descripcion');
        $errorval = $this->_request->getPost('codigo');
        $direccion = $this->pathReal();
        $direccion = $direccion . $pFile . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "validation.xml";
//print_r($direccion);die;
        $doc = new DOMDocument;
        $doc->load((string) $direccion);
        $controlador = $doc->documentElement->getElementsByTagName($nombreclase)->item(0);
        $accion = $controlador->getElementsByTagName($nombremetodo)->item(0);
        $precondition = $accion->getElementsByTagName('precondition')->item(0);
        $validacion = $precondition->getElementsByTagName('validator')->item(0);

        if ($validacion->getAttribute('name') == $denominacion) {

            $old = $precondition->removeChild($validacion);

            file_put_contents((string) $direccion, $doc->saveXML());
        }
        $xml = simplexml_load_file($direccion);
        $val = new SimpleXMLElement($xml->asXml());
        //print_r($val);die;
        $precon = 'precondition';
        $nueva = $val->$nombreclase->$nombremetodo->$precon->addChild('validator');
        $nueva->addAttribute('name', $denominacion);
        $nueva->addAttribute('class', $nombreclass);
        $nueva->addAttribute('method', $nombremethod);
        $nueva->addAttribute('error', $errorval);
        $descripcion = $nueva->addChild("descripcion", $descripcion);
        $val->asXml($direccion);
        $this->identarXml($direccion);
        $this->showMessage('La validación fue modificada satisfactoriamente.');
        die;
    }

    public function eliminarvalidacionAction() {
        $pFile = $this->_request->getPost('path');
        $nombreclase = $this->_request->getPost('padresel');
        $nombremetodo = $this->_request->getPost('hijosel');
        $denominacion = $this->_request->getPost('nombreval');
        $direccion = $this->pathReal();
        $direccion = $direccion . $pFile . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "validation.xml";

        $doc = new DOMDocument;
        $doc->load((string) $direccion);
        $controlador = $doc->documentElement->getElementsByTagName($nombreclase)->item(0);

        $accion = $controlador->getElementsByTagName($nombremetodo)->item(0);
        // print_r($accion);die;
        $precondition = $accion->getElementsByTagName('precondition')->item(0);
        $validacion = $precondition->getElementsByTagName('validator')->item(0);
        
        if ($validacion->getAttribute('name') == $denominacion) {            
            $old = $precondition->removeChild($validacion);
        }
        file_put_contents((string) $direccion, $doc->saveXML());
        $this->identarXml($direccion);
    }

    /* public function buscarvalidacionAction()
      {
      $idsistema = $this->_request->getPost('idsistema');
      $validacion = $this->_request->getPost('validacion');

      $dirsistema =  $this->integrator->seguridad->getRutasistema($idsistema);
      $direccion = $dirsistema.DIRECTORY_SEPARATOR."comun".DIRECTORY_SEPARATOR."recursos".DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."validation.xml";

      $xml = simplexml_load_file($direccion);
      $val = new SimpleXMLElement($xml->asXml());
      //print_r($cantcontr);die;
      $contr = $val->children();
      $cantcontr = count($contr);

      $arrayValidacion = array();
      $canttotal = 0;

      for($cont= 0; $cont< $cantcontr; $cont++)
      {
      $accion = $contr[$cont]->children();

      $cantacc = count($accion);
      $cantval = 0;

      for($i = 0; $i < $cantacc; $i++)
      {
      $arregloval = $accion[$i]->children();
      $validator = $arregloval->children();

      $cantval = count($validator);
      $item = array();

      if($arregloval->getName() == 'precondition')
      {
      for($j = 0; $j < $cantval; $j++)
      {
      $valid = $validator[$j]->getName();
      //
      //print_r($valid);die;
      if ($valid == $validacion)
      {
      $canttotal++;
      foreach($validator as $valida => $val)
      {
      $item['controlador_accion'] = (string)$contr[$cont]->getName().'_'.$accion[$i]->getName();
      $item['controlador'] = (string)$contr[$cont]->getName();
      $item['accion'] = (string)$accion[$i]->getName();
      $item['denominacion'] = (string)$val->getName();
      //print_r($item);die;
      $item['clase'] = (string)$val['class'];
      $item['metodo'] = (string)$val['method'];
      $item['error'] = (string)$val['error'];
      $item['descripcion'] = (string)$val->descripcion;
      $arrayValidacion[] = $item;
      }
      }
      }


      }
      ;
      }
      //print_r($arrayValidacion);die;
      }
      $result =  array('cantidad_filas' => $canttotal, 'datos' => $arrayValidacion);
      echo json_encode($result);return;

      } */
}

?>
