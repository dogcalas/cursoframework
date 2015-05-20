<?php

class ExcepcionModel extends ZendExt_Model {
    public function cargarSistema($idnodo, $path) {
        if(!$idnodo){
            $register = Zend_Registry::getInstance();
            $dircomponent = $register->config->xml->components;
        }
        else{
            $dir_www = $_SERVER['DOCUMENT_ROOT'];
            $dir_rel_mt = '/';
            $dir_abs_mt = str_replace('//','/', dirname($dir_www . '..') . '/' . $dir_rel_mt);
            $dircomponent = $dir_abs_mt.'apps'.$path. DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR. "components.xml";
            
        }
        
        if (file_exists($dircomponent)) {
            $xmlFile = file_get_contents($dircomponent);
            $xmlDoc = new DOMDocument();
            $xmlDoc->loadXML($xmlFile);

            $xmlComponentPackage = $xmlDoc->documentElement;

            $arrayReturn = array();

            if ($xmlComponentPackage->hasChildNodes()) {
                $xmlComponents = $xmlComponentPackage->childNodes;
                $counter = 0;
                for ($i = 0; $i < $xmlComponents->length; $i++) {
                    $xmlComponent = $xmlComponents->item($i);

                      if ($xmlComponent->nodeType == 1) {
                        $id = $xmlComponents->item($i)->getAttributeNode('id')->value;
                        $text = $xmlComponents->item($i)->getAttributeNode('text')->value;
                        $path = $xmlComponents->item($i)->getAttributeNode('path')->value;
                        $arrayReturn[$counter]['id'] = $id;
                        $arrayReturn[$counter]['text'] = $text;
                        //echo($xmlComponents->item($i)->getAttributeNode('leaf')->value);
                        if($xmlComponents->item($i)->getAttributeNode('leaf')->value)
                            $arrayReturn[$counter]['leaf'] = true;
   
                        $arrayReturn[$counter]['path'] = $path;
                        $counter++;
                    }
                }
            }
            return $arrayReturn;
        } else {
            throw new Exception('No se encuentra el fichero.');
        }
    }

    private function cleanPath($dirSistema, &$justCreated) {
        $fileName = "exception.xml";    
        $dir_www = $_SERVER['DOCUMENT_ROOT'];
	$dir_rel_mt = '/';
	$dir_abs_mt = str_replace('//','/', dirname($dir_www . '..') . '/' . $dir_rel_mt);
        $direccion = $dir_abs_mt.'apps'.$dirSistema. DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR;     
        $justCreated = self::checkPath($direccion, $fileName);
        return $direccion . $fileName;
    }

    private function checkPath($direccion, $fileName) {
        $fileDir = $direccion . $fileName;        
        if (is_dir($direccion)) {
            if (file_exists($fileDir)) 
                return;
            else
            throw new ZendExt_Exception('EXC001');
        } else {
            throw new ZendExt_Exception('EXC001');
        }
    }

    public function cargarExcepciones($path, $start, $limit) {  
        $dir_www = $_SERVER['DOCUMENT_ROOT'];
	$dir_rel_mt = '/';
	$dir_abs_mt = str_replace('//','/', dirname($dir_www . '..') . '/' . $dir_rel_mt);
        $dirsistema = $dir_abs_mt.'apps'.$path. DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "exception.xml";
   
        if (!file_exists($dirsistema)) {
            //Creo el fichero xml
            $dom = new DOMDocument('1.0', 'utf-8');
            $xml = $dom->createElement("excepciones", '');
            $dom->appendChild($xml);
            $fs = fopen($dirsistema, "a+");
            $f = fputs($fs, $dom->saveXML());
            $f = fclose($fs);
        }
        if (file_exists($dirsistema)) {
            $excepciones = file_get_contents($dirsistema);
            $exce = simplexml_load_string($excepciones);
            $exceptionCount = count((array) $exce);
            $cont = 0;
            $key = 0;
            $suma = $limit + $start;
            if($exceptionCount){
                foreach ($exce as $pos=>$val) {
                    if($cont >= $start && $cont<$suma){
                        $arrayDatos[$key]['tipo'] = (string)$val->tipo;
                        $arrayDatos[$key]['codigo'] = (string)$pos;
                        $arrayDatos[$key]['mensaje'] = (string)$val->es->mensaje;
                        $arrayDatos[$key]['descripcion'] = (string)$val->es->descripcion;
                        if(isset($val['nombre']))
                                $arrayDatos[$key]['nombre'] = (string)$val['nombre'];                   
                        $key++;                     
                    }               
                $cont++; 
                }
                $result = array('cantidad_filas' => $exceptionCount, 'datos' => $arrayDatos);
                return $result;
            }
            else{
                $result = array('cantidad_filas' => 0, 'datos' => array());
                return $result;
            }
        }
        else
            throw new ZendExt_Exception('EXC001');          
    }

    private function getExceptionsAsXML($direccion, &$domDocumentRef = NULL, &$documentElementRef = NULL) {
        if (file_exists($direccion)) {
            $xmlFile = file_get_contents($direccion);
            
            $xmlDoc = new DOMDocument('1.0', 'UTF-8');
            $xmlDoc->loadXML($xmlFile);
                    
            $exceptions = $xmlDoc->documentElement;
            $excepciones = $exceptions->childNodes;

            if ($domDocumentRef != NULL && $documentElementRef != NULL) {
                $domDocumentRef = $xmlDoc;
                $documentElementRef = $exceptions;
            }
            return $excepciones;
        }
        return NULL;
    }

    private function readExceptionXMLElement($excepcion) {
        $item = array();
        if ($excepcion->nodeType == 1) {

            $codigo = $excepcion->tagName;
            $nombre = '';
            $tipo = NULL;
            $mensaje = NULL;
            $descripcion = NULL;

            if ($excepcion->hasAttribute('nombre')) {
                $nombre = $excepcion->getAttribute('nombre');
            }
            if ($excepcion->hasChildNodes()) {
                $subElements = $excepcion->childNodes;
                $countSubElements = 0;
                for ($i = 0; $i < $subElements->length; $i++) {
                    $element = $subElements->item($i);

                    if ($element instanceof DOMElement) {
                        $countSubElements++;
                        $tagName = $element->tagName;
                        switch ($tagName) {
                            case 'tipo':
                                $tipo = $element->nodeValue;
                                break;
                            case 'es':
                                $esChildren = $element->childNodes;
                                $faultHappened = false;
                                for ($j = 0; $j < $esChildren->length; $j++) {
                                    $esChildElement = $esChildren->item($j);
                                    if ($esChildElement instanceof DOMElement) {
                                        $esChildElementTagName = $esChildElement->tagName;
                                        switch ($esChildElementTagName) {
                                            case 'mensaje':
                                                $mensaje = $esChildElement->nodeValue;
                                                break;
                                            case 'descripcion':
                                                $descripcion = $esChildElement->nodeValue;
                                                break;
                                            default:
                                                $faultHappened = true;
                                                break;
                                        }
                                    }
                                }
                                if ($faultHappened) {
                                    throw new Exception('El fichero xml esta mal estructurado.');
                                }
                                break;
                            default:
                                break;
                        }
                    }
                }
                if ($countSubElements != 2) {
                    throw new Exception('El fichero xml esta mal estructurado.');
                } else {
                    $item['nombre'] = $nombre;
                    $item['codigo'] = $codigo;
                    $item['tipo'] = $tipo;
                    $item['mensaje'] = $mensaje;
                    $item['descripcion'] = $descripcion;
                    return $item;
                }
            } else {
                throw new Exception('El fichero xml esta mal estructurado.');
            }
        }
    }

    public function searchException($pathSubsistema, $start, $limit, $searchCriteria, $value = NULL, $strictSearch = FALSE) {
        $firstChar = $searchCriteria[0];
        $searchCriteria = str_replace($firstChar, strtolower($firstChar), $searchCriteria);
        $searchCriteria = self::replaceStressedChars($searchCriteria);
        $justCreated = FALSE;
        $path = self::cleanPath($pathSubsistema, &$justCreated);
        if (file_exists($path)) {
        $excepciones = file_get_contents($path);
        $xmlFile = simplexml_load_string($excepciones);
        $exceptionCount = count((array) $xmlFile);
        $cont = 0;
        $temp = 0;
        $cantFilas = 0;
        if($exceptionCount){
            foreach ($xmlFile as $key =>$val){

                if($searchCriteria == 'codigo')
                {
                    $pos = stristr($key, $value);                   
                    if($pos !==false)
                    {            
                        if($cont >= $start && $temp< $limit)
                        {
                            $arrayResult[$temp]['codigo'] = (string)$key;
                            $arrayResult[$temp]['tipo'] = (string)$val->tipo;
                            $arrayResult[$temp]['mensaje'] = (string)$val->es->mensaje;
                            $arrayResult[$temp]['descripcion'] = (string)$val->es->descripcion;
                            if(isset($val['nombre']))
                                $arrayResult[$temp]['nombre'] = (string)$val['nombre'];
                        $temp++;
                       }
                       $cantFilas++;
                    }
                }
                elseif($searchCriteria == 'nombre'){                   
                    if(isset($val['nombre']))
                    {
                        $valor = $val['nombre'];
                        $pos = stristr($valor, $value);                    
                        if($pos !==false)
                        {
                            if($cont >= $start && $temp< $limit)
                            {
                                $arrayResult[$temp]['codigo'] = (string)$key;
                                $arrayResult[$temp]['tipo'] = (string)$val->tipo;
                                $arrayResult[$temp]['mensaje'] = (string)$val->es->mensaje;
                                $arrayResult[$temp]['descripcion'] = (string)$val->es->descripcion;
                                if(isset($val['nombre']))
                                    $arrayResult[$temp]['nombre'] = (string)$val['nombre'];
                            $temp++;
                            }
                            $cantFilas++; 
                        }
                    }
                }
                elseif($searchCriteria == 'descripcion')
                {
                    if($val->es->descripcion)
                    {
                        $valor = $val->es->descripcion;
                        $pos = stristr($valor, $value);
                        if($pos !==false)                          
                        {
                            if($cont >= $start && $temp< $limit)
                            {
                                $arrayResult[$temp]['codigo'] = (string)$key;
                                $arrayResult[$temp]['tipo'] = (string)$val->tipo;
                                $arrayResult[$temp]['mensaje'] = (string)$val->es->mensaje;
                                $arrayResult[$temp]['descripcion'] = (string)$val->es->descripcion;
                                if(isset($val['nombre']))
                                    $arrayResult[$temp]['nombre'] = (string)$val['nombre'];
                            $temp++;
                            }
                            $cantFilas++; 
                        }
                    }
                }
                elseif($searchCriteria == 'mensaje')
                {
                    if($val->es->mensaje)
                    {
                        $valor = $val->es->mensaje;
                        $pos = stristr($valor, $value);
                        if($pos !==false)                          
                        {
                            if($cont >= $start && $temp< $limit)
                            {
                                $arrayResult[$temp]['codigo'] = (string)$key;
                                $arrayResult[$temp]['tipo'] = (string)$val->tipo;
                                $arrayResult[$temp]['mensaje'] = (string)$val->es->mensaje;
                                $arrayResult[$temp]['descripcion'] = (string)$val->es->descripcion;
                                if(isset($val['nombre']))
                                    $arrayResult[$temp]['nombre'] = (string)$val['nombre'];
                            $temp++;
                            }
                            $cantFilas++; 
                        }
                    }
                }
            $cont++; 
            }
            $result = array('cantidad_filas' => $cantFilas, 'datos' => $arrayResult);
            return $result;
        }
        else
        {
            $result = array('cantidad_filas' => 0, 'datos' => array());
            return $result;  
        }
       
        }
        else
           throw new ZendExt_Exception('EXC001'); 
    }
    
    private function replaceStressedChars($stringValue){
        $specialChars = array('á' => 'a', 'é' => 'e',
                              'í' => 'i', 'ó' => 'o',
                              'ú' => 'u');

        foreach ($specialChars as $key => $value) {
            $charPos = strpos($stringValue, $key);
            if($charPos !== FALSE){
                $stringValue = str_replace($key, $value, $stringValue);
            }
        }
        return $stringValue;
	}

    public function adicionarExcepcion($dirSistema, $codigo, $nombre, $tipo, $descripcion, $msg) {
        
        if (self::verifyCode($dirSistema,$codigo)) {
            return FALSE;
        } else {
            $justCreated = FALSE;
            $direccion = self::cleanPath($dirSistema, &$justCreated);

            $domDocumentRef = -1;
            $documentElementRef = -1;
            self::getExceptionsAsXML($direccion, &$domDocumentRef, &$documentElementRef);
            if ($domDocumentRef != NULL && $documentElementRef != NULL) {
                $excElement = $domDocumentRef->createElement($codigo);
                $excElement->setAttribute('nombre', $nombre);
                $tipoElement = $domDocumentRef->createElement('tipo', $tipo);
                $es = $domDocumentRef->createElement('es');
                $mensaje = $domDocumentRef->createElement('mensaje', $msg);
                $descripcionEl = $domDocumentRef->createElement('descripcion', $descripcion);
                $es->appendChild($mensaje);
                $es->appendChild($descripcionEl);
                $excElement->appendChild($tipoElement);
                $excElement->appendChild($es);
                $documentElementRef->appendChild($excElement);
                $domDocumentRef->save($direccion);
                self::identarXml($direccion);
                return TRUE;
            }
        }
    }

    public function modificarExcepcion($dirSistema, $codigoExcepcion, $codigoAnterior, $nombre, $tipoValue, $descripcionExcepcion, $mensajeExcepcion) {
        
        $domDocumentRef = -1;
        $documentElementRef = -1;
        if($codigoExcepcion != $codigoAnterior){
            if (self::verifyCode($dirSistema,$codigoExcepcion))        
               throw new ZendExt_Exception('EXC002'); 
        }
        $direccion = self::cleanPath($dirSistema);
        $exceptions = new SimpleXMLElement($direccion, null, true);
        foreach($exceptions as $key=>$value){ 
            if((string)$key == $codigoAnterior){
                if($codigoExcepcion != $codigoAnterior){
                    $domRef = dom_import_simplexml($value);    
                    $domRef->parentNode->removeChild($domRef);
                    $newExeption = $exceptions->addChild($codigoExcepcion);
                    $newExeption->addAttribute('nombre', $nombre);
                    $newExeption->addChild('tipo');
                    $newExeption->tipo = $tipoValue;
                    $es = $newExeption->addChild('es');
                    $es->addChild('mensaje');
                    $es->mensaje = $mensajeExcepcion;
                    $es->addChild('descripcion');
                    $es->descripcion = $descripcionExcepcion;
                }
                else{
                    if (isset($value['nombre']))
                        $value['nombre'] = $nombre;
                    else
                        $value->addAttribute('nombre', $nombre);

                    $value->tipo = $tipoValue; 
                    $value->es->mensaje = $mensajeExcepcion;
                    $value->es->descripcion = $descripcionExcepcion;               
                } 
            }
        }
        $exceptions->asXML($direccion);    
        self::identarXml($direccion);
        return true;
    }
    
    public function eliminarExcepcion($dirSistema, $codigoExcepcion) {
        $direccion = self::cleanPath($dirSistema);
        $domDocumentRef = -1;
        $documentElementRef = -1;
        $removed = FALSE;
        $exceptions = self::getExceptionsAsXML($direccion, &$domDocumentRef, &$documentElementRef);
        $exceptionCount = $exceptions->length;
        for($i = 0; $i < $exceptionCount; $i++){
            $xmlException = $exceptions->item($i);
            $exceptionItem = self::readExceptionXMLElement($xmlException);
            if($exceptionItem['codigo'] == $codigoExcepcion){
                $documentElementRef->removeChild($xmlException);  
                $removed = TRUE;
                break;
            }
        }
        if($removed){
            $domDocumentRef->save($direccion);
        }
        return $removed;
    }
    private function identarXml($direccion){
        $xml = new DOMDocument();
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        $xml->load($direccion);
        $xml->save($direccion);
        }
        
     private function verifyCode($dirSistema, $codigo){

        $path = self::cleanPath($dirSistema); 
        if (file_exists($path)) {
            $excepciones = file_get_contents($path);
            $xmlFile = simplexml_load_string($excepciones);
            $exceptionCount = count((array) $xmlFile);
            $bandera = false;
            if($exceptionCount){
                foreach ($xmlFile as $key =>$val){
                        if((string)$key ===$codigo){  
                        $bandera = true; 
                            return $bandera;
                        }
                    }    
                return $bandera;
            }
            else
                return false;           
        }   
        else 
            throw new ZendExt_Exception('EXC001');    
    }
}

?>
