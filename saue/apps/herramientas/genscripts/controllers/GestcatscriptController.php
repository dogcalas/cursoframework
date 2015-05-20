<?php

/**
 * @Copyright UCI
 * @Author Eidel Parada
 * @Author Rene Bauta
 * @Version 1.0
 */
class GestCatScriptController extends ZendExt_Controller_Secure {

    /**
     * 
     * @var DatScriptModel
     */
    private $_script_model;

    /**
     * 
     * @var ScriptManager
     */
    private $_script_manager;

    public function init() {
        parent::init();
        $this->_script_model = new DatScriptModel();
        $this->_script_manager = new ScriptManager();
    }

    public function gestCatScriptAction() {
        $this->render();
    }

    function pathReal($path = "") {
        $dirweb = $_SERVER[DOCUMENT_ROOT];
        $dirapps = str_replace('web', 'apps', $dirweb);
        $final = ($path == "") ? DIRECTORY_SEPARATOR : "";
        return $dirapps . $path . $final;
    }

    function xmlCnxPath() {
        return DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "conexion.xml";
    }

    protected function showMessage($codExc, $msg) {
        echo "{'codMsg':" . $codExc . ",'mensaje': '$msg'}";
    }

    function cargarScriptsAction() {
        $cant_filas = 0;
        $data = $this->_script_model->cargarScript();
        $result = array();
        foreach ($data as $key1 => $value) {
            foreach ($value as $key2 => $aux) {
                if (!is_int($key2))
                    $result[$key1][$key2] = $aux;
                if ($key2 == 'id_tiposcript' && $aux == 2)
                    $result[$key1][$key2] = 'estructura';
                else if ($key2 == 'id_tiposcript' && $aux == 3)
                    $result[$key1][$key2] = 'datos';
                else if ($key2 == 'id_tiposcript' && $aux == 4)
                    $result[$key1][$key2] = 'permiso';
            }
            $cant_filas++;
        }

        $result1 = array('cantidad_filas' => $cant_filas, 'datos' => $result);
        echo json_encode($result1);
        return;
    }

    function verScriptAction() {
        $id_script = $this->_request->getPost("id");
        $script = $this->_script_model->obtenerDefinicionScript($id_script);

        echo json_encode($script);
        return;
    }

    function eliminarAction() {
        $id_script = json_decode($this->_request->getPost('seleccion'));

        for ($i = 0; $i < count($id_script); $i++) {
            $this->_script_model->eliminar($id_script[$i]);
        }
        $this->showMessage(1, 'El script fue eliminado satisfactoriamente.');
    }

    function descargarAction() {
        $paquete = $this->_request->getPost("paquete");
        $tipo = $this->_request->getPost("tipo");
        $idtipo = 0;
        if ($tipo == 'estructura') {
            $idtipo = 2;
        } elseif ($tipo == 'datos') {
            $idtipo = 3;
        } else {
            $idtipo = 4;
        }
        $id_script = $this->_request->getPost("id");
        $script = $this->_script_model->obtenerDefinicionScript($id_script);

        $this->_script_manager->SalvarScript($paquete, $script, $idtipo);

        echo json_encode($script);
        return;
    }

}

?>