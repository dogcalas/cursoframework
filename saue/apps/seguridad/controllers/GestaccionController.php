<?php

/*
 * Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */

class GestaccionController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestaccionAction() {
        $this->render();
    }

    function cargarsistfuncAction() {
        $modelsistema = new DatSistemaModel();
        $idnodo = $this->_request->getPost('node');
        $idsistema = $this->_request->getPost('idsistema');
        if ($idnodo == 0)
            $sistemas = $modelsistema->cargarsistema($idnodo);
        else
            $sistemas = $modelsistema->cargarsistema($idsistema);
        $contador = 0;
        $sistemafunArr = array();

        if (count($sistemas)) {
            foreach ($sistemas as $valores => $valor) {
                $sistemafunArr[$contador]['id'] = $valor['id'] . '_' . $idnodo;
                $sistemafunArr[$contador]['idsistema'] = $valor['id'];
                $sistemafunArr[$contador]['text'] = $valor['text'];
                $contador++;
            }
        }
        $modelfuncionalidad = new DatFuncionalidadModel();
        $funcionalidad = $modelfuncionalidad->cargarfuncionalidades($idsistema, 0, 0);
        if ($funcionalidad->getData() != NULL) {
            foreach ($funcionalidad as $valores => $valor) {
                $sistemafunArr[$contador]['id'] = $valor->id . '_' . $idnodo;
                $sistemafunArr[$contador]['idfuncionalidad'] = $valor->id;
                $sistemafunArr[$contador]['text'] = $valor->text;
                $sistemafunArr[$contador]['referencia'] = $valor->referencia;
                $sistemafunArr[$contador]['idsistema'] = $valor->idsistema;
                $sistemafunArr[$contador]['leaf'] = true;
                $contador++;
            }
            echo json_encode($sistemafunArr);
            return;
        }
        echo json_encode($sistemafunArr);
        return;
    }

    function insertaraccionAction() {
        $denominacion = $this->_request->getPost('denominacion');
        $abreviatura = $this->_request->getPost('abreviatura');
        $idfuncionalidad = $this->_request->getPost('idfuncionalidad');
        $modelaccion = new DatAccionModel();
        $arrayAcciones = array();
        $arrayAcciones = $modelaccion->obtenerAccionesFuncionalidad($idfuncionalidad);
        $denom = true;
        $abrev = true;
        foreach ($arrayAcciones as $accion) {
            if ($accion['denominacion'] == $denominacion) {
                $denom = false;
                break;
            } elseif ($accion['abreviatura'] == $abreviatura) {
                $abrev = false;
                break;
            }
        }
        if (!$denom)
            throw new ZendExt_Exception('SEG048');
        else if (!$abrev)
            throw new ZendExt_Exception('SEG049');
        else {
            $accion = new DatAccion();
            $accion->denominacion = $denominacion;
            $accion->abreviatura = $abreviatura;
            $accion->iddominio = $this->global->Perfil->iddominio;
            $accion->descripcion = $this->_request->getPost('descripcion');
            $accion->icono = 'icon';
            $accion->idfuncionalidad = $idfuncionalidad;
            $idaccion = $modelaccion->insertaraccion($accion);
            $objAccCompart = new DatAccionCompartimentacion();
            $objAccCompart->idaccion = $idaccion;
            $objAccCompart->iddominio = $this->global->Perfil->iddominio;
            $objAccCompart->save();
             echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgAddAct}");
           
        }
    }

    function modificaraccionAction() {
        $accion = Doctrine::getTable('DatAccion')->find($this->_request->getPost('idaccion'));
        $accion->denominacion = $this->_request->getPost('denominacion');
        $accion->descripcion = $this->_request->getPost('descripcion');
        $accion->abreviatura = $this->_request->getPost('abreviatura');
        $accion->icono = $this->_request->getPost('icono');
        $accion->idfuncionalidad = $this->_request->getPost('idfuncionalidad');
        $idaccion = $this->_request->getPost('idaccion');
        $modelaccion = new DatAccionModel();
        $arrayAcciones = array();
        $arrayAcciones = $modelaccion->obtenerAccionesFuncionalidad($accion->idfuncionalidad);
        $denom = true;
        $abrev = true;
        $auxden = 0;
        $auxabv = 0;
        foreach ($arrayAcciones as $aux2) {
            if ($aux2['idaccion'] == $idaccion) {
                $auxden = $aux2['denominacion'];
                $auxabv = $aux2['abreviatura'];
            }
        }
        $denom = true;
        $abrev = true;
        if ($accion->denominacion != $auxden) {
            foreach ($arrayAcciones as $aux3) {
                if ($aux3['denominacion'] == $accion->denominacion) {
                    $denom = false;
                    break;
                }
            }
        }
        if ($accion->abreviatura != $auxabv) {
            foreach ($arrayAcciones as $aux3) {
                if ($aux3['abreviatura'] == $accion->abreviatura) {
                    $abrev = false;
                    break;
                }
            }
        }
        if (!$denom)
            throw new ZendExt_Exception('SEG048');
        else if (!$abrev)
            throw new ZendExt_Exception('SEG049');
        else {
            $modelaccion->modificaraccion($accion);
             echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgModAct}");
         
        }
    }

    function eliminaraccionAction() {
        $idaccion = $this->_request->getPost('idaccion');
        $modelrol = new SegRolModel();
        $tipo = $this->TipoConexion();
        if ($tipo == 2 || $tipo == 3) {
            $Pgsql = new ZendExt_Db_Role_Pgsql();
            $roles = $modelrol->ObtenerRolesFromAccionesObject(array($idaccion));
            foreach ($roles as $rol) {
                $Pgsql->AdicionarEliminarPrivilegiosOverRole($rol, array($idaccion), $tipo, "REVOKE ");
            }
        }
        $accion = new DatAccion();
        $accion = Doctrine::getTable('DatAccion')->find($idaccion);
        $modelaccion = new DatAccionModel();
        $modelaccion->eliminaraccion($accion);
         echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgDelAct}");

    }

    function cargargridaccionesAction() {
        $idfuncionalidad = $this->_request->getPost('idfuncionalidad');
        $denominacion = $this->_request->getPost('denominacion');
        $start = $this->_request->getPost("start");
        $limit = $this->_request->getPost("limit");
        $modelaccion = new DatAccionModel();
        if ($denominacion) {
            $denominacion = $this->TratarCriterioBusqueda($denominacion);
            $datosacc = $modelaccion->buscaraccion($idfuncionalidad, $denominacion, $limit, $start);
            $canfilas = $modelaccion->obtenercantaccionbuscadas($idfuncionalidad, $denominacion);
        } else {
            $datosacc = $modelaccion->cargaraccion($idfuncionalidad, $limit, $start);
            $canfilas = $modelaccion->obtenercantaccion($idfuncionalidad);
        }
        $datos = array();
        if (count($datosacc))
            $datos = $datosacc->toArray();
        $result = array('cantidad_filas' => $canfilas, 'datos' => $datos);
        echo json_encode($result);
        return;
    }

    function buscarAccionesControladorAction() {        
        $referencia = $this->_request->getPost('referencia');
        $rutabase = ereg_replace('web', "apps", $_SERVER['DOCUMENT_ROOT']);
        $dir_appa = explode('/index.php', $referencia);
        $name_controller = explode('/', $dir_appa[1]);        
        $ruta = $rutabase . '/' . (string) $dir_appa[0] . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR . ucfirst(strtolower($name_controller[1])) . 'Controller.php';
        $valor = file_exists($ruta);
        if (!$valor) {
            throw new ZendExt_Exception('SEG057');
        }
        $nombre = ucfirst(strtolower($name_controller[1])) . 'Controller';
        require_once ($ruta);
        try {
            @$class = new Zend_Reflection_Class($nombre);
            $methods = $class->getMethods();
            $contador = 0;
            foreach ($methods as $method) {
                if ($method->getDeclaringClass()->getName() == $class->getName()) {
                    $accion = $method->name;
                    $cantaccion = strlen($accion);
                    $picar = strlen($accion) - 6;
                    if ($cantaccion > 5) {
                        $cadena = substr($accion, strlen($accion) - 6);
                        if ($cadena == 'Action') {
                            $own_methods [$contador]['id'] = $contador + 1;
                            $own_methods [$contador]['text'] = substr($accion, 0, $picar);
                            $own_methods [$contador]['leaf'] = true;
                            $contador++;
                        }
                    }
                }
            }
            echo json_encode($own_methods);
            return;
        } catch (Exception $ee) {
            echo'<pre>';
            print_r($ee);
            die;
        }
    }

    function getcriterioSeleccionAction() {
        $objetosnomenclados = Doctrine::getTable('NomObjetospermisos')->findAll();
        foreach ($objetosnomenclados as $objeto) {
            $datos[] = array("criterio" => $objeto->nombreobjeto);
        }
        echo json_encode($datos);
    }

    /**
     * Método para configurar el grid de asignar permisos
     * donde se le asignan los permisos a los objetos de la
     * base de datos.
     */
    function configridObjetosAction() {

        $gridDinamicoAsignarPermisos = array('grid' => array('columns' => array()));

        $criterio = $this->_request->getPost('criterio');

        switch ($criterio) {

            case "Tablas": {
                    $fields = array("servidor", "gestor", "base de datos", "esquema", "tablas",
                        "idservidor", "idgestor", "idbd", "idesquema", "idrol", "idobjetobd", "OWN", "SEL", "INS", "UPD", "DEL", "REF", "TRIG");
                }break;
            case 'Vistas': {
                    $fields = array("servidor", "gestor", "base de datos", "esquema", "vistas", "idservidor", "idgestor", "idbd", "idesquema", "idrol", "idobjetobd",
                        "OWN", "SEL", "INS", "UPD", "DEL", "REF", "TRIG");
                }break;
            case "Secuencias": {
                    $fields = array("servidor", "gestor", "base de datos", "esquema", "secuencias",
                        "idservidor", "idgestor", "idbd", "idesquema", "idrol", "idobjetobd", "SEL", "UPD", "USG");
                }break;
            case "Funciones": {
                    $fields = array("servidor", "gestor", "base de datos", "esquema", "funciones"
                        , "idservidor", "idgestor", "idbd", "idesquema", "idrol", "idobjetobd", "EXEC");
                }break;
            case "Servicios": {
                    $fields = array("idservicio", "subsistema", "servicio", "USAR");
                }break;
        }

        foreach ($fields as $field) {

            $header = ucwords($field);

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

            else if ($field == 'esquema')
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

            else if ($field == "idservicio")
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

    private function getCargar($idson) {
        $result = DatSistemaDatServidores::datosDeConex($idson);
        if (count($result)) {
            return $result;
        }
        $padre = DatSistema::ObtenerPadre($idson);
        $idpadre = $padre[0]['idpadre'];
        if ($idson == $idpadre) {
            return array();
        } else {
            return $this->getCargar($idpadre);
        }
    }

    function cargargridObjetosAction() {

        $start = $this->_request->getPost('start');
        $limit = $this->_request->getPost('limit');
        $idfunc = $this->_request->getPost('idfunc');
        $idaccion = $this->_request->getPost('idacc');
        $criterio = $this->_request->getPost('criterio');
        $checkSeleccionado = $this->_request->getPost('seleccionados');
        $BuscarSubsistema = $this->_request->getPost('subsistSelected');
        $BuscarServicio = $this->_request->getPost('servicioSelected');
        if ($criterio != "Servicios") {
            $BuscarServidor = $this->_request->getPost('servSelected');
            $BuscarGestor = $this->_request->getPost('gestSelected');
            $BuscarBD = $this->_request->getPost('bdSelected');
            $BuscarEsquema = $this->_request->getPost('esqSelected');
            $BuscarObjeto = $this->_request->getPost('nombSelected');

            $funccionalidadSis = DatFuncionalidad::getIdSistemaFromFuncionalidad($idfunc);
            $idsistema = $funccionalidadSis[0]->idsistema;
            $datosConexion = $this->getCargar($idsistema);
            $datosConexion = $this->OrdenarServidores($datosConexion);
            $datosConexion = $this->ExtraerConexionesRepetidas($datosConexion);
            $result = array();
            $arregloObjetos = array();
            $arregloObjetos = $this->arregloObjetosBD($datosConexion, $criterio, $BuscarServidor, $BuscarGestor, $BuscarBD, $BuscarEsquema, $BuscarObjeto);

            $cantidad = count($arregloObjetos['objetos']);
            $result['cantidad'] = $cantidad;
            if ($checkSeleccionado == 'false')
                $arregloObjetos['objetos'] = array_slice($arregloObjetos['objetos'], $start, $limit);

            if ($result['cantidad'] == 0) {
                $ipsError = array();

                foreach ($arregloObjetos['manajers'] as $value) {
                    if (!in_array($value, $ipsError)) {
                        $ipsError[] = $value;
                    }
                }

                foreach ($ipsError as $value) {
                    $mensajeError=$value;
                }
                $_SESSION['mensaje'] = $mensajeError;
                echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfObjetos}");               
            } else {
                $result['datos'] = array();


                $arrayObjetosBD = Doctrine::getTable('DatObjetobd')->findAll();
                $cont = 0;
                foreach ($arregloObjetos['objetos'] as $value) {

                    $objeto = $value['objeto'];
                    $idesquema = $value['idesquema'];
                    $idservidor = $value['idservidor'];
                    $idgestor = $value['idgestor'];
                    $idbd = $value['idbd'];
                    $idrol = $value['idrol'];

                    $tuplaAccionObjetobd = DatObjetobd::obtenerPermisos
                                    ($objeto, $idesquema, $idservidor, $idgestor, $idbd, $idaccion, $idrol);

                    if (count($tuplaAccionObjetobd) != 0) {

                        $permisos = $tuplaAccionObjetobd[0]['DatAccionDatObjetobd'][0]['privilegios'];
                        $idobjetobd = $tuplaAccionObjetobd[0]['idobjetobd'];
                        $arregloCheckBox = $this->arregloCheckBox
                                ($criterio, $cont, $permisos, $value['servidor'], $value['gestor'], $value['esquema'], $objeto, $value['bd'], $idservidor, $idgestor, $idesquema, $idbd, $idrol, $idobjetobd);

                        $result['datos'] = array_merge($result['datos'], $arregloCheckBox);
                    } else if ($checkSeleccionado == 'false') {
                        $result['datos'][$cont]['servidor'] = $value['servidor'];
                        $result['datos'][$cont]['gestor'] = $value['gestor'];
                        $result['datos'][$cont]['esquema'] = $value['esquema'];
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

              
                foreach ($ipsError as $value) {
                    $mensajeError=$value;
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
        } else {
            $servicio = array();
            $servicio = $this->loadioc($idaccion, $checkSeleccionado, $BuscarSubsistema, $BuscarServicio);
            $cantidad = count($servicio);
            $servicio = array_slice($servicio, $start, $limit);
            if ($cantidad == 0)
                echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfObjetos}");
            else {
                $result = array('cantidad' => $cantidad, 'datos' => $servicio);
                echo json_encode($result);
            }
        }
    }

    function modificarPermisosAction() {

        $RSA = new ZendExt_RSA_Facade();
        $arrayAccess = json_decode(stripslashes($this->_request->getPost('acceso')));
        $arrayDeny = json_decode(stripslashes($this->_request->getPost('denegado')));
        $idfuncionalidad = $this->_request->getPost('idfuncionalidad');
        $idaccion = $this->_request->getPost('idaccion');
        $criterio = $this->_request->getPost('criterio');
        $arrayEliminados = array();
        $arrayAdicionados = array();
        $idobjetobd = 0;

        $tipo = $this->TipoConexion();
        $idcriterio = NomObjetospermisos::obtenerIdCriterio($criterio);
        if ($tipo == 2 || $tipo == 3) {
            if (count($arrayDeny) > 0)
                $this->EliminarAdicionarPermisosOverActions($idaccion, $tipo, $arrayDeny, $idcriterio, "REVOKE ", false);
        }

        if ($criterio != "Servicios") {

            $criterio = NomObjetospermisos::obtenerIdCriterio($criterio);

            $ObjetoBdModel = new DatObjetobdModel();
            $DatAccionDatObjetobdModel = new DatAccionDatObjetobdModel();
            foreach ($arrayDeny as $value) {

                $idServidor = $value[0];
                $idgestor = $value[1];
                $idbd = $value[2];
                $idesquema = $value[3];
                $idrol = $value[4];
                $objeto = $value[5];
                $idobjetobd = $value[7];
                $permisos = $this->ArrayPermisosToString($value[6]);

                if ($idobjetobd != 0) {

                    $instanceDatAccionDatObjetoBD = DatAccionDatObjetobd::getById($idobjetobd, $idaccion);
                    if ($instanceDatAccionDatObjetoBD != null) {
                        $permisosCambiados = $this->EliminarPermisos($permisos, $instanceDatAccionDatObjetoBD->privilegios);

                        if ($permisosCambiados == "") {
                            $DatAccionDatObjetobdModel->eliminar($instanceDatAccionDatObjetoBD);
                            $existeObjetoAccion = DatAccionDatObjetobd::getBy_idobjetobd($idobjetobd);
                            $existeObjetoServicio = DatServicioObjetobd::getBy_idobjetobd($idobjetobd);
                            if (count($existeObjetoAccion) == 0 && count($existeObjetoServicio) == 0 && !$this->ExisteInArrayAccess($arrayAccess, $idobjetobd)) {
                                $arrayEliminados[] = $idobjetobd;
                                $instanceDatObjetoBd = DatObjetobd::getById($idobjetobd);
                                $ObjetoBdModel->eliminar($instanceDatObjetoBd);
                            }
                        } else {
                            $instanceDatAccionDatObjetoBD->privilegios = $permisosCambiados;
                            $DatAccionDatObjetobdModel->modificar($instanceDatAccionDatObjetoBD);
                        }
                    }
                }
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
                $permisos = $this->ArrayPermisosToString($value[6]);

                if ($idobjetobd != 0) {
                    $instanceDatAccionDatObjetoBD = DatAccionDatObjetobd::getById($idobjetobd, $idaccion);
                    if ($instanceDatAccionDatObjetoBD != null) {
                        $instanceDatAccionDatObjetoBD->privilegios =
                                $this->CombinarPermisos($instanceDatAccionDatObjetoBD->privilegios, $permisos);
                        $DatAccionDatObjetobdModel->modificar($instanceDatAccionDatObjetoBD);
                    } else {
                        $DatAccionObjetoBd = new DatAccionDatObjetobd();
                        $DatAccionObjetoBd->privilegios = $permisos;
                        $DatAccionObjetoBd->idaccion = $idaccion;
                        $DatAccionObjetoBd->idobjetobd = $idobjetobd;
                        $DatAccionDatObjetobdModel->insertar($DatAccionObjetoBd);
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
                    $DatAccionObjetoBd = new DatAccionDatObjetobd();
                    $DatAccionObjetoBd->privilegios = $permisos;
                    $DatAccionObjetoBd->idaccion = $idaccion;
                    $DatAccionObjetoBd->idobjetobd = $ObjetoBdNewObject->idobjetobd;
                    $idobjetobd = $ObjetoBdNewObject->idobjetobd;
                    $DatAccionDatObjetobdModel->insertar($DatAccionObjetoBd);
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
                if (count($arrayAccess) > 0)
                    $this->EliminarAdicionarPermisosOverActions($idaccion, $tipo, $arrayAccess, $idcriterio, "GRANT ");
            }
        }
        else {
            $model = new DatAccionDatServicioiocModel();
            $arrayIdServicios = array();
            foreach ($arrayDeny as $Deny) {
                $idservicio = $Deny[1];
                $arrayIdServicios[] = $idservicio;
                if ($idservicio != 0) {
                    $instancia = DatAccionDatServicioioc::getById($idservicio, $idaccion);
                    $model->Eliminar($instancia);
                    $exist_conAccion = DatAccionDatServicioioc::getBy_idServicio($idservicio);
                    $exist_conObjeto = DatServicioObjetobd::getBy_idservicio($idservicio);
                    if (count($exist_conAccion) == 0 && count($exist_conObjeto) == 0) {
                        $instancia = DatServicioioc::getById($idservicio);
                        $model->Eliminar($instancia);
                    }
                }
            }
            if (count($arrayDeny)) {
                $this->UpdateSevices($arrayIdServicios, $idaccion, $tipo, "REVOKE ", false);
            }
            $arrayIdServicios = array();
            foreach ($arrayAccess as $Access) {
                $subsistema = $Access[0];
                $idservicio = $Access[1];
                $servicio = $Access[2];
                if ($idservicio == 0) {
                    $newObj = new DatServicioioc();
                    $newObj->denominacion = $servicio;
                    $newObj->subsistema = $subsistema;
                    $model->Insertar($newObj);
                    $idservicio = $newObj->idservicio;
                }
                $arrayIdServicios[] = $idservicio;
                $newObj = new DatAccionDatServicioioc();
                $newObj->idservicio = $idservicio;
                $newObj->idaccion = $idaccion;
                $model->Insertar($newObj);
            }
            if (count($arrayAccess)) {
                $this->UpdateSevices($arrayIdServicios, $idaccion, $tipo, "GRANT ", true);
            }
        }


        $eliminados = json_encode($arrayEliminados);
        $adicionados = json_encode($arrayAdicionados);

        echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfPermisos,'elim':'$eliminados', 'add':'$adicionados'}");
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

    function mostrarMensajeAction() {
        $mensaje = $_SESSION['mensaje'];        
        if (count($mensaje)>0)
            echo("{'bien':1,'mensaje':perfil.etiquetas.lbMsgIPsError2,'Msg':'$mensaje'}");

        else {
            echo "{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgIPsError}";
        }
    }

    private function ObjetoAlmacenado($idservidor, $idgestor, $idbd, $idesquema, $objeto, $arrayObjetosBD) {
        foreach ($arrayObjetosBD as $ObjetoBD) {
            if ($ObjetoBD->idservidor == $idservidor && $ObjetoBD->idgestor == $idgestor && $ObjetoBD->idbd == $idbd && $ObjetoBD->idesquema == $idesquema && $ObjetoBD->objeto == $objeto) {
                return $ObjetoBD->idobjetobd;
            }
        }
        return 0;
    }

    private function UpdateSevices($arrayIdServicios, $idaccion, $tipo, $grant_revoke, $QuitarPonerEsquema) {
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $arregloObjetosPermisoOrdenado = $Pgsql->CrearPermisosSobreObjetosBDtoUpdateServices($idaccion, $arrayIdServicios, $grant_revoke);

        $roles = SegRol::ObtenerRolesFromAccionesObject(array($idaccion));
        $rolesArray = array();
        foreach ($roles as $Rol) {
            $arrayRole = array();
            $arrayRole['idrol'] = $Rol->idrol;
            $arrayRole['denominacion'] = $Rol->denominacion;
            $arrayRole['descripcion'] = $Rol->descripcion;
            $acciones = array();
            $acciones[] = array('idaccion' => $idaccion);
            $arrayRole['DatSistemaSegRolDatFuncionalidadDatAccion'] = $acciones;
            $rolesArray[] = $arrayRole;
        }

        $RolesXAcciones = $Pgsql->UnirRolesXAcciones($rolesArray);
        $Pgsql->EjecutarAdicionar_EliminarPermisosRoles($RolesXAcciones, $arregloObjetosPermisoOrdenado, $tipo, $QuitarPonerEsquema);
    }

    private function EliminarAdicionarPermisosOverActions($idaccion, $tipo, $arrayDenAcc, $idcriterio, $grant_revoke, $QuitarPonerEsquema = true) {
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $roles = SegRol::ObtenerRolesFromAccionesObject(array($idaccion));

        $arregloOrdenado = $this->OrdernarArrayDenyAcces($arrayDenAcc, $idaccion, $idcriterio);
        $arregloObjetosPermiso = $Pgsql->CrearConsultasPermisos($arregloOrdenado, $grant_revoke);
        $arregloObjetosPermisoOrdenado = array();
        $arregloObjetosPermisoOrdenado = $Pgsql->UnirXParametrosConexion($arregloObjetosPermiso);

        $rolesArray = array();
        foreach ($roles as $Rol) {
            $arrayRole = array();
            $arrayRole['idrol'] = $Rol->idrol;
            $arrayRole['denominacion'] = $Rol->denominacion;
            $arrayRole['descripcion'] = $Rol->descripcion;
            $acciones = array();
            $acciones[] = array('idaccion' => $idaccion);
            $arrayRole['DatSistemaSegRolDatFuncionalidadDatAccion'] = $acciones;
            $rolesArray[] = $arrayRole;
        }
        $RolesXAcciones = $Pgsql->UnirRolesXAcciones($rolesArray);
        $Pgsql->EjecutarAdicionar_EliminarPermisosRoles($RolesXAcciones, $arregloObjetosPermisoOrdenado, $tipo, $QuitarPonerEsquema);
    }

    private function OrdernarArrayDenyAcces($arrayDenyAcces, $idaccion, $idcriterio) {
        $result = array();
        $idgestor = "";
        $idrol = "";
        foreach ($arrayDenyAcces as $key => $array) {
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
            $permisos = $this->ArrayPermisosToString($array[6]);

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
                "accion_privilegio" => array
                    (
                    $idaccion => $idaccion
                ),
                "permiso" => $permisos
            );
        }
        return $result;
    }

    private function ArrayPermisosToString($arrayPermisos) {
        $permisos = "";
        $ListOfPrivileges = array();
        $ListOfPrivileges['SEL'] = "r";
        $ListOfPrivileges['INS'] = "a";
        $ListOfPrivileges['UPD'] = "w";
        $ListOfPrivileges['DEL'] = "d";
        $ListOfPrivileges['REF'] = "x";
        $ListOfPrivileges['TRIG'] = "t";
        $ListOfPrivileges['EXEC'] = "X";
        $ListOfPrivileges['USG'] = "U";
        foreach ($arrayPermisos as $value) {
            if ($value == "OWN") {
                $permisos = "rawdxt";
                break;
            } else {
                $permisos.=$ListOfPrivileges[$value];
            }
        }
        return $permisos;
    }

    private function ExisteInArrayAccess($arrayAccess, $idObjetobd) {
        foreach ($arrayAccess as $value) {
            if ($value[7] == $idObjetobd) {
                return true;
            }
        }
        return false;
    }

    private function loadioc($idaccion, $checkSeleccionado, $BuscarSubsistema, $BuscarServicio) {
        $register = Zend_Registry::getInstance();
        $IoC = ZendExt_FastResponse::getXML('ioc');

        $subsistemas = $IoC->children();
        $subsistemas_servicios = array();
        $cont = 0;
        $arregloIdServicio_Acciones = array();
        $arregloIdServicio_Acciones = Doctrine::getTable('DatAccionDatServicioioc')
                ->findByDql('idaccion=?', array($idaccion));
        $arraIdServicios = array();

        foreach ($arregloIdServicio_Acciones as $idServ_Acc) {
            $arraIdServicios[] = $idServ_Acc->idservicio;
        }
        $dat_servicioioc_Accion = array();
        if (count($arraIdServicios) > 0) {
            $dat_servicioioc_Accion = DatServicioioc::obtenerDatServicioIoC($arraIdServicios);
            $dat_servicioioc_Accion = $dat_servicioioc_Accion->toArray();
        }
        $dat_Servicio = Doctrine::getTable('DatServicioioc')->findAll();
        $dat_Servicio = $dat_Servicio->toArray();
        foreach ($subsistemas as $subsistema) {
            $subsistemaName = $subsistema->getName();
            if ($BuscarSubsistema == "") {
                $servicios = $IoC->$subsistemaName->children();
                foreach ($servicios as $servicio) {
                    $servicioName = $servicio->getName();
                    if ($BuscarServicio == "") {
                        $isServUsed = $this->ServicioUsado($dat_servicioioc_Accion, $servicioName, $subsistemaName);
                        $idServicio = $this->IdServicio($dat_Servicio, $servicioName, $subsistemaName);
                        if ($isServUsed == true) {
                            $subsistemas_servicios[$cont]['idservicio'] = $idServicio;
                            $subsistemas_servicios[$cont]['subsistema'] = $subsistemaName;
                            $subsistemas_servicios[$cont]['servicio'] = $servicioName;
                            $subsistemas_servicios[$cont]['USAR'] = $isServUsed;
                        } else if ($checkSeleccionado == 'false') {
                            $subsistemas_servicios[$cont]['idservicio'] = $idServicio;
                            $subsistemas_servicios[$cont]['subsistema'] = $subsistemaName;
                            $subsistemas_servicios[$cont]['servicio'] = $servicioName;
                            $subsistemas_servicios[$cont]['USAR'] = false;
                        }
                        $cont++;
                    } else {
                        if (stristr($servicioName, $BuscarServicio) != false) {
                            $isServUsed = $this->ServicioUsado($dat_servicioioc_Accion, $servicioName, $subsistemaName);
                            $idServicio = $this->IdServicio($dat_Servicio, $servicioName, $subsistemaName);
                            if ($isServUsed == true) {
                                $subsistemas_servicios[$cont]['idservicio'] = $idServicio;
                                $subsistemas_servicios[$cont]['subsistema'] = $subsistemaName;
                                $subsistemas_servicios[$cont]['servicio'] = $servicioName;
                                $subsistemas_servicios[$cont]['USAR'] = true;
                            } else if ($checkSeleccionado == 'false') {
                                $subsistemas_servicios[$cont]['idservicio'] = $idServicio;
                                $subsistemas_servicios[$cont]['subsistema'] = $subsistemaName;
                                $subsistemas_servicios[$cont]['servicio'] = $servicioName;
                                $subsistemas_servicios[$cont]['USAR'] = false;
                            }
                            $cont++;
                        }
                    }
                }
            } else {
                if (stristr($subsistemaName, $BuscarSubsistema) != false) {
                    $servicios = $IoC->$subsistemaName->children();
                    foreach ($servicios as $servicio) {
                        $servicioName = $servicio->getName();
                        if ($BuscarServicio == "") {
                            $isServUsed = $this->ServicioUsado($dat_servicioioc_Accion, $servicioName, $subsistemaName);
                            $idServicio = $this->IdServicio($dat_Servicio, $servicioName, $subsistemaName);
                            if ($isServUsed == true) {
                                $subsistemas_servicios[$cont]['idservicio'] = $idServicio;
                                $subsistemas_servicios[$cont]['subsistema'] = $subsistemaName;
                                $subsistemas_servicios[$cont]['servicio'] = $servicioName;
                                $subsistemas_servicios[$cont]['USAR'] = true;
                            } else if ($checkSeleccionado == 'false') {
                                $subsistemas_servicios[$cont]['idservicio'] = $idServicio;
                                $subsistemas_servicios[$cont]['subsistema'] = $subsistemaName;
                                $subsistemas_servicios[$cont]['servicio'] = $servicioName;
                                $subsistemas_servicios[$cont]['USAR'] = false;
                            }
                            $cont++;
                        } else {
                            if (stristr($servicioName, $BuscarServicio) != false) {
                                $isServUsed = $this->ServicioUsado($dat_servicioioc_Accion, $servicioName, $subsistemaName);
                                $idServicio = $this->IdServicio($dat_Servicio, $servicioName, $subsistemaName);
                                if ($isServUsed == true) {
                                    $subsistemas_servicios[$cont]['idservicio'] = $idServicio;
                                    $subsistemas_servicios[$cont]['subsistema'] = $subsistemaName;
                                    $subsistemas_servicios[$cont]['servicio'] = $servicioName;
                                    $subsistemas_servicios[$cont]['USAR'] = true;
                                } else if ($checkSeleccionado == 'false') {
                                    $subsistemas_servicios[$cont]['idservicio'] = $idServicio;
                                    $subsistemas_servicios[$cont]['subsistema'] = $subsistemaName;
                                    $subsistemas_servicios[$cont]['servicio'] = $servicioName;
                                    $subsistemas_servicios[$cont]['USAR'] = false;
                                }
                                $cont++;
                            }
                        }
                    }
                }
            }
        }

        return $subsistemas_servicios;
    }

    private function ServicioUsado($dat_servicioioc, $servicio, $sistema) {
        foreach ($dat_servicioioc as $servic) {
            if ($servic['subsistema'] == $sistema && $servic['denominacion'] == $servicio) {
                return true;
            }
        }
        return false;
    }

    private function IdServicio($dat_servicioioc, $servicio, $sistema) {
        foreach ($dat_servicioioc as $servic) {
            if ($servic['subsistema'] == $sistema && $servic['denominacion'] == $servicio) {
                return $servic['idservicio'];
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

    private function ExtraerConexionesRepetidas($datosConexion) {
        $cont = 0;
        $contenidos = array();
        $newdatosConexion = array();

        foreach ($datosConexion as $value) {
            $elementobuscar['idbd'] = $value['idbd'];
            $elementobuscar['idesquema'] = $value['idesquema'];
            $elementobuscar['idservidor'] = $value['idservidor'];
            $elementobuscar['idgestor'] = $value['idgestor'];
            $elementobuscar['idrolesbd'] = $value['idrolesbd'];

            if (!in_array($elementobuscar, $contenidos)) {
                $contenidos[$cont] = $elementobuscar;
                $newdatosConexion[] = $value;
                $cont++;
            }
        }

        return $newdatosConexion;
    }

    private function TratarCriterioBusqueda($objetoName) {
        $inserted = "";
        for ($i = 0; $i < strlen($objetoName); $i++) {
            if ($objetoName[$i] == "_") {
                $inserted.='!';
            }
            $inserted.=$objetoName[$i];
        }
        return $inserted;
    }

    private function arregloObjetosBD($datosConexion, $criterio, $BuscarServidor, $BuscarGestor, $BuscarBD, $BuscarEsquema, $BuscarObjeto) {

        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $RSA = new ZendExt_RSA_Facade();
        $arregloObjetos = array();
        $arregloObjetos['manajers'] = array();
        $arregloObjetos['objetos'] = array();
        $managersError = array();
        $ipsError = array();

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
            $BuscarObjeto = $this->TratarCriterioBusqueda($BuscarObjeto);
            $criterioBusqueda = "'%$BuscarObjeto%'";

            if (!in_array($ipservidor, $ipsPing))
                if (!$this->VerifyConnection("$gestor://$user:$pass@$ipservidor:$puerto/$bd")) {
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
        $result[$cont]['esquema'] = $esquema;
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

    private function CombinarPermisos($permisosHay, $permisosPoner) {
        $permisos = array();
        $permisos['r'] = false;
        $permisos['a'] = false;
        $permisos['w'] = false;
        $permisos['d'] = false;
        $permisos['x'] = false;
        $permisos['t'] = false;
        $permisos['X'] = false;
        $permisos['U'] = false;
        for ($index = 0; $index < strlen($permisosHay); $index++)
            $permisos[$permisosHay[$index]] = true;
        for ($index1 = 0; $index1 < count($permisosPoner); $index1++)
            $permisos[$permisosPoner[$index1]] = true;
        $combinado = "";
        foreach ($permisos as $key => $permiso) {
            if ($permiso)
                $combinado.=$key;
        }

        return $combinado;
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

    private function VerifyConnection($dsn) {
        $c = explode("@", $dsn);
        $ig = explode(":", $c[1]);
        $ip = $ig[0];
        if (PHP_OS == "Linux") {
            $str = exec("ping -c1 -W2 $ip", $input, $result);
        } else {
            $str = exec("ping -n 1 -w 1 $ip", $input, $result);
        }
        if ($result == 0) {
            return true;
        } else {
            return false;
        }
    }

}

?>
