<?php

/*
 * @author Miguel la llave
 * @nombre GestcompartimentacionrecursosController
 * @clase para gestionar los permisos sobre los recursos de ACAXIA
 * @Version 1.0 
 */

class GestcompartimentacionrecursosController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestcompartimentacionrecursosAction() {
        $this->render();

        /*
          if(strtoupper(substr(PHP_OS, 0, 5)) === 'LINUX'){
          $extensiones=get_loaded_extensions();
          $raiz= PHP_SYSCONFDIR;
          $mongodir=$raiz.'/'.'mongodb.conf';
          if(in_array('mongo',$extensiones)&& file_exists($mongodir)){
          $this->render();
          }else{
          throw new ZendExt_Exception('ECR04');
          }

          }  else if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

          }
         */
    }

    /* @author Miguel la llave 
     * @nombre eliminarPermisoAction
     * @funcion para eliminar un permiso a un usuario sobre un recurso
     * @return void
     * @parametros 
     */

    public function eliminarPermisoAction() {
        $idacl = $this->global->Perfil->iddominio;
        $idrol = $this->_request->getPost("idrol");
        $idusuario = $this->_request->getPost("idusuario");
        $permiso = $this->_request->getPost("permiso");
        $recurso = $this->_request->getPost("recurso");
        $identidad = $this->_request->getPost('identidad');
        $rolacl = $idusuario . '_' . $idrol . '_' . $identidad;
        $mongo = new ZendExt_Mongo();
        $mongo->eliminarPermisodeRol($idacl, $rolacl, $permiso, $recurso);

        if (!$mongo->EstaPermiso($idacl, $permiso, $recurso, $rolacl)) {
            $this->showMessage('Permiso eliminado satisfactoriamente');
        } else {
            $this->showMessage('No se ha eliminado ningun permiso');
        }
    }

    /* @author Miguel la llave 
     * @nombre cargarReglasAction	
     * @funcion para cargar la reglas del permiso seleccionado en el grid
     * @return array
     * @parametros  
     */

    function cargarReglasAction() {
        $idrol = $this->_request->getPost("idrol");
        $idusuario = $this->_request->getPost("idusuario");
        $permiso = $this->_request->getPost("permiso");
        $recurso = $this->_request->getPost("recurso");
        $identidad = $this->_request->getPost('identidad');
        $arrayReglas = array();
        $idacl = $this->global->Perfil->iddominio;
        $rolacl = $idusuario . '_' . $idrol . '_' . $identidad;
        $buscado = array('rolacl' => (string) $rolacl, 'permiso' => (string) $permiso, 'recurso' => (string) $recurso);
        $mongoobj = new ZendExt_Mongo();
        $datos = $mongoobj->buscarPorIdAcl($idacl);

        foreach ($datos as $dat) {
            $busca['rolacl'] = $dat['rolacl'];
            $busca['permiso'] = $dat['permiso'];
            $busca['recurso'] = $dat['recurso'];
            $este = $busca;

            if ($este == $buscado) {
                $regla['nombreregla'] = $dat['nombreregla'];
                $regla['campo'] = $dat['campo'];
                $regla['operador'] = $dat['operador'];
                $regla['valor'] = $dat['valor'];
                if (!in_array($regla, $arrayReglas))
                    $arrayReglas[] = $regla;
            }
        }
        array_unique($arrayReglas);
        echo json_encode(array('datos' => $arrayReglas));
    }

    /* @author Miguel la llave
     * @nombre cargarComboRecursosAction	
     * @funcion para cargar los recursos del xml
     * @return array
     * @parametros  
     */

    function cargarComboRecursosAction() {


        $recursos = $this->cargarRecursosXml();
        echo(json_encode(array('recursos' => $recursos)));
        return;
    }

    /* @author Miguel la llave 
     * @nombre cargarComboCriteriosAction
     * @funcion para cargar los criterios del recurso seleccionado
     * @return array
     * 
     */

    function cargarComboCriteriosAction() {
        $model=new CompartimentacionrecursosModel;
        $table_name = $model->cargaTableNameXml($this->_request->getPost('recurso'));                
        $permiso = $this->_request->getPost('permiso');
        $criterios = $this->cargaCriteriosXml($table_name, $permiso);
        echo(json_encode(array('criterios' => $criterios)));
        return;
    }

    /* @author Miguel la llave 
     * @nombre cargarComboOperadoresAction
     * @funcion para cargar los operadores en dependencia del tipo de dato del criterio seleccionado
     * @return array(=,<,>,etc...)
     * @parametros 
     */

    function cargarComboOperadoresAction() {
        $tipo = $this->_request->getPost('tipo');
        if ($tipo == 'booleano' || $tipo == 'cadena') {
            echo'{"operadores":[{"idoperador":"1","denominacion":"Igual a","valor":"="},{"idoperador":"2","denominacion":"Distinto de","valor":"<>"}]}';
        } else {
            echo'{"operadores":[{"idoperador":"1","denominacion":"Igual a","valor":"="},{"idoperador":"2","denominacion":"Distinto de","valor":"<>"},{"idoperador":"3","denominacion":"Mayor que","valor":">"},{"idoperador":"4","denominacion":"Menor que","valor":"<"},{"idoperador":"5","denominacion":"Mayor o igual","valor":">="},{"idoperador":"6","denominacion":"Menor o igual","valor":"<="}]}';
        }
    }

    /* @author Miguel la llave 
     * @nombre cargarRecursosXml
     * @funcion para leer xml y obtener los nombre de los recursos
     * @return array(recurso-1,recurso-2,...recurso-n)
     * @parametros 
     */

    public function cargarRecursosXml() {

        $xml = ZendExt_FastResponse::getXML('recursos');
        $recursos = array();

        foreach ($xml->children() as $recu) {
            $table['table_name'] = (string) $recu['table_name'];
            $alia['alias'] = (string) $recu['alias'];
            $id['id'] = (string) $recu['id'];

            $recursos[] = $id + $alia + $table;
        }

        return $recursos;
    }

    /* @author Miguel la llave 
     * @nombre cargaCriteriosXml
     * @funcion para leer xml y obtener los critrios de los recursos,dado la tabla, y el permiso
     * @return array(criterio-1,criterio-2,...criterio-n)
     * @parametros  nombre de la tabla y el permiso para buscar los criterios asociados a estos
     */

    public function cargaCriteriosXml($table_name, $permiso) {
        $xml = ZendExt_FastResponse::getXML('recursos');
        $criterios = array();
        if ($permiso == 'modificar') {
            foreach ($xml->children() as $recu) {
                if ((string) $recu['table_name'] == (string) $table_name) {

                    foreach ($recu->children() as $crits) {
                        if ((string) $crits->getName() == 'criteriosmodificar')
                            foreach ($crits->children() as $crit) {
                                $alia['alias'] = (string) $crit['alias'];
                                $tipodato['tipodato'] = (string) $crit['tipodato'];
                                $subsistema['subsistema'] = (string) $crit['subsistema'];
                                $service['service'] = (string) $crit['service'];
                                $needparam['needparam'] = (string) $crit['needparam'];
                                $criterios[] = $alia + $tipodato + $subsistema + $service + $needparam;
                            }
                    }
                }
            }
        } else if ($permiso == 'eliminar') {

            foreach ($xml->children() as $recu) {
                if ((string) $recu['table_name'] == (string) $table_name) {

                    foreach ($recu->children() as $crits) {
                        if ((string) $crits->getName() == 'criterioseliminar')
                            foreach ($crits->children() as $crit) {
                                $alia['alias'] = (string) $crit['alias'];
                                $tipodato['tipodato'] = (string) $crit['tipodato'];
                                $subsistema['subsistema'] = (string) $crit['subsistema'];
                                $service['service'] = (string) $crit['service'];
                                $needparam['needparam'] = (string) $crit['needparam'];
                                $criterios[] = $alia + $tipodato + $subsistema + $service + $needparam;
                            }
                    }
                }
            }
        } else if ($permiso == 'ver') {

            foreach ($xml->children() as $recu) {
                if ((string) $recu['table_name'] == (string) $table_name) {

                    foreach ($recu->children() as $crits) {
                        if ((string) $crits->getName() == 'criteriosver')
                            foreach ($crits->children() as $crit) {
                                $alia['alias'] = (string) $crit['alias'];
                                $tipodato['tipodato'] = (string) $crit['tipodato'];
                                $subsistema['subsistema'] = (string) $crit['subsistema'];
                                $service['service'] = (string) $crit['service'];
                                $needparam['needparam'] = (string) $crit['needparam'];
                                $criterios[] = $alia + $tipodato + $subsistema + $service + $needparam;
                            }
                    }
                }
            }
        } else if ($permiso == 'insertar') {

            foreach ($xml->children() as $recu) {
                if ((string) $recu['table_name'] == (string) $table_name) {

                    foreach ($recu->children() as $crits) {
                        if ((string) $crits->getName() == 'criteriosinsertar')
                            foreach ($crits->children() as $crit) {
                                $alia['alias'] = (string) $crit['alias'];
                                $tipodato['tipodato'] = (string) $crit['tipodato'];
                                $subsistema['subsistema'] = (string) $crit['subsistema'];
                                $service['service'] = (string) $crit['service'];
                                $needparam['needparam'] = (string) $crit['needparam'];
                                $criterios[] = $alia + $tipodato + $subsistema + $service + $needparam;
                            }
                    }
                }
            }
        }

        return $criterios;
    }

    /* @author Miguel la llave 
     * @nombre cargarUsuariosAction
     * @funcion para cargar los usuarios de la base de datos
     * @return array (user-1,user-2,...user-n)
     * @parametros  
     */

    function cargarUsuariosAction() {
        $model=new CompartimentacionrecursosModel;
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $idusuario = $this->global->Perfil->idusuario;
        $nombreusuario = $this->_request->getPost('nombreusuario');
        $dominiobuscar = $this->_request->getPost('dominiobuscar');
        $iddominio = $this->global->Perfil->iddominio;
        $activar = $this->_request->getPost('activar');
        $cantf = 0;
        $arrayresult = array();
        $datosusuario = array();
        $usuariosSinDominio = array();
        $usuariosconpermisosadominios = array();
        $permisos = $model->cargardominioUsuario($idusuario);                 
        $filtroDominio = $this->arregloToUnidimensional2($permisos);
        if (count($filtroDominio))
            $usuariosconpermisosadominios = $model->cargarUsuariosconpermisosaDominios ($filtroDominio);                
        $usuariosconpermisosadominios = $this->arregloToUnidimensionalUsuario($usuariosconpermisosadominios);
        $usuariosdelDominio =$model->cargarUsuariosDominios($iddominio);                
        $usuariosdelDominio = $this->arregloToUnidimensionalUsuario($usuariosdelDominio);
        $usuariosSinDominio =$model->usuariosSinDominio();                
        $usuariosSinDominio = $this->arregloToUnidimensionalUsuario($usuariosSinDominio);
        $arrayresult = array_merge($usuariosconpermisosadominios, $usuariosdelDominio);
        $arrayresult = array_merge($arrayresult, $usuariosSinDominio);
        if ($nombreusuario) {
            if (count($arrayresult)) {
                $datosusuario = $model->buscarUsuarioPorNombre($nombreusuario, $arrayresult, $iddominio, $limit, $start);                         
                $cantf = $model->cantidadFilasUsuariosConNombre($nombreusuario, $arrayresult, $iddominio);                         
            }
        } else {
            if (count($arrayresult)) {
                $datosusuario = $model->cargarGridUsuario($arrayresult, $limit, $start);                        
                $cantf = $model->cantidadFilas($arrayresult);                         
            }
        }
        if (count($datosusuario)) {
            $arrayusuario = $this->datosGridUsuarios($datosusuario);
            $result = array('cantidad_filas' => $cantf, 'datos' => $arrayusuario);
            echo json_encode($result);
            return;
        } else {
            $result = array('cantidad_filas' => $cantf, 'datos' => $datosusuario);
            echo json_encode($result);
            return;
        }
    }

    /* @author Miguel la llave 
     * @nombre arregloToUnidimensionalUsuario
     * @funcion para convertir array de usuarios en arreglo lineal con los identificadores
     * @return array (iduser-1,iduser-2,...iduser-n)
     * @parametros arreglos de datoas de los usuarios
     */

    function arregloToUnidimensionalUsuario($arrayvalores) {
        $array = array();
        foreach ($arrayvalores as $idusuario)
            $array[] = $idusuario['idusuario'];
        return $array;
    }

    /* @author Miguel la llave 
     * @nombre arregloToUnidimensional2
     * @funcion para convertir array de dominios en arreglo lineal con los identificadores
     * @return array (iddominio-1,iddominio-2,...iddominio-n)
     * @parametros arreglos de datos de los dominios
     */

    function arregloToUnidimensional2($arrayDominios) {
        $array = array();
        foreach ($arrayDominios as $dominios)
            $array[] = $dominios['iddominio'];
        return $array;
    }

    /* @author Miguel la llave 
     * @nombre datosGridUsuarios
     * @funcion para configurar datos de usuarios
     * @return array ([iduser-1,nombreuser-1],...[iduser-n,nombreuser-n])
     * @parametros arreglo de datos de usuarios
     */

    function datosGridUsuarios($datosusuario) {
        foreach ($datosusuario as $key => $usuario) {
            $arrayusuario[$key]['idusuario'] = $usuario->idusuario;
            $arrayusuario[$key]['nombreusuario'] = $usuario->nombreusuario;
        }
        return $arrayusuario;
    }

    /* @author Miguel la llave 
     * @nombre cargarRolesAction
     * @funcion para cargar roles de usuario seleccionado
     * @return array (rol-1,rol-2,...rol-n)
     * @parametros 
     */

    function cargarRolesAction() {
        $model=new CompartimentacionrecursosModel;
        $idusuario = $this->_request->getPost("idusuario");
        $rolbuscado = $this->_request->getPost("rolbuscado");
        $iddominio = $this->global->Perfil->iddominio;
        $limit = $this->_request->getPost("limit");
        $start = $this->_request->getPost("start");
        if ($rolbuscado) {
            $roles = array();
            $rolesencontrados = $model->obtenerrolBuscado($iddominio, $rolbuscado, $limit, $start)->toArray();                    
            foreach ($rolesencontrados as $rol) {
                $idrol = $rol['idrol'];
                $existe = $model->comprobarExisteRol($idusuario, $idrol);          
                if (count($existe) != 0) {
                    $id['idrol'] = $rol['idrol'];
                    $denom['denominacion'] = $rol['denominacion'];
                    $roles[] = $id + $denom;
                }
            }
            if (count($roles)) {
                echo (json_encode(array('datos' => $roles)));
                return;
            } else {
                echo (json_encode(array('datos' => $roles)));
                return;
            }
        } else {
            $datos =$model->obtenerrolesusuario($idusuario)->toArray();                    
            $roles = array();
            foreach ($datos as $dat) {
                $id['idrol'] = $dat['idrol'];
                $denom['denominacion'] = $dat['text'];
                $roles[] = $id + $denom;
            }
            echo (json_encode(array('datos' => $roles)));
            return;
        }
    }

    /* @author Miguel la llave 
     * @nombre cargarGridCriteriosAction
     * @funcion para cargar datos del grid criterios(reglas)
     * @return array (reglas del usuario dado permiso y recurso )
     * @parametros  
     */

    function cargarGridCriteriosAction() {
        $arrayReglas = json_decode(stripslashes($this->_request->getPost('arrayReglas')));
        $idrol = $this->_request->getPost("idrol");
        $idusuario = $this->_request->getPost("idusuario");
        $permiso = $this->_request->getPost("permiso");
        $recurso = $this->_request->getPost("recurso");
        $identidad = $this->_request->getPost('identidad');
        $idacl = $this->global->Perfil->iddominio;

        if (count($arrayReglas) != 0) {
            $result = array();
            foreach ($arrayReglas as $reg) {
                $obj['nombreregla'] = $reg->nombreregla;
                $obj['campo'] = $reg->campo;
                $obj['operador'] = $reg->operador;
                $obj['valor'] = $reg->valor;
                $result[] = $obj;
            }
            array_unique($result);
            echo json_encode(array('datos' => $result));
            return;
        } else {
            $rolacl = $idusuario . '_' . $idrol . '_' . $identidad;
            $buscado = array('rolacl' => (string) $rolacl, 'permiso' => (string) $permiso, 'recurso' => (string) $recurso);
            $mongoobj = new ZendExt_Mongo();
            $datos = $mongoobj->buscarPorIdAcl($idacl);
            $result = array();
            foreach ($datos as $dat) {
                $custom['rolacl'] = $dat['rolacl'];
                $custom['permiso'] = $dat['permiso'];
                $custom['recurso'] = $dat['recurso'];
                if ($custom == $buscado) {
                    $reg['nombreregla'] = $dat['nombreregla'];
                    $reg['campo'] = $dat['campo'];
                    $reg['operador'] = $dat['operador'];
                    $reg['valor'] = $dat['valor'];
                    if (!in_array($reg, $result))
                        $result[] = $reg;
                }
            }
            array_unique($result);
            echo json_encode(array('datos' => $result));
            return;
        }
    }

    /* @author Miguel la llave 
     * @nombre cargarEntidadesAction
     * @funcion para cargar arbolEntidades dado idusuario y idrol
     * @return array (entidades en las que el usuario tiene ese rol)
     * @parametros 
     */

    function cargarEntidadesAction() {
        $model=new CompartimentacionrecursosModel;
        $idusuario = $this->_request->getPost("idusuario");
        $idrol = $this->_request->getPost("idrol");
        $idEstructura = $this->_request->getPost("node");
        $iddominio = $this->global->Perfil->iddominio;
        $arrayEstructuras = $model->cargarentidadesusuariorol($idusuario, $idrol);                
        $arrayEstructuras = $this->arregloBidimensionalToUnidimensional($arrayEstructuras);
        if ($idEstructura > 0) {
            $arrayEstNoRol = $model->cargarentidadesusuariorol($idusuario, $idrol);
            $arrayEstNoRol = $this->arregloBidimensionalToUnidimensional($arrayEstNoRol);
            $arrayEst = $this->integrator->metadatos->buscarHijosEstructurasDadoArray($iddominio, $idEstructura, 1, $arrayEstructuras);
            $estructuras = array();
            foreach ($arrayEst as $key => $est) {
                $estructuras[] = $est;
                if ($est['unchecked'] || count(array_intersect($arrayEstNoRol, array($est['id']))))
                    unset($estructuras[$key]['checked']);
            }
            $arreglo = array();
            foreach ($estructuras as $valor) {
                $arr['id'] = $valor['id'];
                $arr['_parent'] = $valor['_parent'];
                $arr['_ltf'] = $valor['_ltf'];
                $arr['_rgt'] = $valor['_rgt'];
                if ($valor['leaf'] == 1)
                    $arr['leaf'] = true;
                else
                    $arr['leaf'] = false;
                $arr['text'] = $valor['text'];
                if ($valor['checked'] || $this->buscarHijosestructura($valor['id'], $arrayEstructuras))
                    $arreglo[] = $arr;
            }
            echo json_encode($arreglo);
            return;
        } else {
            $estructuras = array();
            $arrayEstNoRol = $model->cargarentidadesusuariorol($idusuario, $idrol);
            $arrayEstNoRol = $this->arregloBidimensionalToUnidimensional($arrayEstNoRol);
            $arrayEst = $this->integrator->metadatos->buscarHijosEstructurasDadoArray($iddominio, $idEstructura, 1, $arrayEstructuras);         
            foreach ($arrayEst as $key => $est) {
                $estructuras[] = $est;
            }
            $arreglo = array();
            foreach ($estructuras as $valor) {
                $arr['id'] = $valor['id'];
                $arr['_parent'] = $valor['_parent'];
                $arr['_ltf'] = $valor['_ltf'];
                $arr['_rgt'] = $valor['_rgt'];
                if ($valor['leaf'] == 1)
                    $arr['leaf'] = true;
                else
                    $arr['leaf'] = false;
                $arr['text'] = $valor['text'];
                if ($valor['checked'] || $this->buscarHijosestructura($valor['id'], $arrayEstructuras))
                    $arreglo[] = $arr;
            }
            echo json_encode($arreglo);
            return;
        }
    }
    
    
    function buscarHijosestructura($idestructura, $arrayEstructuras) {
        $iddominio = $this->global->Perfil->iddominio;
        $arrayEst = $this->integrator->metadatos->buscarHijosEstructurasDadoArray($iddominio, $idestructura, 1, $arrayEstructuras);
        if (count($arrayEst)) {
            foreach ($arrayEst as $value) {
                if ($value['checked']) {
                    return 1;
                }
                else
                    $result = $this->buscarHijosestructura($value['id'], $arrayEstructuras);
                return $result;
            }
        }
        else {
            return 0;
        }
    }

    /* @author Miguel la llave 
     * @nombre arregloBidimensionalToUnidimensional
     * @funcion para convertir el arreglo de entidades en lineal
     * @return array (identificadores de  entidades)
     * @parametros  arreglo de estructuras
     */

    function arregloBidimensionalToUnidimensional($arrayEstructuras) {
        $array = array();
        foreach ($arrayEstructuras as $est)
            $array[] = $est['idestructura'];
        return $array;
    }

     /* @author Miguel la llave 
     * @nombre modificarPermisoAction
     * @funcion para adicionar un permiso a un usuario dado el recurso
     * @return void
     * @parametros  
     */

    public function adicionarPermisoAction() {
        $model=new CompartimentacionrecursosModel;
        $idrol = $this->_request->getPost("idrol");
        $idusuario = $this->_request->getPost("idusuario");
        $permiso = $this->_request->getPost("permiso");
        $recurso = $this->_request->getPost("recurso");
        $arrayReglas = json_decode(stripslashes($this->_request->getPost('arrayReglas')));
        $identidad = $this->_request->getPost('identidad');
        $table = $model->cargaTableNameXml($recurso);                
        $rolacl = $idusuario . '_' . $idrol . '_' . $identidad;
        $arrayMongo = array();
        $idacl = $this->global->Perfil->iddominio;
        $arrayMongo['_id'] = (string) $idacl;
        foreach ($arrayReglas as $reg) {
            $tupla['rolacl'] = (string) $rolacl;
            $tupla['permiso'] = (string) $permiso;
            $tupla['recurso'] = (string) $recurso;
            $tupla['nombreregla'] = $reg->nombreregla;
            $tupla['campo'] = $reg->campo;
            $tupla['operador'] = $reg->operador;
            $tupla['valor'] = $reg->valor;
            $tupla['table_name'] = (string) $table;
            if (!in_array($tupla, $arrayMongo))
                $arrayMongo[] = $tupla;
        }
        $mongoobj = new ZendExt_Mongo();
        if (!$mongoobj->estaAcl($idacl)) {
            $mongoobj->insertacl($arrayMongo);
            $this->showMessage('Permiso insertado satisfactoriamente');
        } else {
            $bandera = true;
            if ($mongoobj->EstaPermiso($idacl, $permiso, $recurso, $rolacl)) {
                $bandera = false;
            }
            if ($bandera) {
                $datos = $mongoobj->buscarPorIdAcl($idacl);
                foreach ($datos as $obj) {
                    $tup['rolacl'] = $obj['rolacl'];
                    $tup['permiso'] = $obj['permiso'];
                    $tup['recurso'] = $obj['recurso'];
                    $tup['nombreregla'] = $obj['nombreregla'];
                    $tup['campo'] = $obj['campo'];
                    $tup['operador'] = $obj['operador'];
                    $tup['valor'] = $obj['valor'];
                    $tup['table_name'] = $obj['table_name'];
                    $objeto = $tup;
                    if (!in_array($objeto, $arrayMongo)) {
                        $arrayMongo[] = $objeto;
                    }
                }
                $mongoobj->Save($arrayMongo);
                $this->showMessage('Permiso insertado satisfactoriamente');
            } else {
                $this->showMessage('Existe ese permiso para ese usuario en la entidad seleccionada');
            }
        }
    }

    /* @author Miguel la llave 
     * @nombre modificarPermisoAction
     * @funcion para modificar un permiso a un usuario dado el recurso
     * @return void
     * @parametros  
     */
    public function modificarPermisoAction() {
        $model=new CompartimentacionrecursosModel;
        $idacl = $this->global->Perfil->iddominio;
        $idrol = $this->_request->getPost("idrol");
        $idusuario = $this->_request->getPost("idusuario");
        $permisoviejo = $this->_request->getPost("permisoviejo");
        $recursoviejo = $this->_request->getPost("recursoviejo");
        $permisonuevo = $this->_request->getPost("permisonuevo");
        $recursonuevo = $this->_request->getPost("recursonuevo");
        $arrayReglas = json_decode(stripslashes($this->_request->getPost('arrayReglas')));
        $identidad = $this->_request->getPost('identidad');
        $arrayMongo = array();
        $arrayMongo['_id'] = (string) $idacl;
        $rolacl = $idusuario . '_' . $idrol . '_' . $identidad;
        foreach ($arrayReglas as $reg) {
            $tupla['rolacl'] = (string) $rolacl;
            $tupla['permiso'] = (string) $permisonuevo;
            $tupla['recurso'] = (string) $recursonuevo;
            $tupla['nombreregla'] = $reg->nombreregla;
            $tupla['campo'] = $reg->campo;
            $tupla['operador'] = $reg->operador;
            $tupla['valor'] = $reg->valor;
            $tupla['table_name'] = $model->cargaTableNameXml($recursonuevo);
            if (!in_array($tupla, $arrayMongo))
                $arrayMongo[] = $tupla;
        }
        $roleliminar = $idusuario . '_' . $idrol . '_' . $identidad;
        $mongoobj = new ZendExt_Mongo();
        $mongoobj->eliminarPermisodeRol($idacl, $roleliminar, $permisoviejo, $recursoviejo);
        $datos = $mongoobj->buscarPorIdAcl($idacl);
        foreach ($datos as $obj) {
            $tup['rolacl'] = $obj['rolacl'];
            $tup['permiso'] = $obj['permiso'];
            $tup['recurso'] = $obj['recurso'];
            $tup['nombreregla'] = $obj['nombreregla'];
            $tup['campo'] = $obj['campo'];
            $tup['operador'] = $obj['operador'];
            $tup['valor'] = $obj['valor'];
            $tup['table_name'] = $obj['table_name']; //probar sino quitar
            $objeto = $tup;
            if (!in_array($objeto, $arrayMongo)) {
                $arrayMongo[] = $objeto;
            }
        }
        $mongoobj->Save($arrayMongo);
        $this->showMessage('Permiso modificado satisfactoriamente');
    }

    /* @author Miguel la llave 
     * @nombre listarPermisosUsuarioAction
     * @funcion para listar un los permisos de un usuario con rol en una entidad
     * @return array([permiso,recurso,array(reglas)])
     * @parametros  
     */

    public function listarPermisosUsuarioAction() {
        $idusuario = $this->_request->getPost('idusuario');
        $idrol = $this->_request->getPost('idrol');
        $identidad = $this->_request->getPost('identidad');
        $idacl = $this->global->Perfil->iddominio;
        $start = $this->_request->getPost('start');
        $limit = $this->_request->getPost('limit');
        $rolacl = $idusuario . '_' . $idrol . '_' . $identidad;
        try {
            $mongoobj = new ZendExt_Mongo();
        } catch (Exception $e) {
            throw new ZendExt_Exception('ECR06');
        }
        $datos = $mongoobj->buscarPorIdAcl($idacl);
        $tuplas = array();
        foreach ($datos as $tupla) {
            if ($tupla['rolacl'] == $rolacl) {
                $dat['permiso'] = $tupla['permiso'];
                $dat['recurso'] = $tupla['recurso'];
                $dat['table_name'] = $tupla['table_name'];
                $dato = $dat;
                if (!in_array($dato, $tuplas)) {
                    $tuplas[] = $dato;
                }
            }
        }
        array_unique($tuplas);
        echo json_encode(array('datos' => $tuplas));
        return;
    }

    /* @author Miguel la llave 
     * @nombre obtenerDatosServiciosAction
     * @funcion para cargar datos dinamicos si el criterio de un recurso requiere algun servicio
     * @return array(datos del servicio)
     * @parametros  
     */

    function obtenerDatosServiciosAction() {
        $IoC = ZendExt_IoC::getInstance();
        $idnodo = $this->_request->getPost('idnodo');
        $subsistema = $this->_request->getPost('subsistema');
        $service = $this->_request->getPost('service');
        $needparam = $this->_request->getPost('needparam');
        if ($needparam == 'si') {
            $datos = $IoC->$subsistema->$service($idnodo);
            if (is_array($datos)) {
                $result = array();
                foreach ($datos as $dat) {
                    $id['id'] = $dat['id'];
                    $text['text'] = $dat['text'];
                    $text['leaf'] = $dat['leaf'];
                    $result[] = $id + $text;
                }
                echo json_encode($result);
                return;
            }
            echo json_encode($datos);
            return;
        } else {
            $datos = $IoC->$subsistema->$service();
            echo json_encode($datos);
            return;
        }
    }

    function activarMongodbAction() {
        if (strtoupper(substr(PHP_OS, 0, 5)) === 'LINUX') {
            exec("service mongodb status", $a, $b);
            if ($b == 1) {
                $direccion = Zend_Registry::get('config')->xml->mongo;
                $xml = simplexml_load_file($direccion);
                $estadomongo = new SimpleXMLElement($xml->asXml());
                $estadomongo->estado->attributes()->instalado = 0;
                $estadomongo->asXml($direccion);
                echo json_encode(0);
                return;
            } else {
                $direccion = Zend_Registry::get('config')->xml->mongo;
                $xml = simplexml_load_file($direccion);
                $estadomongo = new SimpleXMLElement($xml->asXml());
                $estadomongo->estado->attributes()->instalado = 1;
                $estadomongo->asXml($direccion);
                echo json_encode(1);
                return;
            }
        } else {
            exec("sc query MongoDB", $a);
            if ($a[3] != "        ESTADO             : 4  RUNNING") {
                echo json_encode(0);
                return;
            }
            $direccion = Zend_Registry::get('config')->xml->mongo;
            $xml = simplexml_load_file($direccion);
            $estadomongo = new SimpleXMLElement($xml->asXml());
            $estadomongo->estado->attributes()->instalado = 1;
            $estadomongo->asXml($direccion);
            echo json_encode(1);
            return;
        }
    }

    function desactivarMongodbAction() {
        $direccion = Zend_Registry::get('config')->xml->mongo;
        $xml = simplexml_load_file($direccion);
        $estadomongo = new SimpleXMLElement($xml->asXml());
        $estadomongo->estado->attributes()->instalado = 0;
        $estadomongo->asXml($direccion);
        echo json_encode(0);
        return;
    }

}

?>
