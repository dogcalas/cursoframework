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

class GestrolController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestrolAction() {
        $this->render();
    }

    function insertarrolAction() {        
        $array_sistfun = $this->_request->getPost('arraysistfun');
        $array_sistemas = $this->_request->getPost('arraysist');
        $sistfun = json_decode(stripslashes($array_sistfun));
        $sistemas = json_decode(stripslashes($array_sistemas));        
        $var= SegRolModel::salvarrol($this->_request->getPost('denominacion'), $this->_request->getPost('descripcion'), $this->_request->getPost('abreviatura'), $this->global->Perfil->iddominio, $sistemas, $sistfun);
         if($var==2){
         throw new ZendExt_Exception('SEG018');
         }  else {
           if($var==3)
               throw new ZendExt_Exception('SEG019');                          
         }
           echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgAddRole}");
    }

    function modificarrolAction() {
        $model = new SegRolModel;
        $rol = $this->_request->getPost('idrol');
        $arrayeliminar = json_decode(stripslashes($this->_request->getPost('arrayeliminar')));
        $datsistemasegrol = new DatSistemaSegRolModel();        
        $accionesEliminar = array();
        $accionesAdicionar = array();
        $funcionalidadesID = array();
        foreach ($arrayeliminar as $func_en_sist) {
            $funcionalidadesID = array_merge($funcionalidadesID, $func_en_sist[1]);
        }
        if (count($arrayeliminar)) {
            $accionesquitar = $model->IdAccionesFromRolSistemasFunctionalidades($rol, $funcionalidadesID);
            foreach ($accionesquitar as $value)
                $accionesEliminar[] = $value['idaccion'];
        }
        foreach ($arrayeliminar as $func_en_sist) {
            $funcionalidades = $model->obtenerfunrol($rol, $func_en_sist[0]);
            if (count($funcionalidades) == count($func_en_sist[1])) {
                SegRolModel::eliminarrolsistema($rol, $func_en_sist[0]);
                $model->EliminarPadreDatSistemaSegRol($rol, $func_en_sist[0], $datsistemasegrol);
            }
            else
                SegRolModel::eliminarXSQL($rol, $func_en_sist[0], $func_en_sist[1]);
        }

        $sistfun = json_decode(stripslashes($this->_request->getPost('arraysistfun')));
        $sist = json_decode(stripslashes($this->_request->getPost('arraysist')));
            SegRolModel::modificarR($sist, $rol, $datsistemasegrol, $sistfun);
        $arrayfuncionalidadesElim = $model->funcionalidadesElim($arrayeliminar, $rol);
        if ($rol == '10000000001')
            $this->validarFuncAdministracion($arrayfuncionalidadesElim);
        $denominacion = $this->_request->getPost('denominacion');
        $abreviatura = $this->_request->getPost('abreviatura');
        $descripcion = $this->_request->getPost('descripcion');        
        $rol_mod = SegRolModel::getRole($rol);
        $rolAnt = $rol_mod;        
        $lastdenominacion = $rol_mod->denominacion;        
        $lastabreviatura = $rol_mod->abreviatura;
        if($lastdenominacion!=$denominacion){            
            if($model->verificarrol($denominacion, ''))                    
                    throw new ZendExt_Exception('SEG018');        
        }        
        if($lastabreviatura!=$abreviatura){                
             if($model->verificarrol('',$abreviatura))
               throw new ZendExt_Exception('SEG019');           
        }        
        $tipoConexion = $model->TipoConexion();
        if ($tipoConexion == 2 || $tipoConexion == 3)
            SegRolModel::ModificarRolAndPermisosInRol($rol_mod, $tipoConexion, $denominacion, $descripcion, $lastdenominacion != $denominacion, $accionesAdicionar, $accionesEliminar);
        $rol_mod->denominacion = $denominacion;
        $rol_mod->descripcion = $descripcion;
        $rol_mod->abreviatura = $abreviatura;
        $model->modificarrolNuevo($rol_mod); 
        echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgModRole}");
    }

    function eliminarrolAction() {
        $model = new SegRolModel();
        $idroles = array();
        $idnoElim = -1;
        $roles = json_decode(stripslashes($this->_request->getPost('arrayRolesElim')));
        for ($i = 0; $i < count($roles); $i++) {
            if ($idnoElim == -1)                
                $idnoElim = $this->VerificarNoEliminarRolInUso($roles[$i]);
            if ($idnoElim == -1) {
                $idroles[] = $roles[$i];
            }
        }
        $tipoConexion = $model->TipoConexion();
        $model->EliminarRolesBD($idroles, $tipoConexion);
        foreach ($roles as $idrol) {
            if ($idnoElim == -1) {
                $model->eliminarRolesAsignacion($idrol);
                $model->eliminarRoles($idrol);
            }
        }
        if ($idnoElim == -1)
           echo"{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgDelRole}";
        else {
            $nombreRol = $model->obtenerNombreRol($idnoElim);
            $denominacion = $nombreRol[0]['denominacion'];
            echo"{'codMsg':3,'mensaje':perfil.etiquetas.lbMsgDelRoleErr}";
        }
    }

    private function VerificarNoEliminarRolInUso($idrol) {
        $model = new SegRolModel();
        $idusuario = $this->global->Perfil->idusuario;
        $entidadUsuarioRol = $model->cargarentidadesusuariorol($idusuario, $idrol);        
        if (count($entidadUsuarioRol) > 0) {
            return $idrol;
        }
        return -1;
    }

    private function validarFuncAdministracion($array) {
        foreach ($array as $valores) {
            if ($valores == '19')
                throw new ZendExt_Exception('SEGROL01');
        }
    }

    function cargarrolAction() {
        $start = $this->_request->getPost("start");
        $limit = $this->_request->getPost("limit");
        $denominacion = $this->_request->getPost("denrol");
        $filtroDominio = $this->global->Perfil->iddominio;
        $model = new SegRolModel();
        $result = $model->cantidadRoles($denominacion, $filtroDominio, $limit, $start);
        if (count($result)) {
            foreach ($result as $key => $rol) {
                $arrayroles[$key]['idrol'] = $rol->idrol;
                $arrayroles[$key]['denominacion'] = $rol->denominacion;
                $arrayroles[$key]['abreviatura'] = $rol->abreviatura;
                $arrayroles[$key]['descripcion'] = $rol->descripcion;
            }
            $result = array('cantidad_filas' => $cantrol, 'datos' => $arrayroles);
            echo json_encode($result);
            return;
        } else {
            $arrayroles = array();
            $result = array('cantidad_filas' => 0, 'datos' => $arrayroles);
            echo json_encode($result);
            return;
        }
    }

    function cargarsistemafuncionalidadesAction() {
        $idnodo = $this->_request->getPost('node');
        $idsistema = $this->_request->getPost('idsistema');
        $rol = $this->_request->getPost('idrol');
        $model = new SegRolModel();
        $sistemas = $model->cargarSistemas($idsistema, $idnodo);        
        $a = 0;
        if (count($sistemas)) {            
            $sistemafunArr = array();
            foreach ($sistemas as $valor) {                
                $sistemafunArr[$a]['id'] = $valor['id'] . '_' . $idnodo;
                $sistemafunArr[$a]['idsistema'] = $valor['idsistema'];
                $sistemafunArr[$a]['text'] = $valor['text'];                
                $tiene = $model->tieneFuncionalidades($valor['id'], $rol);                
                if ($tiene) {
                    $sistemafunArr[$a]['tiene'] = 1;
                    if ($rol)
                        $sistemafunArr[$a]['icon'] = '../../views/images/folder.png';
                }
                if (!$tiene && $rol) {                    
                    $bandera = 0;
                    $validaacceso = $model->tienePermisoFuncionalidades($valor['id'], $rol, $bandera);
                    if ($validaacceso)
                        $sistemafunArr[$a]['icon'] = '../../views/images/folder.png';
                }
                if ($valor['leaf']) {                    
                    $resultado = $model->obtenercantfuncCompart($valor['id']);                    
                    if (!$resultado)
                        $sistemafunArr[$a]['leaf'] = true;
                    else    
                      $sistemafunArr[$a]['leaf'] = false;                    
                }
                else
                    $sistemafunArr[$a]['leaf'] = false;
                $a++;                
            }            
        }        
        if ($idsistema != 0) {
            $funcionalidad = $model->cargarFuncionalidadesCompart($idsistema);

            if ($funcionalidad->getData() != NULL) {
                foreach ($funcionalidad as $valor) {
                    $sistemafunArr[$a]['id'] = $valor['id'] . '_' . $idnodo;
                    $sistemafunArr[$a]['idfuncionalidad'] = $valor['id'];
                    $sistemafunArr[$a]['text'] = $valor['text'];
                    $sistemafunArr[$a]['leaf'] = true;
                    if ($rol)
                        $sistemafunArr[$a]['checked'] = $model->chequear($valor['id'], $rol, $idsistema) ? true : false;
                    else
                        $sistemafunArr[$a]['checked'] = false;
                    $a++;
                }
            }
        }        
        echo json_encode($sistemafunArr);
        return;
    }

    function cargarentidadesAction() {
        $idrol = $this->_request->getPost('idrol');
        $model = new SegRolModel();
        if ($idrol) {
            $entidadesrol = $model->cargarentidadesrol($idrol);
            $datosentidad = $this->integrator->metadatos->MostrarCamposEstructuraSeguridad($entidadesrol[0]->identidad);
            $arrayentidades[0]['id'] = $datosentidad[0]['id'];
            $arrayentidades[0]['text'] = $datosentidad[0]['text'];
            $arrayentidades[0]['leaf'] = true;
        } else {
            $entidadesbd = $model->cargarentidades();
            foreach ($entidadesbd as $key => $entidad) {
                $datosentidad = $this->integrator->metadatos->MostrarCamposEstructuraSeguridad($entidad->identidad);
                $arrayentidades[$key]['id'] = $datosentidad[0]['id'];
                $arrayentidades[$key]['text'] = $datosentidad[0]['text'];
                $arrayentidades[$key]['leaf'] = true;
            }
        }
        echo json_encode($arrayentidades);
        return;
    }

    function cargaraccionesquetieneAction() {
        $idsistema = $this->_request->getPost('idsistema');
        $idrol = $this->_request->getPost('idrol');
        $idfuncionalidad = $this->_request->getPost('idfuncionalidad');
        $model = new SegRolModel();
        $datoaccion = $model->cargaraccionesquetiene($idsistema, $idrol, $idfuncionalidad);
        $cantfilas = $model->todaslasaccionesquetiene($idsistema, $idrol, $idfuncionalidad);
        $datos = $datoaccion->toArray();
        $result = array('cantidad_filas' => $cantfilas, 'datos' => $datos);
        echo json_encode($result);
        return;
    }

    function cargarsistemafuncAction() {
        $idnodo = $this->_request->getPost('node');
        $idsistema = $this->_request->getPost('idsistema');
        $rol = $this->_request->getPost('idrol');
        $model = new SegRolModel();
        $funcionalidadArr = $model->cargarsistemafunc($idnodo, $idsistema, $rol);
        echo json_encode($funcionalidadArr);
        return;
    }

    function cargaraccionesquenotieneAction() {
        $idsistema = $this->_request->getPost('idsistema');
        $idfuncionalidad = $this->_request->getPost('idfuncionalidad');
        $idrol = $this->_request->getPost('idrol');
        $model = new SegRolModel();
        $acciones = $model->cargarAcciCompartimentacion($idfuncionalidad, $idrol);
        $arrayAcc = $model->arregloBidimensionalToUnidimensional($acciones);
        $accionesNoTiene = $model->cargarAccionesNoTiene($idfuncionalidad, $arrayAcc);
        $cantidad = count($accionesNoTiene);
        echo json_encode(array('cantidad_filas' => $cantidad, 'datos' => $accionesNoTiene));
    }

    function adicionaraccionAction() {
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $idsistema = $this->_request->getPost('idsistema');
        $idfuncionalidad = $this->_request->getPost('idfuncionalidad');
        $idrol = $this->_request->getPost('idrol');
        $accs = json_decode(stripslashes($this->_request->getPost('idaccion')));
        $model = new SegRolModel();
        $idaccionesRelacionadas = array();
        foreach ($accs as $idaccion) {
            $accion = $model->crearObjeto();
            $accion->idsistema = $idsistema;
            $accion->idfuncionalidad = $idfuncionalidad;
            $accion->idrol = $idrol;
            $accion->idaccion = $idaccion;
            $acciones[] = $accion;
            $idaccionesRelacionadas[] = $idaccion;
        }
        $rol_mod = 0;
        $tipo = 0;
        $model->cambiarAccion($acciones, $tipo, $rol_mod, $idrol);
        if ($tipo == 2 || $tipo == 3) {
            $Pgsql->AdicionarEliminarPrivilegiosOverRole($rol_mod, $idaccionesRelacionadas, $tipo, "GRANT ");
        }
        echo "{'tiene':1}";
    }

    function eliminaraccionAction() {
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $idsistema = $this->_request->getPost('idsistema');
        $idrol = $this->_request->getPost('idrol');
        $idfuncionalidad = $this->_request->getPost('idfuncionalidad');
        $accionesEliminar = json_decode(stripslashes($this->_request->getPost('idaccion')));
        $model = new SegRolModel();
        if ($idrol == '10000000001')
            $this->verificarAcciones($accionesEliminar);
        $tipo = 0;
        $rol_mod = 0;
        $model->quitarAccion($idsistema, $idfuncionalidad, $idrol, $accionesEliminar, $tipo, $rol_mod);
        if ($tipo == 2 || $tipo == 3) {
            $Pgsql->AdicionarEliminarPrivilegiosOverRole($rol_mod, $accionesEliminar, $tipo, "REVOKE ");
        }
        echo "{'tiene':1}";
    }

    private function verificarAcciones($accionesEliminar) {
        foreach ($accionesEliminar as $valor)
            if ($valor == '70')
                throw new ZendExt_Exception('SEGROL02');
    }

}
?>
