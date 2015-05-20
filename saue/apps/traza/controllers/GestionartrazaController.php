<?php

class GestionartrazaController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestionartrazaAction() {
        $this->render();
    }

    function confgridAction() {
        $result = array('grid' => array('columns' => array()));
        $result ['grid'] ['columns'] [] = array('id' => 'idtraza', 'hidden' => true, 'hideable' => false, 'dataIndex' => 'idtraza');
        $result ['grid'] ['columns'] [] = array('hidden' => true, 'hideable' => false, 'dataIndex' => 'idestructuracomun');
        $result ['grid'] ['columns'] [] = array('header' => 'Usuario', 'width' => 65, 'sortable' => true, 'dataIndex' => 'usuario');
        $result ['grid'] ['columns'] [] = array('header' => 'Rol', 'width' => 65, 'sortable' => true, 'dataIndex' => 'rol');
        $result ['grid'] ['columns'] [] = array('header' => 'Dominio', 'width' => 125, 'sortable' => true, 'dataIndex' => 'dominio');
        $result ['grid'] ['columns'] [] = array('header' => 'Entidad', 'width' => 140, 'sortable' => true, 'dataIndex' => 'estructuracomun');
        $result ['grid'] ['columns'] [] = array('header' => 'Fecha', 'width' => 70, 'sortable' => true, 'dataIndex' => 'fecha');
        $result ['grid'] ['columns'] [] = array('header' => 'Hora', 'width' => 60, 'sortable' => true, 'dataIndex' => 'hora');
        $result ['grid'] ['columns'] [] = array('header' => 'IP', 'width' => 60, 'sortable' => true, 'dataIndex' => 'ip_host');
        $tmp = new stdClass();
        $result ['grid'] ['campos'] [] = $tmp->name = 'idestructuracomun';
        $tmp = new stdClass();
        $result ['grid'] ['campos'] [] = $tmp->name = 'ip_host';
        $tmp = new stdClass();
        $result ['grid'] ['campos'] [] = $tmp->name = 'rol';
        $tmp = new stdClass();
        $result ['grid'] ['campos'] [] = $tmp->name = 'dominio';
        $tmp = new stdClass();
        $result ['grid'] ['campos'] [] = $tmp->name = 'estructuracomun';
        $tmp = new stdClass();
        $result ['grid'] ['campos'] [] = $tmp->name = 'usuario';
        $tmp = new stdClass();
        $result ['grid'] ['campos'] [] = $tmp->name = 'fecha';
        $tmp = new stdClass();
        $result ['grid'] ['campos'] [] = $tmp->name = 'hora';
        //$tmp = new stdClass();
        $traza = $this->_request->getPost('tipo_traza');

        $traceconfig = ZendExt_FastResponse::getXML('traceconfig');
        $contenedores = $traceconfig->containers;
        foreach ($contenedores->children() as $contenedor) {
            if ($traza == (string) $contenedor ['alias']) {
                $atributos = $contenedor->atts;
                foreach ($atributos->children() as $column) {
                    $arr = array('header' => (string) $column ['alias'], 'width' => 83, 'sortable' => true, 'dataIndex' => (string) $column ['att']);
                    $result ['grid'] ['columns'] [] = $arr;
                    $tmp = new stdClass();
                    $tmp->name = (string) $column ['att'];
                    $result ['grid'] ['campos'] [] = $tmp;
                }
                break;
            }
        }
//        print_r($result);die;
        echo (json_encode($result));
    }

    function confformAction() {
        $traza = $this->_request->getPost('tipo_traza');
        $traceconfig = ZendExt_FastResponse::getXML('traceconfig');
        $contenedores = $traceconfig->containers;
        $cantidad = 1;
        $idoperador = 0;
        $result = array(array('cantidad' => $cantidad));
        $result [] = array('xtype' => 'TextField', 'fieldLabel' => 'Usuario', 'id' => 'idusuario');
        foreach ($contenedores->children() as $contenedor) {
            if ($traza == (string) $contenedor ['alias']) {
                $atributos = $contenedor->atts;
                foreach ($atributos->children() as $column) {
                    switch ((string) $column ['type']) {
                        case 'text' :
                            $xtype = 'TextField';
                            break;
                        case 'bool' :
                            $xtype = 'combo';
                            break;
                        case 'number' :
                            $xtype = 'NumberField';
                            break;
                        default :
                            $xtype = null;
                            break;
                    }
                    if ($xtype) {
                        if ($xtype == 'NumberField') {
                            $idoperador++;
                            $arr = array('xtype' => 'combo', 'fieldLabel' => 'Operador', 'id' => 'idcomp' . $idoperador, 'data' => $this->cargarcombooperador());
                            $result [] = $arr;
                            $cantidad++;
                        }
                        $arr = array('xtype' => $xtype, 'fieldLabel' => (string) $column ['alias'], 'id' => 'id' . (string) $column ['att']);
                        if ($xtype == 'combo') {
                            $arr ['hiddenName'] = $arr ['id'];
                            $arr ['data'] = $this->cargarcombobool();
                        }
                        $result [] = $arr;
                        $cantidad++;
                    }
                }
                break;
            }
        }
        $result [0] ['cantidad'] = $cantidad;
        echo (json_encode($result));
    }

    function cargarcombooperador() {
        $result [0] = array('>', '>');
        $result [1] = array('>=', '>=');
        $result [2] = array('=', '=');
        $result [3] = array('<=', '<=');
        $result [4] = array('<', '<');
        return $result;
    }

    function cargarcombobool() {
        $result [0] = array(0, 0);
        $result [1] = array(1, 1);
        $result [2] = array("Todas", 2);
        return $result;
    }

    function cargargridAction() {
        $offset = 0;
        $fecha_desde = 0;
        $fecha_hasta = 0;
        if ($this->_request->getPost('idtipotraza'))
            $idtipotraza = $this->_request->getPost('idtipotraza');
        if ($this->_request->getPost('tipotraza'))
            $tipotraza = $this->_request->getPost('tipotraza');
        if ($this->_request->getPost('ip_host'))
            $ip_host = $this->_request->getPost('ip_host');
        if ($this->_request->getPost('rol'))
            $rol = $this->_request->getPost('rol');
        if ($this->_request->getPost('dominio'))
            $dominio = $this->_request->getPost('dominio');
        if ($this->_request->getPost('estructuracomun'))
            $estructuracomun = $this->_request->getPost('estructuracomun');
        if ($this->_request->getPost('fecha_desde'))
            $fecha_desde = $this->_request->getPost('fecha_desde');
        if ($this->_request->getPost('fecha_hasta'))
            $fecha_hasta = $this->_request->getPost('fecha_hasta');
        if ($this->_request->getPost('start'))
            $offset = $this->_request->getPost('start');
        if ($this->_request->getPost('limit'))
            $limit = $this->_request->getPost('limit');
        if ($this->_request->getPost('campos'))
            $campos = json_decode(str_replace('\\', '', $this->_request->getPost('campos')));

        $class_name = $this->clasenombre($tipotraza);

        $Cantidad = $this->cantidad($class_name, $idtipotraza, $ip_host, $rol, $dominio, $estructuracomun, $fecha_desde, $fecha_hasta, $campos);
        $Trazas = $this->cargardatos($class_name, $idtipotraza, $ip_host, $rol, $dominio, $estructuracomun, $fecha_desde, $fecha_hasta, $offset, $limit, $campos);

        if ($class_name == 'HisProceso') {
            $_SESSION['cantidad'] = $Cantidad;
            $campos[] = $class_name;
            $campos[] = '6';
            $campos[] = $ip_host;
            $campos[] = $rol;
            $campos[] = $dominio;
            $campos[] = $estructuracomun;
            $campos[] = $fecha_desde;
            $campos[] = $fecha_hasta;
            $campos[] = $campos;
            $_SESSION['campos'] = $campos;
        }
        echo (json_encode(array('cantidad_trazas' => $Cantidad, 'trazas' => $Trazas)));
    }

    function cargardatos($class_name, $idtipotraza, $ip_host, $rol, $dominio, $estructuracomun, $fecha_desde, $fecha_hasta, $offset, $limit, $campos) {
        if (!$idtipotraza)
            $idtipotraza = 0;
        if (!$ip_host)
            $ip_host = 0;
        if (!$rol)
            $rol = 0;
        if (!$dominio)
            $dominio = 0;
        if (!$estructuracomun)
            $estructuracomun = 0;
        if (!$fecha_desde)
            $fecha_desde = 0;
        if (!$fecha_hasta)
            $fecha_hasta = 0;
        if (!$offset)
            $offset = 0;
        if (!$limit)
            $limit = 20;
        if (!$campos)
            $campos = new stdClass ( );
        $global = ZendExt_GlobalConcept::getInstance();
        if ($global->Estructura->idestructura)
            $idestructura = $global->Estructura->idestructura;
        else
            $idestructura = 0;

        $clase = new $class_name ( );
        $Trazas = null;
        $Trazas = $clase->select($idestructura, $idtipotraza, $ip_host, $rol, $dominio, $estructuracomun, $fecha_desde, $fecha_hasta, $offset, $limit, $campos);
        $clase = null;
        $campos = null;
        $global = null;
        if ($Trazas)
            return $Trazas;
        return array();
    }

    function cargardatosdeproceso($class_name, $idtipotraza, $ip_host, $rol, $dominio, $estructuracomun, $fecha_desde, $fecha_hasta, $offset, $limit, $campos, $nameproceso) {
        if (!$idtipotraza)
            $idtipotraza = 0;
        if (!$ip_host)
            $ip_host = 0;
        if (!$rol)
            $rol = 0;
        if (!$dominio)
            $dominio = 0;
        if (!$estructuracomun)
            $estructuracomun = 0;
        if (!$fecha_desde)
            $fecha_desde = 0;
        if (!$fecha_hasta)
            $fecha_hasta = 0;
        if (!$offset)
            $offset = 0;
        if (!$limit)
            $limit = 20;
        if (!$campos)
            $campos = new stdClass ( );
        $global = ZendExt_GlobalConcept::getInstance();
        if ($global->Estructura->idestructura)
            $idestructura = $global->Estructura->idestructura;
        else
            $idestructura = 0;
        $clase = new $class_name();

        $Trazas = null;
        $Trazas = $clase->selectproceso($idestructura, 6, $ip_host, $rol, $dominio, $estructuracomun, $fecha_desde, $fecha_hasta, $offset, $_SESSION['cantidad'], $nameproceso);
        $clase = null;
        $campos = null;
        $global = null;
        if ($Trazas)
            return $Trazas;
        return array();
    }

    function cantidad($class_name, $idtipotraza, $ip_host, $rol, $dominio, $estructuracomun, $fecha_desde, $fecha_hasta, $campos) {

        if (!$idtipotraza)
            $idtipotraza = 0;
        if (!$ip_host)
            $ip_host = 0;
        if (!$rol)
            $rol = 0;
        if (!$dominio)
            $dominio = 0;
        if (!$estructuracomun)
            $estructuracomun = 0;
        if (!$fecha_desde)
            $fecha_desde = 0;
        if (!$fecha_hasta)
            $fecha_hasta = 0;
        if (!$campos)
            $campos = new stdClass ( );

        $global = ZendExt_GlobalConcept::getInstance();
        if ($global->Estructura->idestructura)
            $idestructura = $global->Estructura->idestructura;
        else
            $idestructura = 0;
        $clase = new $class_name();
        $Cantidad = $clase->cantidad($idestructura, $idtipotraza, $ip_host, $rol, $dominio, $estructuracomun, $fecha_desde, $fecha_hasta, $campos);

        $clase = null;
        $campos = null;
        if ($Cantidad [0] ['cantidad'])
            return $Cantidad [0] ['cantidad'];
        return 0;
    }

    function clasenombre($tipotraza) {
        if (!$tipotraza)
            $tipotraza = "";

        $traceconfig = ZendExt_FastResponse::getXML('traceconfig');
        $contenedores = $traceconfig->containers;
        foreach ($contenedores->children() as $contenedor) {
            if ($tipotraza == (string) $contenedor ['alias']) {
                $class_name = (string) $contenedor ['doctrine'];
                break;
            }
        }
        if ($class_name)
            return $class_name;
        return "";
    }

    function cargarcombotipoAction() {
        $tipo = NomTipotraza::selectAlltipo();
        echo (json_encode(array('tipo_traza' => $tipo)));
    }

    function exportarxesAction() {
        $temp = $this->_request->getParam('datos');


        //$temp = $this->_request->getPost('datos');

        $campos = json_decode(str_replace('\\', '', $temp));
        if ($campos) {
            $file_name = "Registro de eventos" . " (" . date('d-m-Y h-i-s') . ").xes";
            $class_name = $this->clasenombre($campos [5]);
            $xml = <<<END
<?xml version="1.0" encoding="UTF-8" ?>
<!-- This file has been generated with the OpenXES library. It conforms -->
<!-- to the XML serialization of the XES standard for log storage and -->
<!-- management. -->
<!-- XES standard version: 1.0 -->
<!-- OpenXES library version: 1.0RC7 -->
<!-- OpenXES is available from http://www.openxes.org/ -->
<log xes.version="1.0" xes.features="nested-attributes" openxes.version="1.0RC7" xmlns="http://www.xes-standard.org/">
	<extension name="Lifecycle" prefix="lifecycle" uri="http://www.xes-standard.org/lifecycle.xesext"/>
	<extension name="Organizational" prefix="org" uri="http://www.xes-standard.org/org.xesext"/>
	<extension name="Time" prefix="time" uri="http://www.xes-standard.org/time.xesext"/>
	<extension name="Concept" prefix="concept" uri="http://www.xes-standard.org/concept.xesext"/>
	<extension name="Semantic" prefix="semantic" uri="http://www.xes-standard.org/semantic.xesext"/>
	<global scope="event">
		<string key="org:group" value="UNKNOWN"/>
		<string key="concept:instance" value="UNKNOWN"/>
		<string key="org:resource" value="UNKNOWN"/>
		<date key="time:timestamp" value="1970-01-01T00:00:00.000-05:00"/>
		<string key="lifecycle:transition" value="UNKNOWN"/>
		<string key="org:role" value="UNKNOWN"/>
		<string key="concept:name" value="UNKNOWN"/>
		<string key="semantic:modelReference" value="UNKNOWN"/>
	</global>
	<classifier name="Activity classifier" keys="concept:name lifecycle:transition"/>
	<classifier name="Resource classifier" keys="org:resource"/>
 
</log>
END;
            $miObjeto = new SimpleXMLElement($xml);
            $xml = null;
            $Trazas = $this->cargardatosdeproceso($class_name, $campos [2], null, null, null, null, $campos [3], $campos [4], $campos [0], $campos [1], $campos [6], $campos[7]);
            $instanciadeproceso = $Trazas[0]['idinstancia'];
            $trace = $miObjeto->addChild("trace");
            $objtrace = $trace->addChild("string");
            $objtrace->addAttribute("key", "concept:name");
            $objtrace->addAttribute("value", $Trazas[0]['proceso']);
            if ($Trazas)
                foreach ($Trazas as $traz) {
                    if ($traz['proceso'] == $campos[7]) {

                        if ($traz['idinstancia'] != $instanciadeproceso) {
                            $trace = $miObjeto->addChild("trace");
                            $objtrace = $trace->addChild("string");
                            $objtrace->addAttribute("key", "concept:name");
                            $objtrace->addAttribute("value", $traz['proceso']);
                            $instanciadeproceso = $traz['idinstancia'];
                        }
                        //agrego los evento de cada instancia de proceso
                        $event = $trace->addChild("event");
                        //agregando el org:group
                        $obj = $event->addChild("string");
                        $obj->addAttribute("key", "org:group");
                        $obj->addAttribute("value", $traz['dominio']);
                        //agregando el org:resource
                        $obj = $event->addChild("string");
                        $obj->addAttribute("key", "org:resource");
                        $obj->addAttribute("value", $traz['usuario']);
                        //agregando el time:timestamp
                        $timestamp = $traz['fechaevento'];
                        $timestamp = explode(' ', $timestamp);
                        $hora = explode('.', $timestamp[1]);
                        $hora[1] = $hora[1] / 1000;
                        $aux = explode('.', $hora[1]);
                        $hora[1] = $aux[1];
                        $timestamp[1] = $hora[0] . '.' . $hora[1];
                        $zonahoria = date("O");
                        $timestamp = $timestamp[0] . 'T' . $timestamp[1] . $zonahoria;
                        $obj = $event->addChild("date");
                        $obj->addAttribute("key", "time:timestamp");
                        $obj->addAttribute("value", $timestamp);
                        //agregando el lifecycle:transitio
                        $obj = $event->addChild("string");
                        $obj->addAttribute("key", "lifecycle:transition");
                        $obj->addAttribute("value", $traz['estadoactividad']);
                        //agregando el org:role
                        $obj = $event->addChild("string");
                        $obj->addAttribute("key", "org:role");
                        $obj->addAttribute("value", $traz['rol']);
                        //agregando el concept:name
                        $obj = $event->addChild("string");
                        $obj->addAttribute("key", "concept:name");
                        $obj->addAttribute("value", $traz['actividad']);
                        //agregando el concept:instance
                        $obj = $event->addChild("string");
                        $obj->addAttribute("key", "concept:instance");
                        $obj->addAttribute("value", $traz['idactividad']);
                        //agregando el semantic:modelReference
                        $obj = $event->addChild("string");
                        $obj->addAttribute("key", "semantic:modelReference");
                        $obj->addAttribute("value", $traz['ontologia']);
                        //agragar los pl
                        $pls = $traz['pl'];
                        $pls = str_replace('{', '', $pls);
                        $pls = str_replace('}', '', $pls);
                        $pls = explode(',', $pls);
                        $cantidad = count($pls);
                        if ($cantidad >= 3)
                            for ($index = 0; $index < $cantidad; $index+=3) {
                                //agregando el pl
                                $obj = $event->addChild("string");
                                $obj->addAttribute("key", $pls[$index]);
                                $obj->addAttribute("value", $pls[$index + 1]);
                            }
                    }
                }
            $miObjeto->asXML("/tmp/" . $file_name);
            $xml = new DOMDocument();
            $xml->preserveWhiteSpace = false;
            $xml->formatOutput = true;
            $xml->load("/tmp/" . $file_name);
            $xml->save("/tmp/" . $file_name);
            ZendExt_Download :: force_download($file_name, file_get_contents("/tmp/" . $file_name));
        }
    }

    function exportarxmlAction() {
        $temp = $this->_request->getParam('datos');
        $campos = json_decode(str_replace('\\', '', $temp));
        $temp = null;

        if ($campos) {
            $file_name = "Trazas de " . $campos [5] . " (" . date('d-m-Y h-i-s') . ").xml";
            header('Content-Type: application/octet-stream');
            header('Content-Type: application/force-download');
            header("Content-Disposition: inline; filename=\"{$file_name}\"");
            header('Pragma: no-cache');
            header('Expires: 0');
            $class_name = $this->clasenombre($campos [5]);
            $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?> \n" . "<xml> \n" . "	<trazas tipo=\"$campos[5]\"> \n";
            echo $xml;
            $xml = null;
            $cont = 0;
            $Trazas = $this->cargardatos($class_name, $campos [2], null, null, null, null, $campos [3], $campos [4], $campos [0], $campos [1], $campos [6]);
            if ($Trazas)
                foreach ($Trazas as $traz) {
                    $cont++;
                    $xml = "		<traza_" . $cont . "> \n";
                    foreach ($traz as $name => $value)
                        $xml .= "			<" . $name . ">" . $value . "</" . $name . "> \n";
                    $xml .= "		</traza_" . $cont . "> \n";
                    echo $xml;
                }
            $xml = "	</trazas> \n" . "</xml>";
            echo $xml;
        }
    }

    function cargarestadosAction() {
        $tipo = NomTipotraza::selectAlltipo();
        $traceconfig = ZendExt_FastResponse::getXML('traceconfig');
        $contenedores = $traceconfig->containers;
        $result = array();
        foreach ($tipo as $key => $valores) {
            $result[$key]['idtipotraza'] = $valores['idtipotraza'];
            $result[$key]['tipotraza'] = $valores['tipotraza'];
            foreach ($contenedores->children() as $contenedor)
                if ($valores['tipotraza'] == (string) $contenedor ['alias']) {
                    $result[$key]['enabled'] = $this->toEnable($contenedor ['enabled']);
                    break;
                }
        }
        echo (json_encode(array('tipo_traza' => $result)));
    }

    function toEnable($valor) {
        $enabled = 'Activado';
        if ($valor == '0')
            $enabled = 'Desactivado';
        return ($enabled);
    }

    function cargarcomboestadoAction() {
        $estados = array();
        $estados[0]['enabled'] = 'Activado';
        $estados[1]['enabled'] = 'Desactivado';
        echo (json_encode(array('estados' => $estados)));
    }

    function activartrazasAction() {
        $tipotraza = $this->_request->getPost('tipotraza');
        $estado = $this->_request->getPost('estado');
        $this->updateTraceActivation($tipotraza, $estado);
    }

    function actualizarestadotrazasAction() {
        $response->msg = "Estado de trazas actualizado satisfactoriamente";
        $response->codMsg = "1";
        try {
            $result = json_decode($this->_request->getPost('activation'));
            $xml = ZendExt_FastResponse :: getXML('traceconfig');
            foreach ($result as $activation) {
                $estado = ($activation->enabled == "Activado");
                $this->updateTraceActivation($activation->tipotraza, $estado ? 'true' : 'false');
                foreach ($xml->containers->children() as $container) {
                    if ((string) $container ['alias'] == $activation->tipotraza) {
                        $container['enabled'] = (int) $estado;
                        $path = Zend_Registry :: getInstance()->config->xml->traceconfig;
                        file_put_contents($path, $xml->asXml());
                        break;
                    }
                }
            }
        } catch (Exception $exc) {
            $response->msg = "Ocurrió un error interno";
            $response->codMsg = "1";
        }
        echo json_encode($response);
    }

    function getallprocessAction() {
        $campos = $_SESSION['campos'];
        $trazas = $this->cargardatos($campos[0], $campos[1], $campos[2], $campos[3], $campos[4], $campos[5], $campos[6], $campos[7], 0, $_SESSION['cantidad'], $campos[8]);

        foreach ($trazas as $traza) {
            $trazasaux [] = $traza['proceso'];
        }
        $trazasaux = array_unique($trazasaux);
        $data = array();
        foreach ($trazasaux as $traza) {
            $data [] = array("name" => $traza);
        }

        $result = array('datos' => $data);
        $result = array_unique($result);
        echo json_encode($result);
        return;
    }

    /* function mostrardatosAction() {
      $user = $this->_request->getPost('user');
      $passwd = $this->_request->getPost('passwd');
      $tabla = $this->_request->getPost('tabla');
      $esquema = $this->_request->getPost('esquema');
      $id = $this->_request->getPost('idobjeto');

      $config = Zend_Registry::getInstance()->config->bd->traza;
      $port = (isset($config->port)) ? $config->port : '5432';

      $dsn = "{$config->gestor}://{$user}:{$passwd}@{$config->host}:$port/{$config->bd}";

      $conn = Doctrine_Manager::connection($dsn);

      $sqlPK = "select uc.column_name
      from information_schema.key_column_usage uc, information_schema.table_constraints ucc
      where uc.constraint_name = ucc.constraint_name and
      uc.table_name='$tabla' and
      (ucc.constraint_type='PRIMARY KEY')";

      $result = $conn->execute($sqlPK)->fetchAll();

      $campoid = $result [0][0];

      $sql = "select * from mod_historial.{$tabla} where $campoid = $id";
      //$sql = "select * from mod_seguridad.{$tabla} where $campoid = $id";
      $result = $conn->execute($sql)->fetch(Doctrine :: FETCH_ASSOC);

      $data = array();

      foreach ($result as $key => $value) {
      $dat->campo = $key;
      $dat->valor = $value;
      $data[] = $dat;
      $dat = null;
      }

      $json->data = $data;

      echo json_encode($json);
      } */
}

?>
