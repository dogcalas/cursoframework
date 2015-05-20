<?php

class GestnomrelacionController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function GestnomrelacionAction() {
        $this->render();
    }

    function cargarServiciosAction() {
        $node = $this->_request->getPost('node');
        $register = Zend_Registry::getInstance();
        $IoC = ZendExt_FastResponse::getXML('ioc');
        $result = array();
        $id = -1;
        if ($node) {
            $servicios = $IoC->$node->children();
            foreach ($servicios as $servicio) {
                $servicioName = $servicio->getName();
                $Servicio = Doctrine::getTable('DatServicioioc')
                        ->findByDql('denominacion=? and subsistema=?', array($servicioName, $node));
                $idServicio = $Servicio[0]->idservicio;
                if ($idServicio == null) {
                    $idServicio = $id;
                    $id--;
                }
                $nodo = array();
                $nodo['id'] = $node . $servicioName;
                $nodo['text'] = $servicioName;
                $nodo['idservicio'] = $idServicio;
                $nodo['subsistema'] = $node;
                $nodo['leaf'] = true;
                $result[] = $nodo;
            }
        } else {
            $sistemas = $IoC->children();
            foreach ($sistemas as $sistema) {
                $nodo = array();
                $nodo['id'] = $sistema->getName();
                $nodo['text'] = $sistema->getName();
                $nodo['leaf'] = false;
                $result[] = $nodo;
            }
        }
        echo json_encode($result);
    }

    function getcriterioSeleccionAction() {
        $objetosnomenclados = Doctrine::getTable('NomObjetospermisos')->findAll();
        foreach ($objetosnomenclados as $objeto) {
            if ($objeto->nombreobjeto != "Servicios")
                $datos[] = array("criterio" => $objeto->nombreobjeto);
        }
        echo json_encode($datos);
    }

    function configridObjetosAction() {

        $gridDinamicoAsignarPermisos = array('grid' => array('columns' => array()));

        $criterio = $this->_request->getPost('criterio');

        switch ($criterio) {

            case "Tablas": {
                    $fields = array("servidor", "gestor", "base de datos", "esquemas", "tablas",
                        "idservidor", "idgestor", "idbd", "idesquema", "idrol", "idobjetobd", "OWN", "SEL", "INS", "UPD", "DEL", "REF", "TRIG");
                }break;
            case 'Vistas': {
                    $fields = array("servidor", "gestor", "base de datos", "esquemas", "vistas", "idservidor", "idgestor", "idbd", "idesquema", "idrol", "idobjetobd",
                        "OWN", "SEL", "INS", "UPD", "DEL", "REF", "TRIG");
                }break;
            case "Secuencias": {
                    $fields = array("servidor", "gestor", "base de datos", "esquemas", "secuencias",
                        "idservidor", "idgestor", "idbd", "idesquema", "idrol", "idobjetobd", "SEL", "UPD", "USG");
                }break;
            case "Funciones": {
                    $fields = array("servidor", "gestor", "base de datos", "esquemas", "funciones"
                        , "idservidor", "idgestor", "idbd", "idesquema", "idrol", "idobjetobd", "EXEC");
                }break;
        }
        foreach ($fields as $field) {
            $header = ucfirst($field);
            if ($field == 'tablas' || $field == 'vistas' || $field == 'secuencias' || $field == 'funciones')
                $gridDinamicoAsignarPermisos ['grid'] ['columns'] [] =
                        array('header' => $header, 'width' => 150, 'sortable' => true,
                            'dataIndex' => $field, 'editor' => false);

            else if ($field == 'servidor')
                $gridDinamicoAsignarPermisos ['grid'] ['columns'] [] =
                        array('header' => $header, 'width' => 85, 'sortable' => true,
                            'dataIndex' => $field, 'editor' => false);

            else if ($field == 'gestor')
                $gridDinamicoAsignarPermisos ['grid'] ['columns'] [] =
                        array('header' => $header, 'width' => 52, 'sortable' => true,
                            'dataIndex' => $field, 'editor' => false);

            else if ($field == 'base de datos')
                $gridDinamicoAsignarPermisos ['grid'] ['columns'] [] =
                        array('header' => $header, 'width' => 87, 'sortable' => true,
                            'dataIndex' => $field, 'editor' => false);

            else if ($field == 'esquemas')
                $gridDinamicoAsignarPermisos ['grid'] ['columns'] [] =
                        array('header' => $header, 'width' => 120, 'sortable' => true,
                            'dataIndex' => $field, 'editor' => false);
            else if ($field == 'servicio')
                $gridDinamicoAsignarPermisos ['grid'] ['columns'] [] =
                        array('header' => $header, 'width' => 200, 'sortable' => true,
                            'dataIndex' => $field, 'editor' => false);
            else if ($field == 'subsistema')
                $gridDinamicoAsignarPermisos ['grid'] ['columns'] [] =
                        array('header' => $header, 'width' => 120, 'sortable' => true,
                            'dataIndex' => $field, 'editor' => false);

            else if ($field == 'idservidor')
                $gridDinamicoAsignarPermisos ['grid'] ['columns'] [] =
                        array('hidden' => true, 'hideable' => false, 'dataIndex' => $field);

            else if ($field == 'idgestor')
                $gridDinamicoAsignarPermisos ['grid'] ['columns'] [] =
                        array('hidden' => true, 'hideable' => false, 'dataIndex' => $field);

            else if ($field == 'idbd')
                $gridDinamicoAsignarPermisos ['grid'] ['columns'] [] =
                        array('hidden' => true, 'hideable' => false, 'dataIndex' => $field);

            else if ($field == "idesquema")
                $gridDinamicoAsignarPermisos ['grid'] ['columns'] [] =
                        array('hidden' => true, 'hideable' => false, 'dataIndex' => $field);

            else if ($field == "idrol")
                $gridDinamicoAsignarPermisos ['grid'] ['columns'] [] =
                        array('hidden' => true, 'hideable' => false, 'dataIndex' => $field);

            else if ($field == "idobjetobd")
                $gridDinamicoAsignarPermisos ['grid'] ['columns'] [] =
                        array('hidden' => true, 'hideable' => false, 'dataIndex' => $field);
            else
                $gridDinamicoAsignarPermisos ['grid'] ['columns'] [] = array('header' => $header, 'width' => 50, 'sortable' => true, 'dataIndex' => $field, 'editor' => true);

            $gridDinamicoAsignarPermisos ['grid'] ['campos'] [] = $field;
        }

        echo json_encode($gridDinamicoAsignarPermisos);
    }

    function cargargridObjetosAction() {

        $start = $this->_request->getPost('start');
        $limit = $this->_request->getPost('limit');
        $idfunc = $this->_request->getPost('idfunc');
        $idservicio = $this->_request->getPost('idservicio');
        $criterio = $this->_request->getPost('criterio');
        $checkSeleccionado = $this->_request->getPost('seleccionados');
        $BuscarServidor = $this->_request->getPost('servSelected');
        $BuscarGestor = $this->_request->getPost('gestSelected');
        $BuscarBD = $this->_request->getPost('bdSelected');
        $BuscarEsquema = $this->_request->getPost('esqSelected');
        $BuscarObjeto = $this->_request->getPost('nombSelected');
        $datosConexion = DatSistemaDatServidores::datosDeConex1();
        $model = new DatServicioObjetobdModel();
        $datosConexion = $this->OrdenarServidores($datosConexion);
        $datosConexion = $model->ExtraerConexionesRepetidas($datosConexion);
        $result = array();
        $arregloObjetos = array();
        $arregloObjetos = $this->arregloObjetosBD($datosConexion, $criterio, $BuscarServidor, $BuscarGestor, $BuscarBD, $BuscarEsquema, $BuscarObjeto);
        $cantidad = count($arregloObjetos['objetos']);
        $result['cantidad'] = $cantidad;
        if ($checkSeleccionado == 'false')
            $arregloObjetos['objetos'] = array_slice($arregloObjetos['objetos'], $start, $limit);
        if ($result['cantidad'] == 0) {
            echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfObjetos}");            
        } else {
            $result['datos'] = array();
            $cont = 0;
            $arrayObjetosBD = Doctrine::getTable('DatObjetobd')->findAll();
            foreach ($arregloObjetos['objetos'] as $value) {
                $objeto = $value['objeto'];
                $idesquema = $value['idesquema'];
                $idservidor = $value['idservidor'];
                $idgestor = $value['idgestor'];
                $idbd = $value['idbd'];
                $idrol = $value['idrol'];
                $tuplaServicioObjetobd = DatObjetobd::obtenerPermisosServicios
                                ($objeto, $idesquema, $idservidor, $idgestor, $idbd, $idservicio, $idrol);
                if (count($tuplaServicioObjetobd) != 0) {
                    $permisos = $tuplaServicioObjetobd[0]['DatServicioObjetobd'][0]['privilegios'];
                    $idobjetobd = $tuplaServicioObjetobd[0]['idobjetobd'];
                    $arregloCheckBox = $this->arregloCheckBox
                            ($criterio, $cont, $permisos, $value['servidor'], $value['gestor'], $value['esquema'], $objeto, $value['bd'], $idservidor, $idgestor, $idesquema, $idbd, $idrol, $idobjetobd);
                    $result['datos'] = array_merge($result['datos'], $arregloCheckBox);
                } else if ($checkSeleccionado == 'false') {
                    $result['datos'][$cont]['servidor'] = $value['servidor'];
                    $result['datos'][$cont]['gestor'] = $value['gestor'];
                    $result['datos'][$cont]['esquemas'] = $value['esquema'];
                    $result['datos'][$cont]['base de datos'] = $value['bd'];
                    $result['datos'][$cont]['idservidor'] = $idservidor;
                    $result['datos'][$cont]['idgestor'] = $idgestor;
                    $result['datos'][$cont]['idesquema'] = $idesquema;
                    $result['datos'][$cont]['idbd'] = $idbd;
                    $result['datos'][$cont]['idrol'] = $idrol;
                    $result['datos'][$cont]['idobjetobd'] = $this->ObjetoAlmacenado($idservidor, $idgestor, $idbd, $idesquema, $objeto, $arrayObjetosBD);
                    $criterioAux = lcfirst($criterio);
                    $result['datos'][$cont][$criterioAux] = $objeto;
                    $result['datos'][$cont]['OWN'] = false;
                    $result['datos'][$cont]['SEL'] = false;
                    $result['datos'][$cont]['INS'] = false;
                    $result['datos'][$cont]['UPD'] = false;
                    $result['datos'][$cont]['DEL'] = false;
                    $result['datos'][$cont]['REF'] = false;
                    $result['datos'][$cont]['TRIG'] = false;
                }

                $cont++;
            }
            $ipsError = array();
            foreach ($arregloObjetos['manajers'] as $value) {
                if (!in_array($value, $ipsError)) {
                    $ipsError[] = $value;
                }
            }

            $mensajeError = "Por alguna razón en algún no ha podido acceder a los servidores:";
            foreach ($ipsError as $value) {
                $mensajeError.=" " . $value;
            }

            $_SESSION['mensaje'] = $mensajeError;

            if ($checkSeleccionado != 'false') {
                $result['cantidad'] = count($result['datos']);
                $result['datos'] = array_slice($result['datos'], $start, $limit);
            }
            if ($result['cantidad'] == 0) {
                echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfObjetos}");               
            } else {
                echo json_encode($result);
            }
        }
    }

    function modificarPermisosAction() {

        $RSA = new ZendExt_RSA_Facade();
        $arrayAccess = json_decode(stripslashes($this->_request->getPost('acceso')));
        $arrayDeny = json_decode(stripslashes($this->_request->getPost('denegado')));
        $idservicio = $this->_request->getPost('idservicio');
        $servicio = $this->_request->getPost('servicio');
        $subsistema = $this->_request->getPost('subsistema');
        $criterio = $this->_request->getPost('criterio');
        $arrayEliminados = array();
        $arrayAdicionados = array();
        $tipo = $this->TipoConexion();
        $criterio = NomObjetospermisos::obtenerIdCriterio($criterio);
        if (($tipo == 2 || $tipo == 3) && count($arrayDeny) > 0)
            $this->EliminarAdicionarPermisosOverServicios($idservicio, $arrayDeny, $criterio, "REVOKE ", $tipo, false);
        $ObjetoBdModel = new DatObjetobdModel();
        $relacionObjetoBdModel = new DatServicioObjetobdModel();
        foreach ($arrayDeny as $value) {
            $idServidor = $value[0];
            $idgestor = $value[1];
            $idbd = $value[2];
            $idesquema = $value[3];
            $idrol = $value[4];
            $objeto = $value[5];
            $idobjetobd = $value[7];
            $permisos = $relacionObjetoBdModel->ArrayPermisosToString($value[6]);
            if ($idobjetobd != 0) {
                $instanceDatServicioObjetoBD = DatServicioObjetobd::getById($idservicio, $idobjetobd);
                if ($instanceDatServicioObjetoBD != null) {
                    $permisosCambiados = $this->EliminarPermisos($permisos, $instanceDatServicioObjetoBD->privilegios);
                    if ($permisosCambiados == "") {
                        $relacionObjetoBdModel->eliminar($instanceDatServicioObjetoBD);
                        $existeObjetoAccion = DatAccionDatObjetobd::getBy_idobjetobd($idobjetobd);
                        $existeObjetoServicio = DatServicioObjetobd::getBy_idobjetobd($idobjetobd);
                        if (count($existeObjetoAccion) == 0 && count($existeObjetoServicio) == 0 && !$this->ExisteInArrayAccess($arrayAccess, $idobjetobd)) {
                            $arrayEliminados[] = $idobjetobd;
                            $instanceDatObjetoBd = DatObjetobd::getById($idobjetobd);
                            $ObjetoBdModel->eliminar($instanceDatObjetoBd);
                        }
                    } else {
                        $instanceDatServicioObjetoBD->privilegios = $permisosCambiados;
                        $relacionObjetoBdModel->modificar($instanceDatServicioObjetoBD);
                    }
                }
            }
        }
        $ServicioObjModel = new DatServicioObjetobdModel();
        if ($idservicio > 0) {
            $exist_conAccion = DatAccionDatServicioioc::getBy_idServicio($idservicio);
            $exist_conObjeto = DatServicioObjetobd::getBy_idservicio($idservicio);
            if (count($exist_conAccion) == 0 && count($exist_conObjeto) == 0 && count($arrayAccess) == 0) {
                $instanceService = DatServicioioc::getById($idservicio);
                $ServicioObjModel->eliminar($instanceService);
                $idservicio = 0;
            }
        } else if (count($arrayAccess) != 0) {
            $newObj = new DatServicioioc();
            $newObj->denominacion = $servicio;
            $newObj->subsistema = $subsistema;
            $ServicioObjModel->Insertar($newObj);
            $idservicio = $newObj->idservicio;
        }
        $cont = 0;
        foreach ($arrayAccess as $value) {
            $idServidor = $value[0];
            $idgestor = $value[1];
            $idbd = $value[2];
            $idesquema = $value[3];
            $idrol = $value[4];
            $objeto = $value[5];
            $idobjetobd = $value[7];
            $permisos = $relacionObjetoBdModel->ArrayPermisosToString($value[6]);
            if ($idobjetobd != 0) {
                $instanceDatServicioObjetoBD = DatServicioObjetobd::getById($idservicio, $idobjetobd);
                if ($instanceDatServicioObjetoBD != null) {
                    $instanceDatServicioObjetoBD->privilegios =
                            $ObjetoBdModel->CombinarPermisos($instanceDatServicioObjetoBD->privilegios, $permisos);

                    $relacionObjetoBdModel->modificar($instanceDatServicioObjetoBD);
                } else {
                    $DatServicioObjetoBd = new DatServicioObjetobd();
                    $DatServicioObjetoBd->privilegios = $permisos;
                    $DatServicioObjetoBd->idservicio = $idservicio;
                    $DatServicioObjetoBd->idobjetobd = $idobjetobd;
                    $relacionObjetoBdModel->insertar($DatServicioObjetoBd);
                }
            } else {
                $ObjetoBdNewObject = new DatObjetobd();
                $ObjetoBdNewObject->idservidor = $idServidor;
                $ObjetoBdNewObject->idgestor = $idgestor;
                $ObjetoBdNewObject->idbd = $idbd;
                $ObjetoBdNewObject->idesquema = $idesquema;
                $ObjetoBdNewObject->objeto = $objeto;
                $ObjetoBdNewObject->idobjeto = $criterio;
                $ObjetoBdNewObject->idrolesbd = $idrol;
                $ObjetoBdModel->insertar($ObjetoBdNewObject);
                $DatServicioObjetoBd = new DatServicioObjetobd();
                $DatServicioObjetoBd->privilegios = $permisos;
                $DatServicioObjetoBd->idservicio = $idservicio;
                $DatServicioObjetoBd->idobjetobd = $ObjetoBdNewObject->idobjetobd;
                $idobjetobd = $ObjetoBdNewObject->idobjetobd;
                $relacionObjetoBdModel->insertar($DatServicioObjetoBd);
                $arrayAccess[$cont][7] = $ObjetoBdNewObject->idobjetobd;
                $arrayAdicionados[] = array("idobj" => $ObjetoBdNewObject->idobjetobd,
                    "idservidor" => $idServidor,
                    "idgestor" => $idgestor,
                    "idbd" => $idbd,
                    "idesquema" => $idesquema,
                    "obj" => $objeto);
            }
            $cont++;
        }
        if ($tipo == 2 || $tipo == 3) {
            if (count($arrayAccess))
                $this->EliminarAdicionarPermisosOverServicios($idservicio, $arrayAccess, $criterio, "GRANT ", $tipo, true);
        }

        $eliminados = json_encode($arrayEliminados);
        $adicionados = json_encode($arrayAdicionados);
        echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfRelacion,'elim':'$eliminados','add':'$adicionados', 'ids':'$idservicio'}");
    }

    private function TipoConexion() {
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->configConection;
        $DOM_XML_Conex = new DOMDocument();
        $contentfile = file_get_contents($dirconfigConection);
        $DOM_XML_Conex->loadXML($contentfile);
        $elements = $this->getElementsByAttr($DOM_XML_Conex, "seleccion", "true");
        $element = $elements->item(0);
        return $element->getAttribute('tipo');
    }

    private function getElementsByAttr($DOM, $nameAtrr, $value) {
        $xpath = new DOMXpath($DOM);
        $elements = $xpath->query("//*[@$nameAtrr='$value']");
        if ($elements->length > 0) {
            return $elements;
        }
        return false;
    }

    private function ObjetoAlmacenado($idservidor, $idgestor, $idbd, $idesquema, $objeto, $arrayObjetosBD) {
        foreach ($arrayObjetosBD as $ObjetoBD) {
            if ($ObjetoBD->idservidor == $idservidor && $ObjetoBD->idgestor == $idgestor && $ObjetoBD->idbd == $idbd && $ObjetoBD->idesquema == $idesquema && $ObjetoBD->objeto == $objeto) {
                return $ObjetoBD->idobjetobd;
            }
        }
        return 0;
    }

    private function OrdenarServidores($datosConexion) {
        $result = array();
        $ipAdicionados = array();

        foreach ($datosConexion as $value) {
            $ip = $value['idservidor'];
            if (!in_array($ip, $ipAdicionados)) {
                $ipAdicionados[] = $ip;
                foreach ($datosConexion as $value1) {
                    if ($value1['idservidor'] == $ip) {
                        $result[] = $value1;
                    }
                }
            }
        }
        return $result;
    }

    private function arregloObjetosBD($datosConexion, $criterio, $BuscarServidor, $BuscarGestor, $BuscarBD, $BuscarEsquema, $BuscarObjeto) {

        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $RSA = new ZendExt_RSA_Facade();
        $arregloObjetos = array();
        $arregloObjetos['manajers'] = array();
        $arregloObjetos['objetos'] = array();
        $managersError = array();
        $ipsError = array();
        $model = new DatServicioObjetobdModel();

        $datosConexion = $this->UnirEquemasMismaBD($datosConexion);
        $datosConexion = $this->FiltrarServidorGestorBdEsquemas
                ($datosConexion, $BuscarServidor, $BuscarGestor, $BuscarBD, $BuscarEsquema);
        $ipsPing = array();

        for ($i = 0; $i < count($datosConexion); $i++) {

            $ipservidor = $datosConexion[$i]["datos"]["ipservidor"];
            $idservidor = $datosConexion[$i]["datos"]["idservidor"];
            $gestor = $datosConexion[$i]["datos"]["gestor"];
            $idgestor = $datosConexion[$i]["datos"]["idgestor"];
            $puerto = $datosConexion[$i]["datos"]["puerto"];
            $bd = $datosConexion[$i]["datos"]['bd'];
            $idbd = $datosConexion[$i]["datos"]['idbd'];
            $user = $datosConexion[$i]["datos"]["rol"];
            $idrol = $datosConexion[$i]["datos"]["idrol"];
            $pass = $datosConexion[$i]["datos"]["pass"];
            $pass = $RSA->decrypt($pass);

            $Arregloesquemas = $datosConexion[$i]["esquemas"];
            $whereEsquemas = $this->WhereEsquemas($Arregloesquemas, $criterio);

            $where = $whereEsquemas['where'];
            $esquemasindexados = $whereEsquemas['esquemaindexado'];
            $BuscarObjeto = $model->TratarCriterioBusqueda($BuscarObjeto);
            $criterioBusqueda = "'%$BuscarObjeto%'";
            if (!in_array($ipservidor, $ipsPing))
                if (!$model->VerifyConnection("$gestor://$user:$pass@$ipservidor:$puerto/$bd")) {
                    $managersError[] = "$gestor://$user:$pass@$ipservidor:$puerto/$bd";
                    $ipsError[] = $ipservidor;
                    $ipsPing[] = $ipservidor;
                } else {
                    if ($criterio == 'Tablas') {

                        $tablas = array();

                        if ($BuscarObjeto != "") {

                            if (!in_array("$gestor://$user:$pass@$ipservidor:$puerto/$bd", $managersError)) {

                                $tablas = $Pgsql->getPgsqlTablasDinamicWhereCriterio
                                        ($gestor, $user, $pass, $ipservidor, $bd, $where, $criterioBusqueda, $puerto);

                                $tablas["connectioerror"] = substr($tablas["connectioerror"], 0, 37);


                                if ($tablas["connectioerror"] == "PDO Connection Error: SQLSTATE[08006]") {

                                    $managersError[] = "$gestor://$user:$pass@$ipservidor:$puerto/$bd";
                                    $ipsError[] = $ipservidor;
                                } else {

                                    $ObjetosMasEsquema = $this->ObjetoDatosIdentificadores
                                            ($tablas, $criterio, $esquemasindexados, $ipservidor, $gestor, $bd, $idservidor, $idgestor, $idbd, $idrol);

                                    $arregloObjetos['objetos'] = array_merge($arregloObjetos['objetos'], $ObjetosMasEsquema);
                                }
                            }
                        } else {

                            if (!in_array("$gestor://$user:$pass@$ipservidor:$puerto/$bd", $managersError)) {


                                $tablas = $Pgsql->getPgsqlTablasDinamicWhere
                                        ($gestor, $user, $pass, $ipservidor, $bd, $where, $puerto);

                                $tablas["connectioerror"] = substr($tablas["connectioerror"], 0, 37);


                                if ($tablas["connectioerror"] == "PDO Connection Error: SQLSTATE[08006]") {

                                    $managersError[] = "$gestor://$user:$pass@$ipservidor:$puerto/$bd";
                                    $ipsError[] = $ipservidor;
                                } else {

                                    $ObjetosMasEsquema = $this->ObjetoDatosIdentificadores
                                            ($tablas, $criterio, $esquemasindexados, $ipservidor, $gestor, $bd, $idservidor, $idgestor, $idbd, $idrol);

                                    $arregloObjetos['objetos'] = array_merge($arregloObjetos['objetos'], $ObjetosMasEsquema);
                                }
                            }
                        }
                    } else if ($criterio == 'Vistas') {

                        $vistas = array();
                        if ($BuscarObjeto != "") {

                            if (!in_array("$gestor://$user:$pass@$ipservidor:$puerto/$bd", $managersError)) {
                                $vistas = $Pgsql->getPgsqlVistasDinamicWhereCriterio
                                        ($gestor, $user, $pass, $ipservidor, $bd, $where, $criterioBusqueda, $puerto);

                                $vistas["connectioerror"] = substr($vistas["connectioerror"], 0, 37);

                                if ($vistas["connectioerror"] == "PDO Connection Error: SQLSTATE[08006]") {

                                    $managersError[] = "$gestor://$user:$pass@$ipservidor:$puerto/$bd";
                                    $ipsError[] = $ipservidor;
                                } else {

                                    $ObjetosMasEsquema = $this->ObjetoDatosIdentificadores
                                            ($vistas, $criterio, $esquemasindexados, $ipservidor, $gestor, $bd, $idservidor, $idgestor, $idbd, $idrol);
                                    $arregloObjetos['objetos'] = array_merge($arregloObjetos['objetos'], $ObjetosMasEsquema);
                                }
                            }
                        } else {
                            if (!in_array("$gestor://$user:$pass@$ipservidor:$puerto/$bd", $managersError)) {

                                $vistas = $Pgsql->getPgsqlVistasDinamicWhere
                                        ($gestor, $user, $pass, $ipservidor, $bd, $where, $puerto);

                                $vistas["connectioerror"] = substr($vistas["connectioerror"], 0, 37);

                                if ($vistas["connectioerror"] == "PDO Connection Error: SQLSTATE[08006]") {

                                    $managersError[] = "$gestor://$user:$pass@$ipservidor:$puerto/$bd";
                                    $ipsError[] = $ipservidor;
                                } else {

                                    $ObjetosMasEsquema = $this->ObjetoDatosIdentificadores
                                            ($vistas, $criterio, $esquemasindexados, $ipservidor, $gestor, $bd, $idservidor, $idgestor, $idbd, $idrol);
                                    $arregloObjetos['objetos'] = array_merge($arregloObjetos['objetos'], $ObjetosMasEsquema);
                                }
                            }
                        }
                    } else if ($criterio == 'Secuencias') {

                        $secuencias = array();
                        if ($BuscarObjeto != "") {
                            if (!in_array("$gestor://$user:$pass@$ipservidor:$puerto/$bd", $managersError)) {
                                $secuencias = $Pgsql->getPgsqlSecuenciasDinamicWhereCriterio
                                        ($gestor, $user, $pass, $ipservidor, $bd, $where, $criterioBusqueda, $puerto);

                                $secuencias["connectioerror"] = substr($secuencias["connectioerror"], 0, 37);

                                if ($secuencias["connectioerror"] == "PDO Connection Error: SQLSTATE[08006]") {
                                    $managersError[] = "$gestor://$user:$pass@$ipservidor:$puerto/$bd";
                                    $ipsError[] = $ipservidor;
                                } else {
                                    $ObjetosMasEsquema = $this->ObjetoDatosIdentificadores
                                            ($secuencias, $criterio, $esquemasindexados, $ipservidor, $gestor, $bd, $idservidor, $idgestor, $idbd, $idrol);
                                    $arregloObjetos['objetos'] = array_merge($arregloObjetos['objetos'], $ObjetosMasEsquema);
                                }
                            }
                        } else {
                            if (!in_array("$gestor://$user:$pass@$ipservidor:$puerto/$bd", $managersError)) {

                                $secuencias = $Pgsql->getPgsqlSecuenciasDinamicWhere
                                        ($gestor, $user, $pass, $ipservidor, $bd, $where, $puerto);
                                $secuencias["connectioerror"] = substr($secuencias["connectioerror"], 0, 37);

                                if ($secuencias["connectioerror"] == "PDO Connection Error: SQLSTATE[08006]") {
                                    $managersError[] = "$gestor://$user:$pass@$ipservidor:$puerto/$bd";
                                    $ipsError[] = $ipservidor;
                                } else {
                                    $ObjetosMasEsquema = $this->ObjetoDatosIdentificadores
                                            ($secuencias, $criterio, $esquemasindexados, $ipservidor, $gestor, $bd, $idservidor, $idgestor, $idbd, $idrol);
                                    $arregloObjetos['objetos'] = array_merge($arregloObjetos['objetos'], $ObjetosMasEsquema);
                                }
                            }
                        }
                    } else if ($criterio == 'Funciones') {
                        $funciones = array();
                        if ($BuscarObjeto != "") {
                            if (!in_array("$gestor://$user:$pass@$ipservidor:$puerto/$bd", $managersError)) {
                                $funciones = $Pgsql->getPgsqlFuncionesDinamicWhereCriterio
                                        ($gestor, $user, $pass, $ipservidor, $bd, $where, $criterioBusqueda, $puerto);
                                $funciones["connectioerror"] = substr($funciones["connectioerror"], 0, 37);

                                if ($funciones["connectioerror"] == "PDO Connection Error: SQLSTATE[08006]") {
                                    $managersError[] = "$gestor://$user:$pass@$ipservidor:$puerto/$bd";
                                    $ipsError[] = $ipservidor;
                                } else {
                                    $ObjetosMasEsquema = $this->ObjetoDatosIdentificadores
                                            ($funciones, $criterio, $esquemasindexados, $ipservidor, $gestor, $bd, $idservidor, $idgestor, $idbd, $idrol);
                                    $arregloObjetos['objetos'] = array_merge($arregloObjetos['objetos'], $ObjetosMasEsquema);
                                }
                            }
                        } else {
                            if (!in_array("$gestor://$user:$pass@$ipservidor:$puerto/$bd", $managersError)) {

                                $funciones = $Pgsql->getPgsqlFuncionesDinamicWhere
                                        ($gestor, $user, $pass, $ipservidor, $bd, $where, $puerto);
                                $funciones["connectioerror"] = substr($funciones["connectioerror"], 0, 37);

                                if ($funciones["connectioerror"] == "PDO Connection Error: SQLSTATE[08006]") {
                                    $managersError[] = "$gestor://$user:$pass@$ipservidor:$puerto/$bd";
                                    $ipsError[] = $ipservidor;
                                } else {
                                    $ObjetosMasEsquema = $this->ObjetoDatosIdentificadores
                                            ($funciones, $criterio, $esquemasindexados, $ipservidor, $gestor, $bd, $idservidor, $idgestor, $idbd, $idrol);
                                    $arregloObjetos['objetos'] = array_merge($arregloObjetos['objetos'], $ObjetosMasEsquema);
                                }
                            }
                        }
                    }
                }
        }

        $arregloObjetos['manajers'] = $ipsError;

        return $arregloObjetos;
    }

    private function arregloCheckBox($criterio, $cont, $permisos, $servidor, $gestor, $esquema, $objeto, $bd, $idservidor, $idgestor, $idesquema, $idbd, $idrol, $idobjetobd) {

        $result = array();

        $result[$cont]['servidor'] = $servidor;
        $result[$cont]['gestor'] = $gestor;
        $result[$cont]['base de datos'] = $bd;
        $result[$cont]['esquemas'] = $esquema;
        $result[$cont]['idservidor'] = $idservidor;
        $result[$cont]['idgestor'] = $idgestor;
        $result[$cont]['idesquema'] = $idesquema;
        $result[$cont]['idbd'] = $idbd;
        $result[$cont]['idrol'] = $idrol;
        $result[$cont]['idobjetobd'] = $idobjetobd;
        $criterioAux = lcfirst($criterio);
        $result[$cont][$criterioAux] = $objeto;

        if ($criterio == 'Tablas' || $criterio == 'Vistas') {

            if ($permisos == "rawdxt") {
                $result[$cont]['OWN'] = true;
            } else {

                $result[$cont]['OWN'] = false;

                if ($this->StrPos($permisos, 'r')) {
                    $result[$cont]['SEL'] = true;
                } else {
                    $result[$cont]['SEL'] = false;
                }
                if ($this->StrPos($permisos, 'a')) {
                    $result[$cont]['INS'] = true;
                } else {
                    $result[$cont]['INS'] = false;
                }
                if ($this->StrPos($permisos, 'w')) {
                    $result[$cont]['UPD'] = true;
                } else {
                    $result[$cont]['UPD'] = false;
                }
                if ($this->StrPos($permisos, 'd')) {
                    $result[$cont]['DEL'] = true;
                } else {
                    $result[$cont]['DEL'] = false;
                }
                if ($this->StrPos($permisos, 'x')) {
                    $result[$cont]['REF'] = true;
                } else {
                    $result[$cont]['REF'] = false;
                }
                if ($this->StrPos($permisos, 't')) {
                    $result[$cont]['TRIG'] = true;
                } else {
                    $result[$cont]['TRIG'] = false;
                }
            }
        } else if ($criterio == 'Secuencias') {

            if ($this->StrPos($permisos, 'r')) {
                $result[$cont]['SEL'] = true;
            } else {
                $result[$cont]['SEL'] = false;
            }
            if ($this->StrPos($permisos, 'w')) {
                $result[$cont]['UPD'] = true;
            } else {
                $result[$cont]['UPD'] = false;
            }
            if ($this->StrPos($permisos, 'U')) {
                $result[$cont]['USG'] = true;
            } else {
                $result[$cont]['USG'] = false;
            }
        } else if ($criterio == 'Funciones') {
            if ($this->StrPos($permisos, 'X')) {
                $result[$cont]['EXEC'] = true;
            } else {
                $result[$cont]['EXEC'] = false;
            }
        }
        return $result;
    }

    private function UnirEquemasMismaBD($datosConexion) {
        $posicionActual = 0;
        $ServidorGestorBaseDatosRol = array();
        $esquemas = array();
        $newDatosConexion = array();

        foreach ($datosConexion as $value) {

            $elementobuscar['idbd'] = $value['DatBd']['idbd'];
            $elementobuscar['bd'] = $value['DatBd']['bd'];
            $elementobuscar['idservidor'] = $value['DatServidor']['idservidor'];
            $elementobuscar['ipservidor'] = $value['DatServidor']['ip'];
            $elementobuscar['idgestor'] = $value['DatGestor']['idgestor'];
            $elementobuscar['gestor'] = $value['DatGestor']['gestor'];
            $elementobuscar['puerto'] = $value["DatGestor"]["puerto"];
            $elementobuscar['idrol'] = $value["SegRolesbd"]["idrolesbd"];
            $elementobuscar['rol'] = $value["SegRolesbd"]["rol"];
            $elementobuscar['pass'] = $value["SegRolesbd"]["passw"];


            if (!in_array($elementobuscar, $ServidorGestorBaseDatosRol)) {
                $ServidorGestorBaseDatosRol[] = $elementobuscar;
                $cont = 0;
                foreach ($datosConexion as $value1) {

                    if ($value1['DatBd']['idbd'] == $elementobuscar['idbd'] &&
                            $value1['DatServidor']['idservidor'] == $elementobuscar['idservidor'] &&
                            $value1['DatGestor']['idgestor'] == $elementobuscar['idgestor'] &&
                            $value1["SegRolesbd"]["idrolesbd"] == $elementobuscar['idrol']) {

                        $esquemas[$posicionActual][$cont]['esquema'] = $value1["DatEsquema"]["esquema"];
                        $esquemas[$posicionActual][$cont]['idesquema'] = $value1["DatEsquema"]["idesquema"];
                        $cont++;
                    }
                }
                $newDatosConexion[$posicionActual]['datos'] = $elementobuscar;
                $newDatosConexion[$posicionActual]['esquemas'] = $esquemas[$posicionActual];
                $posicionActual++;
            }
        }

        return $newDatosConexion;
    }

    private function FiltrarServidorGestorBdEsquemas
    ($datosConexion, $BuscarServidor, $BuscarGestor, $BuscarBD, $BuscarEsquema) {

        $datosFiltrados = array();
        if ($BuscarServidor == "" && $BuscarGestor == "" && $BuscarBD == "" && $BuscarEsquema == "") {

            return $datosConexion;
        } else {

            if ($BuscarServidor != "" || $BuscarGestor != "" || $BuscarBD != "")
                foreach ($datosConexion as $value) {

                    if ($BuscarServidor != "" && $BuscarGestor == "" && $BuscarBD == "") {

                        $servidor = $value['datos']['ipservidor'];

                        if (stristr($servidor, $BuscarServidor) != false) {
                            $datosFiltrados[] = $value;
                        }
                    } else if ($BuscarServidor == "" && $BuscarGestor != "" && $BuscarBD == "") {

                        $gestor = $value['datos']['gestor'];
                        if (stristr($gestor, $BuscarGestor) != false) {
                            $datosFiltrados[] = $value;
                        }
                    } else if ($BuscarServidor == "" && $BuscarGestor == "" && $BuscarBD != "") {

                        $BD = $value['datos']['bd'];
                        if (stristr($BD, $BuscarBD) != false) {
                            $datosFiltrados[] = $value;
                        }
                    } else if ($BuscarServidor != "" && $BuscarGestor != "" && $BuscarBD == "") {

                        $gestor = $value['datos']['gestor'];
                        $servidor = $value['datos']['ipservidor'];

                        if (stristr($gestor, $BuscarGestor) != false &&
                                stristr($servidor, $BuscarServidor) != false) {
                            $datosFiltrados[] = $value;
                        }
                    } else if ($BuscarServidor != "" && $BuscarGestor == "" && $BuscarBD != "") {

                        $BD = $value['datos']['bd'];
                        $servidor = $value['datos']['ipservidor'];

                        if (stristr($BD, $BuscarBD) != false &&
                                stristr($servidor, $BuscarServidor) != false) {
                            $datosFiltrados[] = $value;
                        }
                    } else if ($BuscarServidor == "" && $BuscarGestor != "" && $BuscarBD != "") {

                        $gestor = $value['datos']['gestor'];
                        $BD = $value['datos']['bd'];

                        if (stristr($gestor, $BuscarGestor) != false &&
                                stristr($BD, $BuscarBD) != false) {
                            $datosFiltrados[] = $value;
                        }
                    } else if ($BuscarServidor != "" && $BuscarGestor != "" && $BuscarBD != "") {

                        $gestor = $value['datos']['gestor'];
                        $BD = $value['datos']['bd'];
                        $servidor = $value['datos']['ipservidor'];

                        if (stristr($gestor, $BuscarGestor) != false &&
                                stristr($BD, $BuscarBD) != false &&
                                stristr($servidor, $BuscarServidor) != false) {
                            $datosFiltrados[] = $value;
                        }
                    }
                }

            if ($BuscarEsquema != "") {
                $filtraEsquemas = array();
                if (count($datosFiltrados) == 0) {
                    $datosFiltrados = $datosConexion;
                }

                foreach ($datosFiltrados as $elemento) {
                    $esquemas = array();
                    foreach ($elemento['esquemas'] as $value) {
                        if (stristr($value['esquema'], $BuscarEsquema) != false) {
                            $esquemas[] = $value;
                        }
                    }

                    if (count($esquemas) > 0) {
                        $filtraEsquemas[] = array('datos' => $elemento['datos'], 'esquemas' => $esquemas);
                    }
                }

                return $filtraEsquemas;
            }
            else
                return $datosFiltrados;
        }
    }

    private function WhereEsquemas($esquemas, $criterio) {
        $where = "";
        $esquemaIndexado = array();

        if ($criterio == 'Tablas') {
            $lenght = count($esquemas);
            for ($index = 0; $index < $lenght; $index++) {
                if ($index == $lenght - 1) {
                    $esquema = $esquemas[$index]['esquema'];
                    $esquemaIndexado[$esquema] = $esquemas[$index]['idesquema'];
                    $where.="table_schema='$esquema'";
                } else {
                    $esquema = $esquemas[$index]['esquema'];
                    $esquemaIndexado[$esquema] = $esquemas[$index]['idesquema'];
                    $where.="table_schema='$esquema' OR ";
                }
            }
        } else {

            $lenght = count($esquemas);
            for ($index = 0; $index < $lenght; $index++) {
                if ($index == $lenght - 1) {
                    $esquema = $esquemas[$index]['esquema'];
                    $esquemaIndexado[$esquema] = $esquemas[$index]['idesquema'];
                    $where.="ns.nspname='$esquema'";
                } else {
                    $esquema = $esquemas[$index]['esquema'];
                    $esquemaIndexado[$esquema] = $esquemas[$index]['idesquema'];
                    $where.="ns.nspname='$esquema' OR ";
                }
            }
        }

        $resultado = array();
        $resultado['where'] = $where;
        $resultado['esquemaindexado'] = $esquemaIndexado;

        return $resultado;
    }

    private function ObjetoDatosIdentificadores
    ($objetos, $criterio, $esquemaindexados, $servidor, $gestor, $bd, $idservidor, $idgestor, $idBD, $idrol) {

        $arregloObjetoEsquema = array();

        for ($i = 0; $i < count($objetos) - 1; $i++) {

            if ($criterio == 'Tablas') {
                $arregloObjetoEsquema[$i]['objeto'] = $objetos[$i]['table_name'];
                $arregloObjetoEsquema[$i]['esquema'] = $objetos[$i]['table_schema'];
                $arregloObjetoEsquema[$i]['servidor'] = $servidor;
                $arregloObjetoEsquema[$i]['gestor'] = $gestor;
                $arregloObjetoEsquema[$i]['bd'] = $bd;
                $arregloObjetoEsquema[$i]['idservidor'] = $idservidor;
                $arregloObjetoEsquema[$i]['idgestor'] = $idgestor;
                $arregloObjetoEsquema[$i]['idesquema'] = $esquemaindexados[$objetos[$i]['table_schema']];
                $arregloObjetoEsquema[$i]['idbd'] = $idBD;
                $arregloObjetoEsquema[$i]['idrol'] = $idrol;
            } else if ($criterio == 'Vistas') {
                $arregloObjetoEsquema[$i]['objeto'] = $objetos[$i]['relname'];
                $arregloObjetoEsquema[$i]['esquema'] = $objetos[$i]['nspname'];
                $arregloObjetoEsquema[$i]['servidor'] = $servidor;
                $arregloObjetoEsquema[$i]['gestor'] = $gestor;
                $arregloObjetoEsquema[$i]['bd'] = $bd;
                $arregloObjetoEsquema[$i]['idservidor'] = $idservidor;
                $arregloObjetoEsquema[$i]['idgestor'] = $idgestor;
                $arregloObjetoEsquema[$i]['idesquema'] = $esquemaindexados[$objetos[$i]['nspname']];
                $arregloObjetoEsquema[$i]['idbd'] = $idBD;
                $arregloObjetoEsquema[$i]['idrol'] = $idrol;
            } else if ($criterio == 'Secuencias') {
                $arregloObjetoEsquema[$i]['objeto'] = $objetos[$i]['relname'];
                $arregloObjetoEsquema[$i]['esquema'] = $objetos[$i]['nspname'];
                $arregloObjetoEsquema[$i]['servidor'] = $servidor;
                $arregloObjetoEsquema[$i]['gestor'] = $gestor;
                $arregloObjetoEsquema[$i]['bd'] = $bd;
                $arregloObjetoEsquema[$i]['idservidor'] = $idservidor;
                $arregloObjetoEsquema[$i]['idgestor'] = $idgestor;
                $arregloObjetoEsquema[$i]['idesquema'] = $esquemaindexados[$objetos[$i]['nspname']];
                $arregloObjetoEsquema[$i]['idbd'] = $idBD;
                $arregloObjetoEsquema[$i]['idrol'] = $idrol;
            } else if ($criterio == 'Funciones') {
                $arregloObjetoEsquema[$i]['objeto'] = $objetos[$i]['proname'];
                $arregloObjetoEsquema[$i]['esquema'] = $objetos[$i]['nspname'];
                $arregloObjetoEsquema[$i]['servidor'] = $servidor;
                $arregloObjetoEsquema[$i]['gestor'] = $gestor;
                $arregloObjetoEsquema[$i]['bd'] = $bd;
                $arregloObjetoEsquema[$i]['idservidor'] = $idservidor;
                $arregloObjetoEsquema[$i]['idgestor'] = $idgestor;
                $arregloObjetoEsquema[$i]['idesquema'] = $esquemaindexados[$objetos[$i]['nspname']];
                $arregloObjetoEsquema[$i]['idbd'] = $idBD;
                $arregloObjetoEsquema[$i]['idrol'] = $idrol;
            }
        }

        return $arregloObjetoEsquema;
    }

    private function StrPos($permisos, $p) {

        for ($index = 0; $index < strlen($permisos); $index++) {
            if ($permisos[$index] == $p)
                return true;
        }

        return false;
    }

    private function EliminarAdicionarPermisosOverServicios($idservicio, $arrayDenyAcces, $idcriterio, $grant_revoke, $tipo, $QuitarPonerEsquema) {
        $Acciones = DatAccionDatServicioioc::getBy_idServicio($idservicio);
        $arrayIdAcciones = array();
        $acciones = array();
        foreach ($Acciones as $accion) {
            $arrayIdAcciones[] = $accion->idaccion;
            $acciones[] = array('idaccion' => $accion->idaccion);
        }

        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $arregloPermisosOrdenados = $this->OrdernarArrayDenyAcces($arrayDenyAcces, $arrayIdAcciones, $idcriterio, $idservicio);
        $arregloPermisosOrdenados = $Pgsql->UnirPermisosAcciones($arregloPermisosOrdenados);

        $arregloPermisosOrdenados = $Pgsql->CrearConsultasPermisos($arregloPermisosOrdenados, $grant_revoke);
        $arregloObjetosPermisoOrdenado = array();
        $arregloObjetosPermisoOrdenado = $Pgsql->UnirXParametrosConexion($arregloPermisosOrdenados);

        $roles = SegRol::ObtenerRolesFromAccionesObject($arrayIdAcciones);
        $rolesArray = array();
        foreach ($roles as $Rol) {
            $arrayRole = array();
            $arrayRole['idrol'] = $Rol->idrol;
            $arrayRole['denominacion'] = $Rol->denominacion;
            $arrayRole['descripcion'] = $Rol->descripcion;
            $arrayRole['DatSistemaSegRolDatFuncionalidadDatAccion'] = $acciones;
            $rolesArray[] = $arrayRole;
        }
        $RolesXAcciones = $Pgsql->UnirRolesXAcciones($rolesArray);
        $Pgsql->EjecutarAdicionar_EliminarPermisosRoles($RolesXAcciones, $arregloObjetosPermisoOrdenado, $tipo, $QuitarPonerEsquema);
    }

    private function OrdernarArrayDenyAcces($arrayDenyAcces, $arrayidaccion, $idcriterio, $idservicio) {
        $result = array();
        $idgestor = "";
        $idrol = "";
        $model = new DatServicioObjetobdModel();
        foreach ($arrayDenyAcces as $array) {
            if ($idgestor != $array[1]) {
                $puerto = DatGestor::getPuertoPorId($array[1]);
                $idgestor = $array[1];
            }
            if ($idrol != $array[4]) {
                $user_pass = SegRolesbd::getNombreyPassXidrol($array[4]);
                $user = $user_pass[0]['nombrerol'];
                $pass = $user_pass[0]['passw'];
                $idrol = $array[4];
            }
            $permisos = $model->ArrayPermisosToString($array[6]);
            $accion_privilegio = $model->accion_privilegio($arrayidaccion, $idservicio, $array[7]);
            $result[$array[7]] = array(
                "idobjetobd" => $array[7],
                "idobjeto" => $idcriterio,
                "idbd" => $array[2],
                "bd" => $array[10],
                "objeto" => $array[5],
                "idservidor" => $array[0],
                "ip" => $array[8],
                "idesquema" => $array[3],
                "esquema" => $array[11],
                "idgestor" => $array[1],
                "gestor" => $array[9],
                "puerto" => $puerto,
                "idrol" => $array[4],
                "user" => $user,
                "pass" => $pass,
                "accion_privilegio" => $accion_privilegio,
                "permiso" => $permisos
            );
        }
        return $result;
    }

    private function ExisteInArrayAccess($arrayAccess, $idObjetobd) {
        foreach ($arrayAccess as $value) {
            if ($value[7] == $idObjetobd) {
                return true;
            }
        }
        return false;
    }

    private function EliminarPermisos($permisosQuitar, $permisosHay) {

        $permisos = "";
        for ($index = 0; $index < strlen($permisosHay); $index++) {
            if ($this->StrPos($permisosQuitar, $permisosHay[$index]) == false) {
                $permisos.=$permisosHay[$index];
            }
        }

        return $permisos;
    }

}

?>
