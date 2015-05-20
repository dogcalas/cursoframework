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

class SegRolModel extends ZendExt_Model {

    public function SegRolModel() {
        parent::ZendExt_Model();
    }

    public function insertarrol($arraysistemarolfun, $arrayrolsist, $arrayobjacciones) {
        foreach ($arrayrolsist as $valorsist)
            $valorsist->save();
        foreach ($arraysistemarolfun as $valorsitrolfun)
            $valorsitrolfun->save();
        if ($arrayobjacciones)
            foreach ($arrayobjacciones as $valorobjacciones) {
                $valorobjacciones->save();
            }
    }

    public function modificarrol($rol_mod, $arraysistemasAdd, $arraysistemasElim, $arrayfuncaccionesAdd, $arrayfuncionalidadesElim, $rol) {
        $rol_mod->save();
        if (count($arrayfuncionalidadesElim)) {
            foreach ($arrayfuncionalidadesElim as $valorfunEliminar) {
                DatSistemaSegRolDatFuncionalidad::eliminarfuncionalidad($rol, $valorfunEliminar[1], $valorfunEliminar[0]);
            }
        }

        if (count($arraysistemasElim)) {
            foreach ($arraysistemasElim as $sistemaElim) {
                DatSistemaSegRol::eliminarrol($sistemaElim, $rol);
            }
        }

        if (count($arraysistemasAdd)) {
            foreach ($arraysistemasAdd as $rolsistemaAdd) {
                $rolsistemaAdd->save();
            }
        } elseif (!count($arrayfuncaccionesAdd))
            throw new ZendExt_Exception('SEGROL01');

        if (count($arrayfuncaccionesAdd)) {
            foreach ($arrayfuncaccionesAdd as $valor) {
                if (count($valor))
                    foreach ($valor as $valores) {
                        $valores->save();
                    }
            }
        }
    }

    function adicionaraccion($arrayAcciones) {
        foreach ($arrayAcciones as $objAccion) {
            $objAccion->save();
        }
    }

    public function modificarrolNuevo($rol_mod) {
        $rol_mod->save();
    }

    public function ObtenerRolesFromAccionesObject($acciones) {
        $array = SegRol::ObtenerRolesFromAcciones($acciones);
        return $array;
    }

    public function obtenerRegla($value, $funcionalidades) {
        $regla = DatReglas::obtenerRegla($value, $funcionalidades);
        return $regla;
    }

    public function nombreFuncionalidad($idfuncionalidad) {
        $nombre = DatFuncionalidad::nombreFuncionalidad($idfuncionalidad);
        return $nombre;
    }

    static function cargaraccion($fun, $x, $y) {
        $acciones = DatAccion::cargaraccion($fun, $x, $y);
        return $acciones;
    }

    public function TipoConexion() {
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->configConection;
        $DOM_XML_Conex = new DOMDocument();
        $contentfile = file_get_contents($dirconfigConection);
        $DOM_XML_Conex->loadXML($contentfile);
        $elements = SegRolModel::getElementsByAttr($DOM_XML_Conex, "seleccion", "true");
        $element = $elements->item(0);
        return $element->getAttribute('tipo');
    }

    public function getElementsByAttr($DOM, $nameAtrr, $value) {
        $xpath = new DOMXpath($DOM);
        $elements = $xpath->query("//*[@$nameAtrr='$value']");
        if ($elements->length > 0) {
            return $elements;
        }
        return false;
    }

    static function InsertarRolBD($rol, $arregloacciones, $tipo) {
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $Pgsql->CrearUnRolBD($rol, $arregloacciones, $tipo);
    }

    public function IdAccionesFromRolSistemasFunctionalidades($rol, $funcionalidadesID) {
        $accionesquitar = DatSistemaSegRolDatFuncionalidadDatAccion::IdAccionesFromRolSistemasFunctionalidades($rol, $funcionalidadesID);
        return $accionesquitar;
    }

    public function obtenerfunrol($rol, $func_en_sist) {
        $funcionalidades = DatSistemaSegRolDatFuncionalidad::obtenerfunrol($rol, $func_en_sist);
        return $funcionalidades;
    }

    static function eliminarrolsistema($rol, $func_en_sist) {
        DatSistemaSegRol::eliminarrolsistema($rol, $func_en_sist);
    }

    static function eliminarXSQL($rol, $idsistema, $idfuncionalidades) {
        DatSistemaSegRolDatFuncionalidad::eliminarXSQL($rol, $idsistema, $idfuncionalidades);
    }

    static function getRole($rol) {
        $rol_mod = SegRol::getRole($rol);
        return $rol_mod;
    }

    static function ModificarRolAndPermisosInRol($rol_mod, $tipo, $newName, $comment, $nameModified, $accionesAdicionar, $accionesEliminar) {
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $Pgsql->ModificarRolAndPermisosInRol($rol_mod, $tipo, $newName, $comment, $nameModified, $accionesAdicionar, $accionesEliminar);
    }

    static function EliminarRolesBD($idroles, $tipoConexion) {
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $Pgsql->EliminarRolesBD($idroles, $tipoConexion);
    }

    static function eliminarRolesAsignacion($idrol) {
        SegRolAsignacion::eliminarRoles($idrol);
    }

    static function eliminarRoles($idrol) {
        SegRol::eliminarRoles($idrol);
    }

    public function obtenerNombreRol($idnoElim) {
        $nombreRol = SegRol::obtenerNombreRol($idnoElim);
        return $nombreRol;
    }

    public function cargarentidadesusuariorol($idusuario, $idrol) {
        $entidadUsuarioRol = DatEntidadSegUsuarioSegRol::cargarentidadesusuariorol($idusuario, $idrol);
        return $entidadUsuarioRol;
    }

    public function comprobarrol($denominacion, $abreviatura) {
        $rol = SegRol::comprobarrol($denominacion, $abreviatura);        
        return $rol;
    }

    static public function salvarrol($denominacion, $descripcion, $abreviatura, $iddominio, $sistemas, $sistfun) {
        $rol = new SegRol();
        $rol->denominacion = $denominacion;
        $rol->descripcion = $descripcion;
        $rol->abreviatura = $abreviatura;        
        if (SegRolModel::verificarrol($rol->denominacion, ''))
                return 2;            
        if (SegRolModel::verificarrol('', $rol->abreviatura))
            return 3;
        $rol->save();

        $objRolDominio = new SegRolNomDominio();
        $objRolDominio->idrol = $rol->idrol;
        $objRolDominio->iddominio = $iddominio;
        $objRolDominio->save();

        $segRolAsignacion = new SegRolAsignacion();
        $segRolAsignacion->idrol = $rol->idrol;
        $segRolAsignacion->idrolasig = $rol->idrol;
        $segRolAsignacion->save();

        $arrayrolsist = array();
        foreach ($sistemas as $valorsist) {
            $rol_sist = new DatSistemaSegRol();
            $rol_sist->idsistema = $valorsist;
            $rol_sist->idrol = $rol->idrol;
            $arrayrolsist[] = $rol_sist;
        }

        $arraysistemarolfun = array();
        $arrayobjacciones = array();
        $arregloobjacciones = array();
        foreach ($sistfun as $valorsistfun)
            foreach ($valorsistfun[1] as $fun) {
                $sistema_rol_fun = new DatSistemaSegRolDatFuncionalidad();
                $sistema_rol_fun->idfuncionalidad = $fun;
                $sistema_rol_fun->idrol = $rol->idrol;
                $sistema_rol_fun->idsistema = $valorsistfun[0];
                $arraysistemarolfun[] = $sistema_rol_fun;
                $acciones = SegRolModel::cargaraccion($fun, 0, 0);
                $arrayacciones = $acciones->toArray();

                if (count($arrayacciones)) {

                    foreach ($arrayacciones as $accion) {
                        $objaccion = new DatSistemaSegRolDatFuncionalidadDatAccion();
                        $objaccion->idfuncionalidad = $fun;
                        $objaccion->idrol = $rol->idrol;
                        $objaccion->idsistema = $valorsistfun[0];
                        $objaccion->idaccion = $accion['idaccion'];
                        $arrayobjacciones[] = $objaccion;
                        $arregloobjacciones[] = $accion['idaccion'];
                    }
                }
            }
        SegRolModel::insertarrol($arraysistemarolfun, $arrayrolsist, $arrayobjacciones);
        $tipoConexion = SegRolModel::TipoConexion();
        if ($tipoConexion != 1 && $tipoConexion != 0)
            SegRolModel::InsertarRolBD($rol, $arregloobjacciones, $tipoConexion);
    }

    public function verificarrol($denominacion, $abreviatura) {        
        $rol = SegRolModel::comprobarrol($denominacion, $abreviatura);        
        if ($rol[0]['idrol'])
            return $rol;
        else            
            return 0;
    }

    static function modificarR($sist, $rol, $datsistemasegrol, $sistfun) {
        $datsistrolfunc = new DatSistemaSegRolDatFuncionalidadModel();
        $datsistrolfuncacc = new DatSistemaSegRolDatFuncionalidadDatAccionModel();
        foreach ($sist as $valorsist1) {
            $sistemaRol = Doctrine::getTable('DatSistemaSegRol')->findByDql("idrol=? AND idsistema=?", array($rol, $valorsist1))->toArray();
            if (count($sistemaRol) == 0) {
                $rol_sist = new DatSistemaSegRol();
                $rol_sist->idsistema = $valorsist1;
                $rol_sist->idrol = $rol;
                $datsistemasegrol->Adicionar($rol_sist);
            }
        }
        foreach ($sistfun as $valorsist) {
            foreach ($valorsist[1] as $funcionalidad) {
                $sistemaRolfunc = Doctrine::getTable('DatSistemaSegRolDatFuncionalidad')->
                                findByDql("idrol=? AND idsistema=? AND idfuncionalidad=?", array($rol, $valorsist[0], $funcionalidad))->toArray();
                if (count($sistemaRolfunc) == 0) {
                    $sistema_rol_fun = new DatSistemaSegRolDatFuncionalidad();
                    $sistema_rol_fun->idfuncionalidad = $funcionalidad;
                    $sistema_rol_fun->idrol = $rol;
                    $sistema_rol_fun->idsistema = $valorsist[0];
                    $datsistrolfunc->Adicionar($sistema_rol_fun);
                    $acciones = SegRolModel::cargaraccion($funcionalidad, 0, 0);
                    $arrayacciones = $acciones->toArray();

                    foreach ($arrayacciones as $accion) {
                        $objaccion = new DatSistemaSegRolDatFuncionalidadDatAccion();
                        $objaccion->idfuncionalidad = $funcionalidad;
                        $objaccion->idrol = $rol;
                        $objaccion->idsistema = $valorsist[0];
                        $objaccion->idaccion = $accion['idaccion'];
                        if (!in_array($accion['idaccion'], $accionesAdicionar))
                            $accionesAdicionar[] = $accion['idaccion'];
                        $datsistrolfuncacc->Adicionar($objaccion);
                    }
                }
            }
        }
    }

    function funcionalidadesElim($arraysistfun, $rol) {
        $resultado = array();
        foreach ($arraysistfun as $valor) {
            $resultado = array_merge($resultado, $valor[1]);
        }
        return $resultado;
    }

    function TratarCriterioBusqueda($objetoName) {
        $inserted = "";
        for ($i = 0; $i < strlen($objetoName); $i++) {
            if ($objetoName[$i] == "_") {
                $inserted.='!';
            }
            $inserted.=$objetoName[$i];
        }
        return $inserted;
    }

    function cantidadRoles($denominacion, $filtroDominio, $limit, $start) {
        if ($denominacion) {
            $denominacion = $this->TratarCriterioBusqueda($denominacion);
            $result = SegRol::obtenerrolBuscado($filtroDominio, $denominacion, $limit, $start);
            $cantrol = SegRol::cantrolBuscados($filtroDominio, $denominacion);
        } else {
            $result = SegRol::obtenerrol($filtroDominio, $limit, $start);
            $cantrol = SegRol::cantrol($filtroDominio);
        }
        return $result;
    }

    function tieneFuncionalidades($idsistema, $rol) {
        return DatSistemaSegRolDatFuncionalidad::tieneFuncionalidad($rol, $idsistema);
    }

    function cargarSistemas($idsistema, $idnodo) {
        if ($idnodo == 0) {
            $sistemas = DatSistema::cargarArbolSistemasCompartimentacion($idnodo);
            if (!count($sistemas)) {
                echo json_encode(array());
                return;
            }
        }
        else
            $sistemas = DatSistema::cargarArbolSistemasCompartimentacion($idsistema);
        return $sistemas;
    }

    function tienePermisoFuncionalidades($raiz, $idrol, $bandera) {
        $tiene = DatSistemaSegRolDatFuncionalidad::tieneFuncionalidad($idrol, $raiz);
        if ($tiene) {
            $bandera = $raiz;
            return 1;
        } else {
            $sistemashijos = DatSistema::cargarsistemahijos($raiz, $idrol);
            if (count($sistemashijos)) {
                foreach ($sistemashijos as $hijo) {
                    if ($bandera != $raiz)
                        $this->tienePermisoFuncionalidades($hijo['idsistema'], $idrol, $bandera);
                    return 1;
                }
                return 0;
            }
            else
                return 0;
        }
    }

    function obtenercantfuncCompart($valor) {
        $resultado = DatFuncionalidad::obtenercantfuncCompart($valor);
        return $resultado;
    }

    function cargarFuncionalidadesCompart($idsistema) {
        $funcionalidad = DatFuncionalidad::cargarFuncionalidadesCompart($idsistema);
        return $funcionalidad;
    }

    function cargarentidadesrol($idrol) {
        $entidadesrol = DatSistemaDatEntidadSegRol::cargarentidadesrol($idrol);
        return $entidadesrol;
    }

    function cargarentidades() {
        $entidadesbd = DatSistemaDatEntidad::cargarentidades();
        return $entidadesbd;
    }

    function chequear($id, $rol, $idsistema) {
        $funrol = DatSistemaSegRolDatFuncionalidad::obtenerfunrol($rol, $idsistema);
        $arreglo = $funrol->toArray();
        foreach ($arreglo as $val) {
            if ($val['idfuncionalidad'] == $id)
                return true;
        }
        return false;
    }

    function existesistema($arraysistemas, $sistema) {
        foreach ($arraysistemas as $key => $sist) {

            if ($sist['idsistema'] == $sistema)
                return $key;
        }

        return -1;
    }

    function existefuncionalidad($arrayfun, $fun) {
        foreach ($arrayfun as $key => $funcionalidad)
            if ($funcionalidad['idfuncionalidad'] == $fun)
                return $key;
        return -1;
    }

    function sistemasAdd($arrayGeneral, $arrayNuevo, $rol) {
        $resultado = array();
        foreach ($arrayNuevo as $valor) {
            $pos = $this->existesistema($arrayGeneral, $valor);
            if ($pos == -1) {
                $rol_sist = new DatSistemaSegRol();
                $rol_sist->idsistema = $valor;
                $rol_sist->idrol = $rol;
                $resultado[] = $rol_sist;
            }
        }
        return $resultado;
    }

    function cargaraccionesquetiene($idsistema, $idrol, $idfuncionalidad) {
        $datoaccion = DatSistemaSegRolDatFuncionalidadDatAccion::cargaraccionesquetiene($idsistema, $idrol, $idfuncionalidad);
        return $datoaccion;
    }

    function todaslasaccionesquetiene($idsistema, $idrol, $idfuncionalidad) {
        $cantfilas = DatSistemaSegRolDatFuncionalidadDatAccion::todaslasaccionesquetiene($idsistema, $idrol, $idfuncionalidad);
        return $cantfilas;
    }

    public function cargarsistemafunc($idnodo, $idsistema, $rol){
        if ($idnodo == 0)
            $sistemas = $this->cargarsistemasdelrol($idnodo, $rol);
        else
            $sistemas = $this->cargarsistemasdelrol($idsistema, $rol);

        $valores = 0;
        if (count($sistemas)) {
            foreach ($sistemas as $valor) {
                $funcionalidadArr[$valores]['id'] = $valor['id'] . '_' . $idnodo;
                $funcionalidadArr[$valores]['idsistema'] = $valor['id'];
                $funcionalidadArr[$valores]['text'] = $valor['text'];
                $valores++;
            }
        }
        if ($idnodo) {
            $funcionalidad = $this->cargarsist_funcionalidades($idsistema, $rol);
            if ($funcionalidad->getData() != NULL) {
                foreach ($funcionalidad as $valorfun) {
                    $funcionalidadArr[$valores]['id'] = $valorfun->id . '_' . $idnodo;
                    $funcionalidadArr[$valores]['idfuncionalidad'] = $valorfun->id;
                    $funcionalidadArr[$valores]['text'] = $valorfun->text;
                    $funcionalidadArr[$valores]['idsistema'] = $valorfun->idsistema;
                    $funcionalidadArr[$valores]['leaf'] = true;
                    $valores++;
                }
            }
        }
        return $funcionalidadArr;
    }

    function cargarsistemasdelrol($valor1, $valor2) {
        $sistemas = DatSistema::cargarsistemasdelrol($valor1, $valor2);
        return $sistemas;
    }

    function cargarsist_funcionalidades($idsistema, $rol) {
        $funcionalidad = DatSistemaSegRolDatFuncionalidad::cargarsist_funcionalidades($idsistema, $rol);
        return $funcionalidad;
    }

    function cargarAcciCompartimentacion($idfuncionalidad, $idrol) {
        $acciones = DatAccion::cargarAcciCompartimentacion($idfuncionalidad, $idrol);
        return $acciones;
    }

    function cargarAccionesNoTiene($idfuncionalidad, $arrayAcc) {
        $accionesNoTiene = DatAccion::cargarAccionesNoTiene($idfuncionalidad, $arrayAcc)->toArray();
        return $accionesNoTiene;
    }

    function arregloBidimensionalToUnidimensional($arrayAcciones) {
        $array = array();
        foreach ($arrayAcciones as $acciones)
            $array[] = $acciones->idaccion;
        return $array;
    }

    function crearObjeto() {
        $accion = new DatSistemaSegRolDatFuncionalidadDatAccion();
        return $accion;
    }

    function cambiarAccion($acciones, $tipo, $rol_mod, $idrol) {
        $this->adicionaraccion($acciones);
        $tipo = $this->TipoConexion();
        $rol_mod = SegRol::getRole($idrol);
    }

    function quitarAccion($idsistema, $idfuncionalidad, $idrol, $accionesEliminar, $tipo, $rol_mod) {
        DatSistemaSegRolDatFuncionalidadDatAccion::eliminaraccion($idsistema, $idfuncionalidad, $idrol, $accionesEliminar);
        $tipo = $this->TipoConexion();
        $rol_mod = SegRol::getRole($idrol);
    }

     public function EliminarPadreDatSistemaSegRol($rol, $sistema, $datsistemasegrol) {
        $padre = DatSistema::ObtenerPadre($sistema);
        $padre = $padre[0]['idpadre'];
        if ($padre != $sistema) {
            $hijos = DatSistema::cargarsistemahijjos($padre);
            $arrayHijos = array();
            $where = "AND (";
            $primera = true;
            foreach ($hijos as $hijos) {
                if ($primera) {
                    $primera = false;
                    $where.=" idsistema=? ";
                }
                else
                    $where.=" OR idsistema=?";
                $arrayHijos[] = $hijos->idsistema;
            }
            $where.=")";
            array_unshift($arrayHijos, $rol);

            $sistemaRol = Doctrine::getTable('DatSistemaSegRol')->
                    findByDql("idrol=? $where", $arrayHijos);
            $funcSisP = Doctrine::getTable('DatSistemaSegRolDatFuncionalidad')->
                    findByDql("idrol=? AND idsistema=?", array($rol, $padre));

            if (count($sistemaRol) == 0 && count($funcSisP) == 0) {
                DatSistemaSegRol::eliminarrolsistema($rol, $padre);
                $this->EliminarPadreDatSistemaSegRol($rol, $padre, $datsistemasegrol);
            }
        }
    }

}

?>