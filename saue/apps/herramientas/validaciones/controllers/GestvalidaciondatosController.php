<?php

/**
 * Componente para gestinar los sistemas.
 * @package SIGIS
 * @Copyright UCI
 * @Author Oiner Gomez Baryolo 
 * @Author Rene Bauta
 * @Version 3.0-0
 */
class GestvalidaciondatosController extends ZendExt_Controller_Secure {

    public function init() {
        parent::init();
    }

    public function gestvalidaciondatosAction() {
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

    function xmlValPath() {
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
        $busqueda = $this->_request->getPost('validacion');
        $pFile = $this->_request->getPost('path');
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $direccion = $this->pathReal($pFile);
        $direccion = $direccion . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "validation.xml";
        $direccion = str_replace('/', DIRECTORY_SEPARATOR, $direccion);
        $direccion = str_replace('//', DIRECTORY_SEPARATOR, $direccion);

        if (!file_exists($direccion)) {
            $dom = new DOMDocument('1.0', 'utf-8');
            $xml = $dom->createElement("validador", '');
            $dom->appendChild($xml);
            $fs = fopen($direccion, "a+");
            $f = fputs($fs, $dom->saveXML());
            $f = fclose($fs);
        }
        if (!file_exists($direccion)) {
            $this->showMessage("Error en permisos de creacion de xml. Contacte al administrador");
            die;
        }

        $xml = simplexml_load_file($direccion);
        $validacion = $xml->children();
        $cantf = count($validacion);
        $arrayValidacion = array();

        if ($busqueda == '') {
            for ($cont = (int) $start; $cont < $cantf; $cont++) {
                $accion = $validacion[$cont]->children();
                $cantacc = count($accion);
                for ($i = 0; $i < $cantacc; $i++) {
                    $arregloval = $accion[$i]->children();
                    $canval = count($arregloval);
                    $item = array();
                    $item['controlador_accion'] = (string) $validacion[$cont]->getName() . '_' . $accion[$i]->getName();
                    $item['controlador'] = (string) $validacion[$cont]->getName();
                    $item['accion'] = (string) $accion[$i]->getName();
                    $p = 0;
                    for ($v = 0; $v < $canval; $v++) {
                        if ($arregloval[$v]->getName() == 'param') {
                            $pepe = array();
                            $pepe = $arregloval->param[$p];
                            $item['nombre'] = (string) $pepe['name'];
                            $item['nombre_tipo'] = (string) $pepe['type'];
                            $item['not_null'] = (string) $pepe['not_null'];
                            $item['null_error'] = (string) $pepe['null_error'];
                            $item['codigo'] = (string) $pepe['type_error'];
                            $canttotal++;
                            $p++;
                            $arrayValidacion[] = $item;
                        }
                    }
                }
            }
            $canttotal = $cantf;
        } else {
            for ($cont = (int) $start; $cont < (int) $start + (int) $limit && $cont < $cantf; $cont++) {
                $accion = $validacion[$cont]->children();
                $cantacc = count($accion);

                for ($i = 0; $i < $cantacc; $i++) {
                    $arregloval = $accion[$i]->children();
                    $canval = count($arregloval);
                    $item = array();
                    $item['controlador_accion'] = (string) $validacion[$cont]->getName() . '_' . $accion[$i]->getName();
                    $item['controlador'] = (string) $validacion[$cont]->getName();
                    $item['accion'] = (string) $accion[$i]->getName();
                    $p = 0;
                    for ($v = 0; $v < $canval; $v++) {
                        if ($arregloval[$v]->getName() == 'param') {
                            $pepe = array();
                            $pepe = $arregloval->param[$p];
                            $item['nombre'] = (string) $pepe['name'];
                            $item['nombre_tipo'] = (string) $pepe['type'];
                            $item['not_null'] = (string) $pepe['not_null'];
                            $item['null_error'] = (string) $pepe['null_error'];
                            $item['codigo'] = (string) $pepe['type_error'];
                            $p++;
                            if (stristr($item['nombre'], $busqueda)) {
                                $canttotal++;
                                $arrayValidacion[] = $item;
                            }
                        }
                    }
                }
            }
        }

        $limite = (int) $limit;
        $este = array();
        for ($aa = (int) $start; $aa < count($arrayValidacion); $aa++) {
            if ($limite > 0) {
                $este[] = $arrayValidacion[$aa];
                $limite--;
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

    public function cargarcombotipoAction() {
        $direccion = $this->pathReal();
        $direccion = explode("apps", $direccion);
        $direccion = $direccion[0] . "config" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "expressions.xml";
        $xml = simplexml_load_file($direccion);
        $expresiones = $xml->children();
        $cantexp = count($expresiones);
        $arrayExpresiones = array();
        $canttotal = 0;

        for ($i = 0; $i < $cantexp; $i++) {
            $item = array();
            $item['nombre_tipo'] = (string) ($expresiones[$i]->getName());
            $canttotal++;
            $arrayExpresiones[] = $item;
        }
        $result = array('cantidad_filas' => $canttotal, 'datos' => $arrayExpresiones);
        echo json_encode($result);
        return;
    }

    public function cargarcombotipoerrorAction() {
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

    public function cargarcombonullerrorAction() {
        $pFile = $this->_request->getPost('path');
        $direccion = $this->pathReal();
        $direccion = $direccion . $pFile . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "exception.xml";
        $direccion = str_replace('/', DIRECTORY_SEPARATOR, $direccion);

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
        $nombreclaseArray = explode(".",$this->_request->getPost('nombreclase'));
        $nombreclase = $nombreclaseArray[0];
        $nombremetodo = $this->_request->getPost('nombremetodo').'Action';
        $nombreval = $this->_request->getPost('nombre');
        $nombreTipo = $this->_request->getPost('nombre_tipo');
        $tipoerror = $this->_request->getPost('codigo');
        $notnull = $this->_request->getPost('not_null');
        $nullerror = $this->_request->getPost('null_error');
        $pFile = $this->_request->getPost('path');
        $dirsistema = $this->pathReal($pFile);
        $direccion = $dirsistema . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "validation.xml";
        $xml = simplexml_load_file($direccion);
        $val = new SimpleXMLElement($xml->asXml());
        $contr = $val->children();
        $cantcontr = count($contr);

        if ($cantcontr != 0) {
            $contrencontrado = false;
            for ($i = 0; $i < $cantcontr; $i++) {
                if ($contr[$i]->getName() == $nombreclase) {
                    $contrencontrado = true;
                    $accencontrado = false;
                    $acc = $contr[$i]->children();
                    $cantacc = count($acc);
                    for ($j = 0; $j < $cantacc; $j++) {
                        if ($acc[$j]->getName() == $nombremetodo) {
                            $accencontrado = true;
                            $valencont = false;
                            $varparam = $acc[$j]->children();
                            $cantparam = count($varparam);
                            for ($v = 0; $v < $cantparam; $v++) {
                                $atrib = $varparam[$v]->attributes();
                                $valxml = strtolower($atrib->name);
                                $valdenom = strtolower($nombreval);
                                if ($valxml == $valdenom) {
                                    $valencont = true;
                                    $this->showMessage('Esta validación ya existe en el método ' . $nombremetodo . '.');
                                }
                            }
                            if (!$valencont) {
                                $param = $val->$nombreclase->$nombremetodo->addChild('param');
                                $validator = $param->addAttribute('name', $nombreval);
                                $validator = $param->addAttribute('type', $nombreTipo);
                                $validator = $param->addAttribute('not_null', $notnull);
                                if ($notnull == true) {
                                    $validator = $param->addAttribute('null_error', $nullerror);
                                }
                                $validator = $param->addAttribute('type_error', $tipoerror);
                                $val->asXML($direccion);
                                $this->identarXml($direccion);
                                $this->showMessage('La validación fue insertada satisfactoriamente.');
                            }
                        }
                    }
                    if (!$accencontrado) {
                        $accion = $val->$nombreclase->addChild($nombremetodo);
                        $param = $accion->addChild('param');
                        $validator = $param->addAttribute('name', $nombreval);
                        $validator = $param->addAttribute('type', $nombreTipo);
                        $validator = $param->addAttribute('not_null', $notnull);
                        if ($notnull == true) {
                            $validator = $param->addAttribute('null_error', $nullerror);
                        }
                        $validator = $param->addAttribute('type_error', $tipoerror);
                        $val->asXML($direccion);
                        $this->identarXml($direccion);
                        $this->showMessage('La validación fue insertada satisfactoriamente.');
                    }
                }
            }
            if (!$contrencontrado) {
                $controlador = $val->addChild($nombreclase);
                $accion = $controlador->addChild($nombremetodo);
                $param = $accion->addChild('param');
                $validator = $param->addAttribute('name', $nombreval);
                $validator = $param->addAttribute('type', $nombreTipo);
                $validator = $param->addAttribute('not_null', $notnull);

                if ($notnull == true) {
                    $validator = $param->addAttribute('null_error', $nullerror);
                }
                $validator = $param->addAttribute('type_error', $tipoerror);
                $val->asXML($direccion);
                $this->identarXml($direccion);
                $this->showMessage('La validación fue insertada satisfactoriamente.');
            }
        } else {
            $controlador = $val->addChild($nombreclase);
            $accion = $controlador->addChild($nombremetodo);
            $param = $accion->addChild('param');
            $validator = $param->addAttribute('name', $nombreval);
            $validator = $param->addAttribute('type', $nombreTipo);
            $validator = $param->addAttribute('not_null', $notnull);
            if ($notnull == true) {
                $validator = $param->addAttribute('null_error', $nullerror);
            }
            $validator = $param->addAttribute('type_error', $tipoerror);
            $val->asXML($direccion);
            $this->identarXml($direccion);
            $this->showMessage('La validación fue insertada satisfactoriamente.');
        }
    }

    public function modificarvalidacionAction() {
        $nombresis = $this->_request->getPost('sistema');
        $pFile = $this->_request->getPost('path');
        $nombreclase = $this->_request->getPost('padresel');
        $nombremetodo = $this->_request->getPost('hijosel');
        $nombreval = $this->_request->getPost('nombre');
        $nombreTipo = $this->_request->getPost('nombre_tipo');
        $tipoerror = $this->_request->getPost('codigo');
        $notnull = $this->_request->getPost('not_null');
        $nullerror = $this->_request->getPost('null_error');
        $dirsistema = $this->pathReal();
        $nombresis = str_replace('//', DIRECTORY_SEPARATOR, $nombresis);
        $direccion = $dirsistema . $pFile . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "validation.xml";
        $xml = simplexml_load_file($direccion);
        $val = new SimpleXMLElement($xml->asXml());
        $contr = $val->children();
        $cantcontr = count($contr);

        $varval = false;

        for ($i = 0; $i < $cantcontr; $i++) {
            if ($contr[$i]->getName() == $nombreclase) {
                $acc = $contr[$i]->children();
                $cantacc = count($acc);
                for ($j = 0; $j < $cantacc; $j++) {
                    if ($acc[$j]->getName() == $nombremetodo) {
                        $valdato = $acc[$j]->param;
                        $cantvaldato = count($valdato);

                        for ($s = 0; $s < $cantvaldato; $s++) {
                            if ($valdato[$s]->attributes()->name == $nombreval) {
                                $varval = true;
                                $type = 'type';
                                $not_null = 'not_null';
                                $null_error = 'null_error';
                                $type_error = 'type_error';
                                $valdato[$s]->attributes()->$type = $nombreTipo;
                                $valdato[$s]->attributes()->$not_null = $notnull;
                                if ($notnull == true) {
                                    $valdato[$s]->attributes()->$null_error = $nullerror;
                                }
                                $valdato[$s]->attributes()->$type_error = $tipoerror;
                                $val->asXML($direccion);
                                $this->identarXml($direccion);
                                $this->showMessage('La validación fue modificada satisfactoriamente.');
                            }
                        }
                        if ($varval == false) {
                            $this->showMessage('Esta validación no existe.');
                        }
                    }
                }
            }
        }
    }

    public function eliminarvalidacionAction() {
        $nombreclase = $this->_request->getPost('padresel');
        $nombremetodo = $this->_request->getPost('hijosel');
        $nombre = $this->_request->getPost('nombreval');
        $pFile = $this->_request->getPost('path');
        $direccion = $this->pathReal($pFile);
        $direccion = $direccion . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "validation.xml";
        $direccion = str_replace('//', DIRECTORY_SEPARATOR, $direccion);
      // print_r($direccion);die;
      /*   $doc = new DOMDocument;
        $doc->load((string) $direccion);
        $controlador = $doc->documentElement->getElementsByTagName($nombreclase)->item(0);
      // print_r($controlador->children());die;

        $accion = $controlador->getElementsByTagName($nombremetodo)->item(0);
        // print_r($accion);die;
      //  $precondition = $accion->getElementsByTagName('precondition')->item(0);
        $validacion =  $accion->getElementsByTagName('param')->item(0);
        
        if ($validacion->getAttribute('name') == $nombre) {            
            $old = $accion->removeChild($validacion);
        }
        file_put_contents((string) $direccion, $doc->saveXML());
        $this->identarXml($direccion);*/
        
        
        $doc = simplexml_load_file($direccion);
        
        
        $params = $doc->$nombreclase->$nombremetodo;
        $cont=0;
        $params=$params->children();
        foreach ($params as $param) {
            
            if ($param['name'] == $nombre) {
                unset($params[$cont]);
                
            }
            $cont++;
        }
         $doc->asXML($direccion);
        
    }

}

?>
