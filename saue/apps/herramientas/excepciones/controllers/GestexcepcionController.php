<?php

class GestexcepcionController extends ZendExt_Controller_Secure {

   public function init() {
        parent::init();
    }

    public function gestexcepcionAction() {
        $this->render();
    }
    //construye la direccion real de cualquier fichero dado su dir relativa

    function pathReal($path = "") {
        $dirweb = $_SERVER[DOCUMENT_ROOT];
        $dirapps = str_replace('web', 'apps', $dirweb);
        $final = ($path == "") ? DIRECTORY_SEPARATOR : "";
        return $dirapps . $path . $final;
    }
    
    /*function cargarsistemaAction() {
        $idnodo = $this->_request->getPost('node');
        $path = $this->_request->getPost('path');
        $arrayReturn = ExcepcionModel::cargarSistema($idnodo, $path);
        echo json_encode($arrayReturn);
    }*/
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
    
    function xmlPath() {
        return DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "components.xml";
    }
    
    function cargarsistemaAction() {
        $nodo = $this->_request->getPost('node');
        $path = $this->_request->getPost('path');
        $nombre = $this->_request->getPost('text');
        $abrev = $this->_request->getPost('abrev');
        $arreglo_auxiliar = array();
        if ($nodo == 0) {
            $register = Zend_Registry::getInstance();
            $dirModulesConfig = $register->config->xml->components;

            $xml = new SimpleXMLElement($dirModulesConfig, null, true);
        } else {
            $real_path = $this->pathReal($path);
            $xml_complete = simplexml_load_file($real_path . $this->xmlPath());
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

    function cargarexcepcionesAction() {
        $search_criteria = $this->_request->getPost('criteriobusqueda');
        $pathSubsistema = $this->_request->getPost('path');
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        if (empty($search_criteria) || $search_criteria == 'Ninguno') {
            $excepciones = ExcepcionModel::cargarExcepciones($pathSubsistema, $start, $limit);
            echo json_encode($excepciones);
        } else {
            $value = $this->_request->getPost('valor');
            $excepciones = ExcepcionModel::searchException($pathSubsistema,$start,$limit, $search_criteria, $value);
            echo json_encode($excepciones);
        }
    }

    function adicionarexcepcionAction() {
        $nombreExcepcion = $this->_request->getPost('nombre');
        $tipoExcepcion = $this->_request->getPost('tipo');
        $descripcionExcepcion = $this->_request->getPost('descripcion');
        $codigoExcepcion = $this->_request->getPost('codigo');
        $mensajeExcepcion = $this->_request->getPost('mensaje');

        $dirSubsistema = $this->_request->getPost('sistema');

        $added = ExcepcionModel::adicionarExcepcion($dirSubsistema, $codigoExcepcion, $nombreExcepcion, $tipoExcepcion, $descripcionExcepcion, $mensajeExcepcion);

        if ($added === TRUE) {
            $this->showMessage(1,'La excepción fue insertada satisfactoriamente.');
        } else {
            $this->showMessage(3,'Ya existe una excepción con ese código.');
        }
    }

    protected function showMessage($codExc, $msg) {
        echo "{'codMsg':".$codExc.",'mensaje': '$msg'}";
    }

    function modificarexcepcionAction() {
        $nombreExcepcion = $this->_request->getPost('nombre');
        $tipoExcepcion = $this->_request->getPost('tipo');
        $descripcionExcepcion = $this->_request->getPost('descripcion');
        $codigoExcepcion = $this->_request->getPost('codigo');
        $codigoAnterior = $this->_request->getPost('codigoanterior');
        $mensajeExcepcion = $this->_request->getPost('mensaje');
        $dirSistema = $this->_request->getPost('path');

        $changed = ExcepcionModel::modificarExcepcion($dirSistema, $codigoExcepcion,$codigoAnterior, $nombreExcepcion, $tipoExcepcion, $descripcionExcepcion, $mensajeExcepcion);

        if ($changed === TRUE) {
            $this->showMessage(1,'La excepción fue modificada satisfactoriamente.');
        }  else {
            $this->showMessage(3,'La excepción no fue modificada.');
        }
    }

    function eliminarexcepcionAction() {
        $dirSistema = $this->_request->getPost('path');
        $codigoExcepcion = $this->_request->getPost('codigoExcepcion');

        $deleted = ExcepcionModel::eliminarExcepcion($dirSistema, $codigoExcepcion);
        if($deleted === TRUE){
            $this->showMessage(1,'La excepción fue eliminada satisfactoriamente.');
        }  else {
            $this->showMessage(3,'La excepción no fue eliminada.');
        }
    }

}

?>
