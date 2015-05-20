<?php

/**
 * @Copyright UCI
 * @Author Eidel Parada
 * @Author Rene Bauta
 * @Version 1.0
 */
class GestGenScriptsController extends ZendExt_Controller_Secure {

    /**
     * 
     * @var Script_Manager
     */
    private $_script_manager;

    /**
     * 
     * @var DatScriptModel
     */
    private $_script_model;

    public function init() {
        parent::init();
        $this->_script_manager = new ScriptManager();
        $this->_script_model = new DatScriptModel();
    }

    public function gestGenScriptsAction() {
        $this->render();
    }

    function pathReal($path = "") {
        $dirweb = $_SERVER[DOCUMENT_ROOT];
        $dirapps = str_replace('web', 'apps', $dirweb);
        $final = ($path == "") ? DIRECTORY_SEPARATOR : "";
        return $dirapps . $path . $final;
    }

    function xmlScPath() {
        return DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "paquetesScript.xml";
    }

    function xmlCnxPath() {
        return DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "conexion.xml";
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

    function cargarpaquetesAction() {
        $nodo = $this->_request->getPost('node');
        $array_return = array();
        $arreglo_auxiliar = array();
        if ($nodo == '0') {
            $path = DIRECTORY_SEPARATOR . "herramientas" . DIRECTORY_SEPARATOR . "genscripts";
            $dir = $this->pathReal($path) . $this->xmlScPath();
            $xml = simplexml_load_file($dir);
            $cont = 0;
            $paquetes = $xml->children();
            foreach ($paquetes as $var) {
                $arreglo_auxiliar['text'] = (string) $var->nombrePaquete;
                $arreglo_auxiliar['leaf'] = false;
                $arreglo_auxiliar['id'] = (string) $var->versionSistema . (string) $var->nombrePaquete;
                $arreglo_auxiliar['nombreS'] = (string) $var->nombreSistema;
                $arreglo_auxiliar['abrev'] = (string) $var->abreviaturaPaquete;
                $arreglo_auxiliar['versionSc'] = (string) $var->versionScript;
                $array_return[$cont] = $arreglo_auxiliar;
                $cont++;
            }
            echo json_encode($array_return);
            return;
        } else {
            $arreglo_auxiliar['leaf'] = true;
            $arreglo_auxiliar['text'] = 'Estructura';
            $arreglo_auxiliar['id'] = $nodo . 'est';
            $array_return[0] = $arreglo_auxiliar;
            $arreglo_auxiliar['leaf'] = true;
            $arreglo_auxiliar['text'] = 'Datos';
            $arreglo_auxiliar['id'] = $nodo . 'dat';
            $array_return[1] = $arreglo_auxiliar;
            $arreglo_auxiliar['leaf'] = true;
            $arreglo_auxiliar['text'] = 'Permisos';
            $arreglo_auxiliar['id'] = $nodo . 'per';
            $array_return[2] = $arreglo_auxiliar;
            echo json_encode($array_return);
            return;
        }
    }

    function adicionarpaqueteAction() {
        $nombrePaquete = $this->_request->getPost('nombre');
        $nombreSistema = $this->_request->getPost('nombreS');
        $versionPaquete = $this->_request->getPost('versionSis');
        $versionScript = $this->_request->getPost('versionSc');
        $abrev = $this->_request->getPost('abrev');
        $descripcionPaquete = $this->_request->getPost('descripcion');
        $path = DIRECTORY_SEPARATOR . "herramientas" . DIRECTORY_SEPARATOR . "genscripts";
        $dir = $this->pathReal($path) . $this->xmlScPath();
        $encontrado = FALSE;

        try {
            $paquetes = simplexml_load_file($dir);
            $paquete1 = $paquetes->children();
            foreach ($paquete1 as $var) {
                if ($var->nombrePaquete == $nombrePaquete && $var->versionSistema == $versionPaquete) {
                    $encontrado = TRUE;
                }
            }
            if (!$encontrado) {
                $paquete = $paquetes->addChild('paquete');
                $paquete->addChild('nombrePaquete', $nombrePaquete);                
                $paquete->addChild('nombreSistema', $nombreSistema);
                $paquete->addChild('versionSistema', $versionPaquete);
                $paquete->addChild('versionScript', $versionScript);
                $paquete->addChild('descripcion', $descripcionPaquete);                
                $paquete->addChild('abreviaturaPaquete', $abrev);
                $paquetes->asXML($dir);
                $xml = new DOMDocument();
                $xml->preserveWhiteSpace = false;
                $xml->formatOutput = true;
                $xml->load($dir);
                $xml->save($dir);
                $this->showMessage(1, 'El paquete fue registrado satisfactoriamente.');
            }
            else
                $this->showMessage(3, 'Ya exsiste un paquete con el nombre y versi&oacute;n especificados.');
        } catch (Exception $e) {
            $this->showMessage(3, 'Error al registrar el paquete.');
        }
    }

    protected function showMessage($codExc, $msg) {
        echo "{'codMsg':" . $codExc . ",'mensaje': '$msg'}";
    }

    function modificarPaqueteAction() {
        $nombrePaquete = $this->_request->getPost('nombre');
        $nombreSistema = $this->_request->getPost('nombreS');
        $versionPaquete = $this->_request->getPost('versionSis');
        $versionScript = $this->_request->getPost('versionSc');
        $abrev = $this->_request->getPost('abrev');
        $descripcionPaquete = $this->_request->getPost('descripcion');              
        $nombre_paquete = $this->_request->getPost('nombre_paquete');
        $id_paquete = $this->_request->getPost('id_paquete');

        $path = DIRECTORY_SEPARATOR . "herramientas" . DIRECTORY_SEPARATOR . "genscripts";
        $dir = $this->pathReal($path) . $this->xmlScPath();
        $paquetes = simplexml_load_file($dir);
        $paquete = $paquetes->children();
        try {
            foreach ($paquete as $var) {
                if ($var->nombrePaquete == $nombre_paquete && $var->versionSistema == ($id_paquete - $nombre_paquete)) {
                    $var->nombrePaquete = $nombrePaquete;
                    $var->nombreSistema = $nombreSistema;
                    $var->versionSistema = $versionPaquete;
                    $var->versionScript = $versionScript;
                    $var->descripcion = $descripcionPaquete;
                    $var->abreviatura = $abrev;
                }
            }
            $paquetes->asXML($dir);
            $xml = new DOMDocument();
            $xml->preserveWhiteSpace = false;
            $xml->formatOutput = true;
            $xml->load($dir);
            $xml->save($dir);
            $this->showMessage(1, 'El paquete fue modificado satisfactoriamente.');
        } catch (Exception $exc) {
            $this->showMessage(3, 'El paquete no pudo ser modificado.');
        }
    }

    function eliminarpaqueteAction() {
        $nombre_paquete = $this->_request->getPost('nombre_paquete');
        $id_paquete = $this->_request->getPost('id_paquete');

        $path = DIRECTORY_SEPARATOR . "herramientas" . DIRECTORY_SEPARATOR . "genscripts";
        $dir = $this->pathReal($path) . $this->xmlScPath();
        $paquetes = simplexml_load_file($dir);
        $paquete = $paquetes->children();
        try {
            $cont = 0;
            foreach ($paquete as $var) {
                if ($var->nombrePaquete == $nombre_paquete && $var->versionSistema == ($id_paquete - $nombre_paquete)) {
                    unset($paquete[$cont]);
                    if (count($paquete) == 0)
                        break;
                }
                $cont++;
            }
            $paquetes->asXML($dir);
            $xml = new DOMDocument();
            $xml->preserveWhiteSpace = false;
            $xml->formatOutput = true;
            $xml->load($dir);
            $xml->save($dir);
            if (is_dir($nombre_paquete)){
                $file = new ZendExt_File();
                $file->rm($nombre_paquete);
            }
            $this->showMessage(1, 'El paquete fue eliminado satisfactoriamente.');
        } catch (Exception $e) {
            $this->showMessage(3, 'El paquete no pudo ser eliminado.');
        }
    }

    function cargarbdAction() {
        $nodo = $this->_request->getPost('node');
        $text = $this->_request->getPost('texto');
        $array_return = array();
        $arreglo_auxiliar = array();
        $this->_script_manager = new ScriptManager();
        $rsa = new ZendExt_RSA_Facade();
        $path = DIRECTORY_SEPARATOR . "herramientas" . DIRECTORY_SEPARATOR . "genscripts";
        $dir = $this->pathReal($path) . $this->xmlCnxPath();
        $xml = simplexml_load_file($dir);

        $datConn = $xml->children();
        $host = (string) $datConn->host;
        $port = (string) $datConn->port;
        $user = (string) $datConn->user;
        $pass = (string) $rsa->decrypt($datConn->pass);
        $dbname = (string) $datConn->dbname;
        try {
            $conn = new Connection($host, $port, $dbname, $user, $pass);
            $schemas = $conn->schemasAll();

            $cont = 0;
            $idSchema = array();
            foreach ($schemas as $var) {
                $idSchema[$cont] = (string) $var['table_schema'];
                $cont++;
            }

            $cont = 0;
            $schema = preg_split("/\^/", $nodo);

            if ($nodo == '0') {
                foreach ($schemas as $var) {
                    $arreglo_auxiliar['text'] = (string) $var['table_schema'];
                    $arreglo_auxiliar['leaf'] = false;
                    $arreglo_auxiliar['id'] = (string) $var['table_schema'];
                    $array_return[$cont] = $arreglo_auxiliar;
                    $cont++;
                }
            } else {
                switch ($text) {
                    case "Estructura":
                        if (stripos($nodo, '^tables') == false && stripos($nodo, '^functions') == false && stripos($nodo, '^triggers') == false && stripos($nodo, '^index') == false && stripos($nodo, '^restrictions') == false && stripos($nodo, '^sequences') == false && stripos($nodo, '^types') == false) {
                            $array_return = $this->_script_manager->ScriptEstructura($schemas, $nodo);
                        } else if (stripos($nodo, '^tables') == strlen($nodo) - 7) {
                            $tablas = $conn->TableForSchema($schema[0]);
                            $array_return = $this->_script_manager->Tables($tablas, $schema[0], $text);
                        } else if (stripos($nodo, '^functions') == strlen($nodo) - 10) {
                            $funciones = $conn->FunctionForSchema($schema[0]);
                            $array_return = $this->_script_manager->Functions($funciones, $schema[0]);
                        } else if (stripos($nodo, '^triggers') == strlen($nodo) - 9) {
                            $disparadores = $conn->TriggerForSchema($schema[0]);
                            $array_return = $this->_script_manager->Triggers($disparadores, $schema[0]);
                        } else if (stripos($nodo, '^index') == strlen($nodo) - 6) {
                            $indices = $conn->IndexForSchema($schema[0]);
                            $array_return = $this->_script_manager->Indexs($indices, $schema[0]);
                        } else if (stripos($nodo, '^types') == strlen($nodo) - 6) {
                            $types = $conn->SelectTypesForSchema($schema[0]);                            
                            $array_return = $this->_script_manager->Types($types, $schema[0]);
                        }else if (stripos($nodo, '^sequences') == strlen($nodo) - 10) {
                            $secuencias = $conn->SequencesForSchema($schema[0]);
                            $array_return = $this->_script_manager->Sequences($secuencias, $schema[0]);
                        }
                        break;

                    case "Datos":
                        $tablas = $conn->TableForSchema($nodo);
                        $array_return = $this->_script_manager->Tables($tablas, $nodo, $text);
                        break;

                    case "Permisos":
                        if (stripos($nodo, '^functions') == false && stripos($nodo, '^sequences') == false && stripos($nodo, '^tables') == false && stripos($nodo, '^triggers') == false && stripos($nodo, '^types') == false) {
                            $array_return = $this->_script_manager->ScriptPermisos($schemas, $nodo);
                        } else if (stripos($nodo, '^tables') == true) {
                            $tablas = $conn->TableForSchema($schema[0]);
                            $array_return = $this->_script_manager->Tables($tablas, $schema[0], $text);
                        } else if (stripos($nodo, '^functions') == true) {
                            $funciones = $conn->FunctionForSchema($schema[0]);
                            $array_return = $this->_script_manager->Functions($funciones, $schema[0]);
                        } else if (stripos($nodo, '^types') == true) {
                            $types = $conn->SelectTypesForSchema($schema[0]);                            
                            $array_return = $this->_script_manager->Types($types, $schema[0]);
                        } else if (stripos($nodo, '^sequences') == true) {
                            $secuencias = $conn->SequencesForSchema($schema[0]);
                            $array_return = $this->_script_manager->Sequences($secuencias, $schema[0]);
                        }
                        break;
                }
            }
            echo json_encode($array_return);
            return;
        } catch (Exception $exc) {
            echo "{'codMsg':3,'mensaje':'No se pudo establecer la conexi&oacute;n con el servidor de datos.'}";
            return false;
        }
    }

    function generarScriptAction() {
        $tipo = $this->_request->getPost('tipo');
        $paquete = $this->_request->getPost('paquete');
        $checked = json_decode($this->_request->getPost('array'));
        $id = $this->_request->getPost('version');
        $rsa = new ZendExt_RSA_Facade();
        $version_script = "";
        for ($i = 0; $i < stripos($id, $paquete); $i++) {
            $version_script = $version_script . $id[$i];
        }
        $path = DIRECTORY_SEPARATOR . "herramientas" . DIRECTORY_SEPARATOR . "genscripts";
        $dir = $this->pathReal($path) . $this->xmlCnxPath();
        $xml = simplexml_load_file($dir);
        $datConn = $xml->children();
        $host = (string) $datConn->host;
        $port = (string) $datConn->port;
        $user = (string) $datConn->user;
        $pass = (string) $rsa->decrypt($datConn->pass);
        $dbname = (string) $datConn->dbname;
        try {
            $conn = new Connection($host, $port, $dbname, $user, $pass);

            if ($tipo == 'Estructura') {
                $script = $this->_script_model->Header($version_script, 'ESTRUCTURA');
                $esquemas = array();
                $j = 0;
                foreach ($checked as $var) {
                    $schema = preg_split("/\^/", $var[0]);
                    if (!in_array($schema[0], $esquemas)) {
                        $esquemas[$j] = $schema[0];
                        $j++;
                    }
                }
                //cabecera del script            
                $script = $script . $this->_script_model->Defautl($esquemas);
                //creacion de secuencias
                $script = $script . $this->_script_manager->HeadersScEst('secuencias');
                $script = $script . $this->_script_model->SqlSequenceCreate($checked, $conn);
                //creacion de tablas
                $script = $script . $this->_script_manager->HeadersScEst('tablas');
                $script = $script . $this->_script_model->SqlTableCreate($checked, $conn);
                //creacion de llaves primarias
                $script = $script . $this->_script_manager->HeadersScEst('llaves primarias');
                $script = $script . $this->_script_model->SqlPkCreate($checked, $conn);
                //creacion de otras restricciones de datos
                $script = $script . $this->_script_manager->HeadersScEst('otras restricciones de datos');
                $script = $script . $this->_script_model->SqlRestricCreate($checked, $conn);
                //creacion de indices
                $script = $script . $this->_script_manager->HeadersScEst('indices');
                $script = $script . $this->_script_model->SqlIndexCreate($checked, $conn);
                //creacion de tipos de datos
                $script = $script . $this->_script_manager->HeadersScEst('tipos de datos');
                $script = $script . $this->_script_model->SqlTypesCreate($checked, $conn);
                //creacion de llaves foraneas
                $script = $script . $this->_script_manager->HeadersScEst('llaves foraneas');
                $script = $script . $this->_script_model->SqlFkCreate($checked, $conn);
                //creacion de funciones
                $script = $script . $this->_script_manager->HeadersScEst('funciones');
                $script = $script . $this->_script_model->SqlFunctionCreate($checked, $conn);
                //creacion de disparadores
                $script = $script . $this->_script_manager->HeadersScEst('triggers');
                $script = $script . $this->_script_model->SqlTriggerCreate($checked, $conn);
                //cierre del script
                $script = $script . $this->_script_manager->Footer();
            } else if ($tipo == 'Permisos') {
                $script = $this->_script_model->Header($version_script, 'PERMISOS');
                //duenno y permisos de los esquemas
                $script = $script . $this->_script_manager->HeadersScPer('duenno y permisos de esquemas');
                $script = $script . $this->_script_model->SqlGrantAlterSchema($checked);
                //permisos sobre las funciones
                $script = $script . $this->_script_manager->HeadersScPer('permisos sobre funciones');
                $script = $script . $this->_script_model->SqlGrantFunction($checked, $conn);
                //permisos sobre las secuencias
                $script = $script . $this->_script_manager->HeadersScPer('permisos sobre secuencias');
                $script = $script . $this->_script_model->SqlGrantSequences($checked);
                //permisos sobre las tablas
                $script = $script . $this->_script_manager->HeadersScPer('permisos sobre tablas');
                $script = $script . $this->_script_model->SqlGrantTables($checked);
                //permisos sobre tipos de datos
                $script = $script . $this->_script_manager->HeadersScPer('tipos de datos');
//                $script = $script . $this->_script_model->SqlTypesCreate($checked, $conn);
                //duenno de funciones
                $script = $script . $this->_script_manager->HeadersScPer('duenno de funciones');
                $script = $script . $this->_script_model->SqlAlterFunction($checked, $conn);
                //duenno de tablas y secuencias
                $script = $script . $this->_script_manager->HeadersScPer('duenno de tablas y secuencias');
                $script = $script . $this->_script_model->SqlAlterTablesSequences($checked);
                //cierre del script
                $script = $script . $this->_script_manager->Footer();
            } else if ($tipo == 'Datos') {
                $script = $this->_script_model->Header($version_script, 'DATOS INICIALES');
                foreach ($checked as $var) {
                    $place = preg_split("/\^/", $var[0]);
                    $schema = $place[0];
                    $table = $place[1];
                    $script = $script . $this->_script_manager->HeadersScDat($schema, $table);
                    $script = $script . "\nSET search_path = $schema, pg_catalog; \n\n";
                    $script = $script . $this->_script_model->SqlInsertData($var, $schema, $table, $conn);
                    $script = $script . "\n";
                }
                $script = $script . $this->_script_manager->Footer();
            }
            $idtipo = 0;
            if ($tipo == 'Estructura') {
                $idtipo = 2;
            } elseif ($tipo == 'Datos') {
                $idtipo = 3;
            } else {
                $idtipo = 4;
            }
            
            //persistir en el catalogo evidencia de script generado            
            $flag = $this->PersistScript($paquete, $version_script, $script, $idtipo);
            
            if ($flag) {
                $this->showMessage(1, 'El script fue generado satisfactoriamente.');
            }
        } catch (Exception $exc) {
            echo "{'codMsg':3,'mensaje':'No se pudo establecer la conexi&oacute;n con el servidor de datos.'}";
            return false;
        }
    }

    function PersistScript($paquete, $version_script, $script, $tipo) {
        $register = Zend_Registry::getInstance();
        $user = $register->session->perfil['usuario'];
        $fecha = date('d/m/Y');
        $ip = $this->devolverIp();
        //creo el objeto
        $sc = new DatScript();        
        $sc->version_script = $version_script;
        $sc->nombre_paquete = $paquete;
        $sc->nombre_sistema = $paquete;
        $sc->version_sistema = $version_script;
        $sc->id_tiposcript = $tipo;
        $sc->usuario = $user;
        $sc->fecha = $fecha;
        $sc->ip_host = $ip;
        $sc->definicionsql = $script;
        $sc->nombre_script = $tipo . "-" . $paquete.".sql";

        //persisto el objeto con Doctrine
        $id_script = $this->_script_model->insertar($sc);
die($id_script);
        if ($id_script) {
            //salvar el script
            $this->_script_manager->SalvarScript($paquete, $script, $tipo);
            return true;
        } else {
            $this->showMessage(3, 'Ya exsiste en este paquete un script con esa versi&oacute;n.');
            return false;
        }
    }

    function establecerconexionAction() {
        $host = $this->_request->getPost('host');
        $dbname = $this->_request->getPost('db');
        $user = $this->_request->getPost('user');
        $psw = $this->_request->getPost('passwd');
        $port = $this->_request->getPost('port');
        $rsa = new ZendExt_RSA_Facade();
        $pass = $rsa->encrypt($this->getRequest()->getPost('passwd'));

        $path = DIRECTORY_SEPARATOR . "herramientas" . DIRECTORY_SEPARATOR . "genscripts";
        $dir = $this->pathReal($path) . $this->xmlCnxPath();
        $conexionXML = simplexml_load_file($dir);
        $url = array('sussess' => true);
        $conexionXML->host = "";
        $conexionXML->dbname = "";
        $conexionXML->user = "";
        $conexionXML->pass = "";
        $conexionXML->port = "";
        $conexionXML->asXML($dir);
        $xml = new DOMDocument();
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        $xml->load($dir);
        $xml->save($dir);

        try {
            new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $psw);
            $conexionXML->host = $host;
            $conexionXML->dbname = $dbname;
            $conexionXML->user = $user;
            $conexionXML->pass = $pass;
            $conexionXML->port = $port;
            $conexionXML->asXML($dir);
            $xml->preserveWhiteSpace = false;
            $xml->formatOutput = true;
            $xml->load($dir);
            $xml->save($dir);
            echo json_encode($url);
        } catch (Exception $e) {
            echo "{'codMsg':3,'mensaje':'No se pudo establecer la conexi&oacute;n con el servidor de datos.'}";
            return false;
        }
    }

    function configridObjetosAction() {
        $gridDinamico = array('grid' => array('columns' => array()));
        $tabla = $this->_request->getPost('tabla');
        $table = preg_split("/\^/", $tabla);
        $path = DIRECTORY_SEPARATOR . "herramientas" . DIRECTORY_SEPARATOR . "genscripts";
        $dir = $this->pathReal($path) . $this->xmlCnxPath();
        $xml = simplexml_load_file($dir);
        $rsa = new ZendExt_RSA_Facade();

        $datConn = $xml->children();
        $host = (string) $datConn->host;
        $port = (string) $datConn->port;
        $user = (string) $datConn->user;
        $pass = (string) $rsa->decrypt($datConn->pass);
        $dbname = (string) $datConn->dbname;
        try {
            $conn = new Connection($host, $port, $dbname, $user, $pass);
            $fields = $conn->SelectFieldsForTable($table[1]);

            foreach ($fields as $field) {
                $header = ucwords($field['column_name']);
                $gridDinamico ['grid'] ['columns'] [] = array('header' => $header,
                    'sortable' => true, 'dataIndex' => $field['column_name'], 'editor' => false, 'flex' => 1);
                $gridDinamico ['grid'] ['campos'] [] = $field['column_name'];
            }

            $gridDinamico ['grid'] ['columns'] [] = array('header' => 'Primary_Key',
                'width' => 150,'hidden' => true, 'sortable' => true, 'dataIndex' => 'primary_key', 'editor' => false);
            $gridDinamico ['grid'] ['campos'] [] = 'primary_key';

            $v1 = 0;
            $v2 = count($gridDinamico['grid']['campos']) - 1;

            while ($v2 - $v1 >= 1) {
                $aux1 = $gridDinamico['grid']['campos'][$v1];
                $aux2 = $gridDinamico['grid']['campos'][$v2];
                $gridDinamico['grid']['campos'][$v1] = $aux2;
                $gridDinamico['grid']['campos'][$v2] = $aux1;
                $v1++;
                $v2--;
            }
            echo json_encode($gridDinamico);
        } catch (Exception $e) {
            echo "{'codMsg':3,'mensaje':'No se pudo establecer la conexi&oacute;n con el servidor de datos.'}";
            return false;
        }
    }

    function cargargridObjetosAction() {
        $tabla = $this->_request->getPost('tabla');
        $table = preg_split("/\^/", $tabla);
        $rsa = new ZendExt_RSA_Facade();
        $path = DIRECTORY_SEPARATOR . "herramientas" . DIRECTORY_SEPARATOR . "genscripts";
        $dir = $this->pathReal($path) . $this->xmlCnxPath();
        $xml = simplexml_load_file($dir);

        $datConn = $xml->children();
        $host = (string) $datConn->host;
        $port = (string) $datConn->port;
        $user = (string) $datConn->user;
        $pass = (string) $rsa->decrypt($datConn->pass);
        $dbname = (string) $datConn->dbname;
        try {
            $conn = new Connection($host, $port, $dbname, $user, $pass);
            $data = $conn->SelectAllData($table[0], $table[1]);
            $key = $conn->SelectPrimaryKey($table[1]);
            $llave = $key[0]['columna'];
            $id = $conn->SelectPkeyData($llave, $table[0], $table[1]);

            $datos = array();
            $cantidad = 0;

            foreach ($data as $key1 => $value) {
                foreach ($value as $key2 => $var) {
                    if (!is_int($key2)) {
                        $datos[$key1][$key2] = $var;
                        $datos[$key1]['primary_key'] = $llave . "=>" . $datos[$key1][$llave];
                    }
                }
                $cantidad++;
            }

            foreach ($datos as $value) {
                $value['primary_key'] = $id;
            }

            $result = array('cantidad' => $cantidad, 'datos' => $datos);
            echo json_encode($result);
        } catch (Exception $e) {
            echo "{'codMsg':3,'mensaje':'No se pudo establecer la conexi&oacute;n con el servidor de datos.'}";
            return false;
        }
    }

    private function devolverIp() {
        if ($_SERVER) {
            if ($_SERVER['HTTP_X_FORWARDED_FOR'])
                $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else {
                if ($_SERVER['HTTP_CLIENT_IP'])
                    $realip = $_SERVER['HTTP_CLIENT_IP'];
                else
                    $realip = $_SERVER['REMOTE_ADDR'];
            }
        }
        else {
            if (getenv("HTTP_X_FORWARDED_FOR"))
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            elseif (getenv("HTTP_CLIENT_IP"))
                $realip = getenv("HTTP_CLIENT_IP");
            else
                $realip = getenv("REMOTE_ADDR");
        }
        return $realip;
    }

    function descargarAction() {
        $paquete = $this->_request->getPost('paquete');
        if (!$_SESSION['path'])
            $_SESSION['path'] = $paquete;

        $file = new ZendExt_File ();
        $recursive = $file->tree($paquete);
        $zip = new ZipArchive ();
        $zip->open($paquete . '.zip', ZIPARCHIVE::CREATE);
        foreach ($recursive as $item) {
            if (!is_dir($item))
                $zip->addFile($item);
        }

        $zip->close();
        $file->rm($_SESSION['path']);

        ZendExt_Download :: force_download($_SESSION['path'] . ".zip", file_get_contents($_SESSION['path'] . '.zip'));
    }

}

?>