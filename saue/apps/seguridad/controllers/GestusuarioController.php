<?php

/*
 * Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien García Tejo
 * @author Julio Cesar García Mosquera  
 * @version 1.0-0
 */

class GestusuarioController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestusuarioAction() {
        $this->render();
    }

    function fichausuarioAction() {
        $params = $this->getRequest()->getParams();
        $idusuario = $params['idusuario'];
        $this->view->perfilusuario = $this->getDatosUsuario($idusuario);
        $this->render();
    }

    function cargarRolesUsuarioAction() {
        $model = new SegUsuarioModel();
        $idusuario = $this->_request->getPost('idusuario');
        $roles = $model->obtenerrolesusuario($idusuario);
        $cantidad = $model->obtenerCantRolesUsuario($idusuario);
        echo json_encode(array('cantidad_filas' => $cantidad, 'datos' => $roles));
    }

    //andres
    function buscarUsuariosControladorAction() {
        $contador = 0;
        $sistemafunArr = array();
        while ($contador < 10) {
            $sistemafunArr[$contador]['text'] = "valor->text";
            $sistemafunArr[$contador]['checked'] = false;
            $sistemafunArr[$contador]['leaf'] = true;
            $contador++;
        }
        echo json_encode($sistemafunArr);
        return;
    }

    public function getnomAction() {
        $ldap = new ZendExt_SauxeLdap();
        $as = json_decode(stripslashes($this->_request->getPost('ArrayUsuarios')));
        $strin = '';
        foreach ($as as $value) {
            $result = $ldap->findUsers1($value);
            if ($result != '') {
                if ($strin != '') {
                    $strin .= ';';
                }
                $strin .= $result;
            }
        }
        echo json_encode($strin);
        return;
    }

    function probandoAction() {
        $string = $this->_request->getPost('no');
        if ($string == 'nada') {
            $filter = "DC=uci,DC=cu";
        } else {
            $filter = $string;
        }
        $sistemafunArr = array();
        $ldap = new ZendExt_SauxeLdap();
        $nume = count(split(',', $filter)) + 1;
        $result = $ldap->findUsers($filter, $nume);
        $contador = 0;
        for ($i = 0; $i < count($result); $i++) {
            $sistemafunArr[$contador]['text'] = $result[$i]["name"];
            $sistemafunArr[$contador]['checked'] = false;
            $sistemafunArr[$contador++]['metadatos'] = $result[$i]["dn"];
        }
        echo json_encode($sistemafunArr);
        return;
    }

    private function accountDomainNameShort($acountDomainName) {
        $acountDomainName = explode('.', $acountDomainName);
        return $acountDomainName[0];
    }

    private function createAccountDomainName($string) {
        $string = strtolower($string);
        $stringResult = '';
        $string = substr($string, strpos($string, 'dc'), strlen($string));
        $array = explode(',', $string);
        $cant = count($array);
        foreach ($array as $dc) {
            $result = explode('dc=', $dc);
            if ($cant == 1)
                $stringResult .= $result[1];
            else {
                $stringResult .= $result[1] . '.';
                $cant--;
            }
        }
        return $stringResult;
    }

//andres

    public function getDatosUsuario($idusuario) {
        $model = new SegUsuarioModel();
        $perfil = $model->cargarperfilusuario($idusuario);
        $perfilusuario = '';
        if (count($perfil)) {
            $perfilusuario['tema'] = $perfil[0]['NomTema']['denominacion'];
            $perfilusuario['idioma'] = $perfil[0]['NomIdioma']['denominacion'];
            $perfilusuario['portal'] = $perfil[0]['NomDesktop']['denominacion'];
            $perfilusuario['usuario'] = $perfil[0]['nombreusuario'];
            $perfilusuario['idusuario'] = $perfil[0]['idusuario'];
            $perfilusuario['activo'] = ($perfil[0]['activo']) ? 'Si' : 'No';
            $arrayDominio = $this->integrator->metadatos->DatosDominioDadoID($perfil[0]['iddominio']);
            $perfilusuario['dominio'] = $arrayDominio[0]['denominacion'];
            $perfilusuario['iddominio'] = $perfil[0]['iddominio'];
            $arrayEstructuras = $this->integrator->metadatos->MostrarCamposEstructuraSeguridad($perfil[0]['identidad']);
            $perfilusuario['entidad'] = $arrayEstructuras[0]['text'];
            if ($perfil[0]['idarea']) {
                $area = $this->integrator->metadatos->EstructurasInternasDadoIDSeguridad($perfil[0]['idarea']);
                $perfilusuario['area'] = $area[0]['denominacion'];
            }
            if ($perfil[0]['idcargo']) {
                $cargo = $this->integrator->metadatos->CargoDadoIDSeguridad($perfil[0]['idcargo']);
                $perfilusuario['cargo'] = $cargo[0]['denominacion'];
            }
            if (isset($perfil[0]['NomFila']) && is_array($perfil[0]['NomFila']['NomValor'])) {
                $perfilusuario['dinamico'] = array();
                foreach ($perfil[0]['NomFila']['NomValor'] as $valor) {
                    if ($valor['idfila']) {
                        $arraycampos = $model->cargarcamposdadovalores($valor['idvalor']);
                        $perfilusuario[$arraycampos[0]['NomCampo']['nombre']] = array($valor['valor'], $arraycampos[0]['NomCampo']['nombreamostrar']);
                        $perfilusuario['dinamico'][] = $arraycampos[0]['NomCampo']['nombre'];
                    }
                }
            }
        }
        return $perfilusuario;
    }

    function cargarusuarioAction() {
        $model = new SegUsuarioModel();
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
        $filtroDominio = $this->arregloToUnidimensional($permisos);
        if (count($filtroDominio))
            $usuariosconpermisosadominios = $model->cargarUsuariosconpermisosaDominios($filtroDominio);
        $usuariosconpermisosadominios = $this->arregloToUnidimensionalUsuario($usuariosconpermisosadominios);
        $usuariosdelDominio = $model->cargarUsuariosDominios($iddominio);
        $usuariosdelDominio = $this->arregloToUnidimensionalUsuario($usuariosdelDominio);
        $usuariosSinDominio = $model->usuariosSinDominio();
        $usuariosSinDominio = $this->arregloToUnidimensionalUsuario($usuariosSinDominio);
        $arrayresult = array_merge($usuariosconpermisosadominios, $usuariosdelDominio);
        $arrayresult = array_merge($arrayresult, $usuariosSinDominio);
        $usuarioscompartimentados = $model->cargarUsuarioDominioCompartimentado($iddominio);
        $usuarioscompartimentados = $this->arregloToUnidimensionalUsuario($usuarioscompartimentados);
        $arrayresult = array_merge($arrayresult, $usuarioscompartimentados);
        $arrayresult = array_unique($arrayresult);

        if ($nombreusuario || $dominiobuscar || $activar) {
            if (count($arrayresult)) {
                $datosusuario = $model->cargarGridUsuarioBuscados($nombreusuario, $arrayresult, $dominiobuscar, $activar, $limit, $start);
                $cantf = $model->cantidadFilasUsuariosBuscados($nombreusuario, $arrayresult, $dominiobuscar, $activar);
            }
        } else {
            if (count($arrayresult)) {
                $datosusuario = $model->cargarGridUsuario($arrayresult, $limit, $start);
                $cantf = $model->cantidadFilas($arrayresult);//AQUI DA EL PALO
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

    function arregloToUnidimensionalUsuario($arrayvalores) {
        $array = array();
        foreach ($arrayvalores as $idusuario)
            $array[] = $idusuario['idusuario'];
        return $array;
    }

    function arregloToUnidimensional($arrayDominios) {
        $array = array();
        foreach ($arrayDominios as $dominios)
            $array[] = $dominios['iddominio'];
        return $array;
    }

    function datosGridUsuarios($datosusuario) {
        $model = new SegUsuarioModel();
        foreach ($datosusuario as $key => $usuario) {
            $arrayusuario[$key]['idusuario'] = $usuario->idusuario;
            if ($usuario->activo)
                $arrayusuario[$key]['activo'] = 'Si';
            else
                $arrayusuario[$key]['activo'] = 'No';
            $arrayusuario[$key]['nombreusuario'] = $usuario->nombreusuario;
            $arrayusuario[$key]['ip'] = $usuario->ip;
            $arrayusuario[$key]['iddominio'] = $usuario->iddominio;
            $dominio = $this->integrator->metadatos->DatosDominioDadoID($usuario->iddominio);
            $arrayusuario[$key]['dominio'] = $dominio[0]['denominacion'];
            $arrayusuario[$key]['iddesktop'] = $usuario->iddesktop;
            $arrayusuario[$key]['idtema'] = $usuario->idtema;
            $arrayusuario[$key]['ididioma'] = $usuario->ididioma;
            $arrayusuario[$key]['tema'] = $usuario->NomTema->denominacion;
            $arrayusuario[$key]['idioma'] = $usuario->NomIdioma->denominacion;
            $arrayusuario[$key]['desktop'] = $usuario->NomDesktop->denominacion;
            $arrayusuario[$key]['identidad'] = $usuario->identidad;
            $arrayusuario[$key]['idarea'] = $usuario->idarea;
            $arrayusuario[$key]['idcargo'] = $usuario->idcargo;
            $datosservidoraut = $model->cargarservidoraut($usuario->idusuario);
            $arrayusuario[$key]['idservidor'] = $datosservidoraut[0]->idservidor;
            $arrayusuario[$key]['denominacion'] = $datosservidoraut[0]->denominacion;
            if ($usuario->identidad) {
                $entidad = $this->integrator->metadatos->MostrarCamposEstructuraSeguridad($usuario->identidad);
                $arrayusuario[$key]['entidad'] = $entidad[0]['text'];
            }
            if ($usuario->idarea) {
                $area = $this->integrator->metadatos->EstructurasInternasDadoIDSeguridad($usuario->idarea);
                $arrayusuario[$key]['area'] = $area[0]['denominacion'];
            }
            if ($usuario->idcargo) {
                $cargo = $this->integrator->metadatos->CargoDadoIDSeguridad($usuario->idcargo);
                $arrayusuario[$key]['cargo'] = $cargo[0]['denominacion'];
            }
        }
        return $arrayusuario;
    }

    function comprobartienerol($idrol, $idusuario, $identidad) {
        $model = new SegUsuarioModel();
        $rolmarcado = $model->cargarroles($idrol, $idusuario, $identidad);
        if (count($rolmarcado)) {
            foreach ($rolmarcado as $valor) {
                $roles = $valor->toArray();
                if ($roles['idrol'] == $idrol)
                    return true;
            }
            return false;
        }
        return false;
    }

    function comprobartienesession($idusuario) {
        $model = new SegUsuarioModel();
        $usuarioSession = $model->obtenerUserConectados();        
        if (count($usuarioSession)) {
            foreach ($usuarioSession as $valor) {          
                if ($valor['idusuario'] == $idusuario)
                    return true;
            }            
            return false;
        }
        return false;
    }

    function cargarEntidadesRolAction() {
        $model = new SegUsuarioModel();
        $idnodo = $this->_request->getPost('nodo');
        $identidad = $this->_request->getPost('identidad');
        $idusuario = $this->_request->getPost('idusuario');
        if ($idnodo == 0 && $identidad == 0) {
            $entidades = $model->cargarentidades();
            foreach ($entidades as $key => $entidad) {
                $datosentidad = $this->integrator->metadatos->MostrarCamposEstructuraSeguridad($entidad->identidad);
                $arrayentidades[$key]['id'] = $datosentidad[0]['id'];
                $arrayentidades[$key]['text'] = $datosentidad[0]['text'];
            }
            echo json_encode($arrayentidades);
            return;
        } elseif ($identidad > 0) {
            $entidadesrol = $model->cargarentidadesrol($identidad);
            foreach ($entidadesrol as $key => $rolesentidad) {
                $arrayentidadrol[$key]['id'] = $identidad . _ . $rolesentidad['id'];
                $arrayentidadrol[$key]['idrol'] = $rolesentidad['id'];
                $arrayentidadrol[$key]['text'] = $rolesentidad['text'];
                $arrayentidadrol[$key]['leaf'] = true;
                if ($idusuario) {
                    $arrayentidadrol[$key]['checked'] = $this->comprobartienerol($rolesentidad['id'], $idusuario, $identidad) ? true : false;
                } else {
                    $arrayentidadrol[$key]['checked'] = false;
                }
            }
            echo json_encode($arrayentidadrol);
            return;
        }
    }

    function eliminarusuarioAction() { 
        $userCS=array();
        $model = new SegUsuarioModel();
        $ArrayUserDel = json_decode(stripslashes($this->_request->getPost('ArrayUserDel')));
        $idusuarioNoDelete = $this->global->Perfil->idusuario;
        $userND = false;        
        foreach ($ArrayUserDel as $idusuario) {
            if ($idusuarioNoDelete != $idusuario) {
                if (!$this->comprobartienesession($idusuario)) {
                    $band = false;
                    $comprovar = $model->comprovarestado($idusuario);
                    if ($comprovar[0]->estado == '0') {
                        $roles = $model->CargarRolXIdUsuario($idusuario);
                        $tipo = SegUsuarioModel::TipoConexion();
                        if ($tipo == 3) {
                            foreach ($roles as $rol) {
                                SegUsuarioDatServidorDatGestorModel::ElimminarRolLogin($rol->idrol, $idusuario, $tipo);
                            }
                        }
                        $model->eliminarusuario($idusuario);
                        $band = true;                        
                    }
                }else{
                   $userCS[]=$idusuario; 
                }
            } else { 
               $userND = true;
            }
        }        
        if ($userND) {
            $name = $model->nombusuariocontrasennabd($idusuarioNoDelete);
            $name = $name[0]['nombreusuario'];
            echo "{'error':3,'mensaje': '$name'}";
        } else            
            if ($userCS!=null) {        
            foreach($userCS as $valor){                
            $name = $model->nombusuariocontrasennabd($valor);            
            $nombre.=$name[0]['nombreusuario'].',';           
            }            
            echo "{'error':3,'mensaje': '$nombre'}";               
        }else
            if ($band)            
            echo "{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgDelUser}";
    }

    function insertarusuarioAction() {
        $siquiere = 0;
        $stri = split(';', $this->_request->getPost('nombreusuario'));
        $existUser = array();
        foreach ($stri as $value) {
            $model = new SegUsuarioModel();
            $usuario = new SegUsuario();
            $idservidorauth = $this->_request->getPost('idservidor');
            $usuario->nombreusuario = $value;
            if (!$idservidorauth) {
                if (!$this->verificarpass($this->_request->getPost('contrasenna')))
                    return false;
                $usuario->contrasenna = md5($this->_request->getPost('contrasenna'));
                $usuario->contrasenabd = md5($this->_request->getPost('contrasena'));
            } else {
                $usuario->contrasenna = md5($value . rand(10, 800));
                $usuario->contrasenabd = md5($value . rand(10, 800));
            }
            $usuario->ip = $this->_request->getPost('ip');
            $usuario->idtema = $this->_request->getPost('idtema');
            $usuario->iddominio = $this->_request->getPost('iddominio');
            $usuario->ididioma = $this->_request->getPost('ididioma');
            $usuario->iddesktop = $this->_request->getPost('iddesktop');
            if ($this->_request->getPost('idarea'))
                $usuario->idarea = $this->_request->getPost('idarea');
            if ($this->_request->getPost('identidad'))
                $usuario->identidad = $this->_request->getPost('identidad');
            if ($this->_request->getPost('idcargo'))
                $usuario->idcargo = $this->_request->getPost('idcargo');
            if (!$this->verificarusuario($usuario->nombreusuario)) {
                $usuario->save();

                $restricc_array = $model->cargarrestriccion();

                $objclaveacceso = new SegClaveacceso();
                $objclaveacceso->valor = true;
                $objclaveacceso->fechainicio = date('d-m-Y H:i:s');
                $objclaveacceso->fechafin = $this->calcularFecha(+$restricc_array[0]['diascaducidad']);
                $objclaveacceso->clave = $usuario->contrasenna;
                $objclaveacceso->idusuario = $usuario->idusuario;

                $objclaveaccesomodel = new SegClaveAccesoModel();
                $objclaveaccesomodel->insertClave($objclaveacceso);

                $objusuarioDominio = new SegUsuarioNomDominio();
                $objusuarioDominio->idusuario = $usuario->idusuario;
                $objusuarioDominio->iddominio = $this->global->Perfil->iddominio;
                $modelusuarioDominio = new SegUsuarioNomDominioModel();
                $modelusuarioDominio->insertar($objusuarioDominio);
                if ($idservidorauth) {
                    $sistemaservautenticacion = new SegUsuarioDatSerautenticacion();
                    $sistemaservautenticacion->idusuario = $usuario->idusuario;
                    $sistemaservautenticacion->idservidor = $idservidorauth;
                    $model = new SegUsuarioModel();
                    $model->insertarservidoraut($sistemaservautenticacion);
                }
                $siquiere++;
            } else {
                //Aqui se guardan los usuarios que no se insertaron porque ya existen
                $existUser[] = $value;
            }
        }
        //Tratar la respuesta
        if (count($existUser) == 0) {
            if ($siquiere > 1)
                echo "{'bien':1,'mensaje':$siquiere}"; //               
            else if ($siquiere == 1)
                echo "{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgAddUser}";
        } else { //Aqui entra cuando hay usuarios que no se pudieron insertar
            if (count($stri) > 1) {
                $cantidad = count($existUser);
                echo "{'bien':2,'mensaje':$cantidad}";
            } else if (count($stri) == 1) //En el caso de que solo tratamos de ins. un user
                throw new ZendExt_Exception('SEG009'); //GENERO una excepcion
        }
    }

    function calcularFecha($dias) {

        $calculo = strtotime("$dias days");
        return date("d-m-Y H:i:s", $calculo);
    }

    function modificarusuarioAction() {
        $model = new SegUsuarioModel();
        $idusuario = $this->_request->getPost('idusuario');
        $usuario = Doctrine::getTable('SegUsuario')->find($idusuario);
        $nombreusuario = $this->_request->getPost('nombreusuario');
        $usuario->ip = $this->_request->getPost('ip');
        $usuario->idtema = $this->_request->getPost('idtema');
        $usuario->iddominio = $this->_request->getPost('iddominio');
        $usuario->ididioma = $this->_request->getPost('ididioma');
        $usuario->iddesktop = $this->_request->getPost('iddesktop');
        if ($this->_request->getPost('idarea'))
            $usuario->idarea = $this->_request->getPost('idarea');
        if ($this->_request->getPost('identidad'))
            $usuario->identidad = $this->_request->getPost('identidad');
        if ($this->_request->getPost('idcargo') && $this->_request->getPost('idcargo') != $usuario->idcargo) {
            $usuario->idcargo = $this->_request->getPost('idcargo');
        }
        $idservidorauth = $this->_request->getPost('idservidor');
        $OldDenominacion = $usuario->nombreusuario;
        if ($usuario->nombreusuario != $nombreusuario) {
            if (!$this->verificarusuario($this->_request->getPost('nombreusuario'))) {
                $usuario->nombreusuario = $this->_request->getPost('nombreusuario');
                if (!$idservidorauth) {
                    $servidor = $model->cantservidoraut($usuario->idusuario);
                    if (count($servidor))
                        $model->eliminarusuarioservidoraut($usuario->idusuario);
                } else {
                    $servidor = $model->cantservidoraut($usuario->idusuario);
                    if (count($servidor))
                        $model->eliminarusuarioservidoraut($usuario->idusuario);
                    $sistemaservautenticacion = new SegUsuarioDatSerautenticacion();
                    $sistemaservautenticacion->idusuario = $usuario->idusuario;
                    $sistemaservautenticacion->idservidor = $idservidorauth;
                }
                $model->modificarusuario($sistemaservautenticacion, $usuario);

                $tipo = SegUsuarioModel::TipoConexion();
                if ($OldDenominacion != $nombreusuario && $tipo == 3)
                    SegUsuarioModel::ModificarNombreUsuarioGestor($OldDenominacion, $NewDenominacion, $idusuario, $tipo);
                echo "{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgModUser}";
            } else {
                throw new ZendExt_Exception('SEG009');
            }
        } else {
            $usuario->nombreusuario = $this->_request->getPost('nombreusuario');
            $sistemaservautenticacion = null;
            if (!$idservidorauth) {
                $servidor = $model->cantservidoraut($usuario->idusuario);
                if (count($servidor))
                    $model->eliminarusuarioservidoraut($usuario->idusuario);
            } else {
                $servidor = $model->cantservidoraut($usuario->idusuario);
                if (count($servidor))
                    $model->eliminarusuarioservidoraut($usuario->idusuario);
                $sistemaservautenticacion = new SegUsuarioDatSerautenticacion();
                $sistemaservautenticacion->idusuario = $usuario->idusuario;
                $sistemaservautenticacion->idservidor = $idservidorauth;
            }

            $model->modificarusuario($sistemaservautenticacion, $usuario);

            echo "{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgModUser}";
        }
    }

    function cargarcombodesktopAction() {
        $model = new SegUsuarioModel();
        $datoscombo = $model->cargarcombodesktop();
        $datos = $datoscombo->toArray(true);
        echo json_encode($datos);
        return;
    }

    function cargarcombodominioAction() {
        $arraydominios = $this->integrator->metadatos->BuscarComboDominio(0, 0);   
        echo json_encode($arraydominios);
        return;
    }

    function cargarComboDominioBuscarAction() {
        $filtroDominio = array();
        $permisos = array();
        $dominios = array();
        $idusuario = $this->global->Perfil->idusuario;
        $iddominio = $this->global->Perfil->iddominio;
        $permisos = SegCompartimentacionusuario::cargardominioUsuario($idusuario);
        $dominios = SegUsuario::obtenerIdDominios($iddominio);
        $dominios = $this->arregloToUnidimensional($dominios);
        $permisos = $this->arregloToUnidimensional($permisos);
        $filtroDominio = array_merge($dominios, $permisos);
        $arraydominios = $this->integrator->metadatos->cargarComboDominioBuscar($filtroDominio);        
        //$arraydominios['datos'][count($arraydominios['datos'])] = array('iddominio' => 0, 'denominacion' => 'S/D', 'descripcion' => 'S/D');
        echo json_encode($arraydominios);
        return;
    }

    function cargarcomboidiomaAction() {
        $model = new SegUsuarioModel();
        $datoscombo = $model->cargarcomboidioma();
        $datos = $datoscombo->toArray(true);
        echo json_encode($datos);
        return;
    }

    function cargarcombotemaAction() {
        $model = new SegUsuarioModel();
        $datoscombo = $model->cargarcombotema();
        $datos = $datoscombo->toArray(true);
        echo json_encode($datos);
        return;
    }

    function verificarusuario($nombusuario) {
        $model = new SegUsuarioModel();
        $usuario = $model->contarusuario($nombusuario);
        if ($usuario)
            return 1;
        else
            return 0;
    }

    function verificarpass($pass) {
        $s = '/^([a-zA-ZÃ¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½])+$/';
        $sn = '/^[\da-zA-ZÃ¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½]+$/';
        $nn = '/[\d]/';
        $nl = '/[\!\\Ã¯Â¿Â½\\!\@\#\$\%\^\&\*\(\)\_\+\=\{\}\[\]\:\"\;\'\?\/\>\.\<\,\\\*\-]+$/';
        $clave = new SegRestricclaveacceso();
        $datos = $clave->cargarclave(0, 0);
        $resultados = array();
        $results = array();
        if ($datos->getData() == null)
            throw new ZendExt_Exception('SEG001');
        $datosacc = $datos->toArray(true);
        if (strlen($pass) < $datosacc[0]['minimocaracteres']) {
            $long = $datosacc[0]['minimocaracteres'];
            echo("{'error':3,'mensaje':$long}");
            return false;
        }
        if ($datosacc[0]['numerica'] == 1 && $datosacc[0]['alfabetica'] == 0 && $datosacc[0]['signos'] == 0) {
            if (!$this->hayNumeros($pass))
                throw new ZendExt_Exception('SEG002');
        }
        if ($datosacc[0]['numerica'] == 0 && $datosacc[0]['alfabetica'] == 1 && $datosacc[0]['signos'] == 0) {
            if (!$this->hayLetras($pass))
                throw new ZendExt_Exception('SEG003');
        }
        if ($datosacc[0]['numerica'] == 0 && $datosacc[0]['alfabetica'] == 0 && $datosacc[0]['signos'] == 1) {
            $signos = preg_match($sn, $pass, $resultados);
            if ($signos == 1)
                throw new ZendExt_Exception('SEG004');
        }
        if ($datosacc[0]['numerica'] == 1 && $datosacc[0]['alfabetica'] == 1 && $datosacc[0]['signos'] == 0) {
            if (!$this->hayLetras($pass) || !$this->hayNumeros($pass))
                throw new ZendExt_Exception('SEG005');
        }
        if ($datosacc[0]['numerica'] == 0 && $datosacc[0]['alfabetica'] == 1 && $datosacc[0]['signos'] == 1) {
            $signos = preg_match($sn, $pass, $resultados);
            if (!$this->hayLetras($pass) || !$signos)
                throw new ZendExt_Exception('SEG006');
        }

        if ($datosacc[0]['numerica'] == 1 && $datosacc[0]['alfabetica'] == 0 && $datosacc[0]['signos'] == 1) {
            $signos = preg_match_all($sn, $pass, $resultados);
            if (!$signos == 1 || !$this->hayNumeros($pass))
                throw new ZendExt_Exception('SEG007');
        }
        if ($datosacc[0]['numerica'] == 1 && $datosacc[0]['alfabetica'] == 1 && $datosacc[0]['signos'] == 1) {
            $signos = preg_match($sn, $pass, $resultados);
            if (!$this->hayNumeros($pass) || $signos == 1 || !$this->hayNumeros($pass))
                throw new ZendExt_Exception('SEG008');
        }
        return true;
    }

    function hayNumeros($cadena) {
        $cant = strlen($cadena);
        for ($i = 0; $i < $cant; $i++)
            if ($cadena[$i] >= '0' && $cadena[$i] <= '9')
                return true;
        return false;
    }

    function hayLetras($cadena) {
        $cant = strlen($cadena);
        for ($i = 0; $i < $cant; $i++)
            if (($cadena[$i] >= 'A' && $cadena[$i] <= 'Z') || ($cadena[$i] >= 'a' && $cadena[$i] <= 'z') || $cadena[$i] == 'Ã¯Â¿Â½' || $cadena[$i] == 'Ã¯Â¿Â½')
                return true;
        return false;
    }

    function cargarestructuraAction() {
        $idEstructura = $this->_request->getPost('node');
        $arrayEstructuras = $this->integrator->metadatos->DameEstructurasinChecked($idEstructura);
        echo json_encode($arrayEstructuras);
        return;
    }

    function cargarareasAction() {
        $idestructura = $this->_request->getPost('identidad');
        $idarea = $this->_request->getPost('node');
        if ($idarea) {
            $arrayareas = $this->integrator->metadatos->DameHijosInternaSeguridadSinCheked($idarea);
        } else {
            $arrayareas = $this->integrator->metadatos->DameEstructurasInternasSeguridadSinCheked($idestructura, true);
        }
        echo json_encode($arrayareas);
        return;
    }

    function cargarcargosAction() {
        $idarea = $this->_request->getPost('idarea');
        $arraycargos = $this->integrator->metadatos->BuscarCargosPorTiposSeguridad($idarea);
        if (count($arraycargos)) {
            foreach ($arraycargos as $key => $cargo) {
                $cargosformados[$key]['text'] = $cargo['Asignacion']['text'];
                $cargosformados[$key]['id'] = $cargo['id'];
                $cargosformados[$key]['idcargo'] = $cargo['id'];
                $cargosformados[$key]['leaf'] = true;
            }
            echo json_encode($cargosformados);
        }
        else
            echo json_encode($arraycargos);
    }

    function cargarcomboservidoresautAction() {
        $model = new SegUsuarioModel();
        $servidoresaut = $model->cargarcomboservidoresaut();
        $datos = $servidoresaut->toArray(true);
        $datos[count($datos)] = array('idservidor' => 0, 'denominacion' => 'Ninguno');
        echo json_encode($datos);
        return;
    }

    private function TratarCriterioBusqueda($objetoName) {
        $inserted = "";
        for ($i = 0; $i < strlen($objetoName); $i++) {
            if ($objetoName[$i] == "_") {
                $inserted .= '!';
            }
            $inserted .= $objetoName[$i];
        }
        return $inserted;
    }

    function cargarrolesAction() {
        $model = new SegUsuarioModel();
        $start = $this->_request->getPost("start");
        $limit = $this->_request->getPost("limit");
        $idusuario = $this->_request->getPost("idusuario");
        $rolbuscado = $this->_request->getPost("rolbuscado");
        $filtroDominio = $this->global->Perfil->iddominio;

        $arrayResult = array();
        $arrayRolesDominio = array();
        $arrayRolesPermisoDominio = array();
        $arrayRolesDominio = $model->RolesdelDominio($filtroDominio); //todos los roles del dominio

        $arrayRolesDominio = $this->arregloToUnidimensionalRoles($arrayRolesDominio);
        $arrayRolesPermisoDominio = $model->cargarRolesDominio($filtroDominio);
        $arrayRolesPermisoDominio = $this->arregloToUnidimensionalRoles($arrayRolesPermisoDominio);
        $arrayResult = array_merge($arrayRolesDominio, $arrayRolesPermisoDominio);

        if (count($arrayResult)) {
            $result = array();
            $cantrol = 0;
            if (!$rolbuscado) {
                $result = $model->obtenerrolesasociados($limit, $start, $idusuario, $arrayResult);
                $cantrol = $model->cantrolesDominio($arrayResult);
            } else {
                $rolbuscado = $this->TratarCriterioBusqueda($rolbuscado);
                $result = $model->obtenerRolesBuscado($rolbuscado, $limit, $start, $idusuario);
                $cantrol = $model->cantrolBuscados($filtroDominio, $rolbuscado);
            }
            $arrayroles = array();
            if (count($result)) {
                foreach ($result as $key => $rol) {
                    $arrayroles[$key]['idrol'] = $rol['idrol'];
                    $arrayroles[$key]['denominacion'] = $rol['denominacion'];
                    $arrayroles[$key]['abreviatura'] = $rol['abreviatura'];
                    $arrayroles[$key]['descripcion'] = $rol['descripcion'];
                    if (count($rol['DatEntidadSegUsuarioSegRol']))
                        $arrayroles[$key]['estado'] = 1;
                    else
                        $arrayroles[$key]['estado'] = 0;
                }
                $result = array('cantidad_filas' => $cantrol, 'datos' => $arrayroles);
                echo json_encode($result);
                return;
            }
        }
        $arrayroles = array();
        $result = array('cantidad_filas' => 0, 'datos' => $arrayroles);
        echo json_encode($result);
        return;
    }

    public function arregloToUnidimensionalRoles($rolesDominio) {
        $array = array();
        foreach ($rolesDominio as $rol)
            $array[] = $rol['idrol'];
        return $array;
    }

    function arregloBidimensionalToUnidimensional($arrayEstructuras) {
        $array = array();
        foreach ($arrayEstructuras as $est)
            $array[] = $est['idestructura'];
        return $array;
    }

    function cargarentidadesReporteAction() {
        $model = new SegUsuarioModel();
        $idusuario = $this->_request->getPost("idusuario");
        $idrol = $this->_request->getPost("idrol");
        $idEstructura = $this->_request->getPost("node");
        $iddominio = $this->_request->getPost("iddominio");
        $arrayEst = $model->cargarentidadesusuariorol($idusuario, $idrol);
        $arrayEst = $this->arregloBidimensionalToUnidimensional($arrayEst);
        $arrayEstNoRol = $model->cargarentidadesusuarionorol($idusuario, $idrol);
        $arrayEstNoRol = $this->arregloBidimensionalToUnidimensional($arrayEstNoRol);
        $arrayEstructuras = $this->integrator->metadatos->buscarHijosEstructurasDadoArray($iddominio, $idEstructura, 1, $arrayEst);
        $estructuras = array();
        foreach ($arrayEstructuras as $key => $est) {
            $estructuras[$key] = $est;
            $estructuras[$key]['disabled'] = true;
            if ($est['unchecked'] || !$est['checked'] || count(array_intersect($arrayEstNoRol, array($est['id']))))
                unset($estructuras[$key]['checked']);
        }
        echo json_encode($estructuras);
        return;
    }

    function cargarentidadesAction() {
        $model = new SegUsuarioModel();
        $idusuario = $this->_request->getPost("idusuario");
        $idrol = $this->_request->getPost("idrol");
        $idEstructura = $this->_request->getPost("node");
        $tcheck = $this->_request->getPost('tcheck');
        $iddominio = $this->_request->getPost("iddominio");
        $arrayEst = $model->cargarentidadesusuariorol($idusuario, $idrol);
        $arrayEst = $this->arregloBidimensionalToUnidimensional($arrayEst);
        $arrayEstNoRol = $model->cargarentidadesusuarionorol($idusuario, $idrol);
        $arrayEstNoRol = $this->arregloBidimensionalToUnidimensional($arrayEstNoRol);
        $arrayEstructuras = $this->integrator->metadatos->buscarHijosEstructurasDadoArray($iddominio, $idEstructura, 1, $arrayEst);

        $estructuras = array();
        foreach ($arrayEstructuras as $key => $est) {
            $estructuras[$key] = $est;
            if ($est['unchecked'] || count(array_intersect($arrayEstNoRol, array($est['id']))))
                unset($estructuras[$key]['checked']);
        }
        echo json_encode($estructuras);
        return;
    }

    function gestionarMarcadoEntidades($arrayEstructuras, $arrayEst, $idEstructura, $tcheck) {
        $cant = count($arrayEstructuras);
        if ($tcheck == 'marcado') {
            if (!$this->estaCheckEnBd($arrayEst, $idEstructura)) {
                for ($i = 0; $i < $cant; $i++)
                    $arrayEstructuras[$i]['checked'] = true;
            }
            else
                $arrayEstructuras = $this->marcarEntidades($arrayEstructuras, $arrayEst);
        } else if ($tcheck == 'desmarcado') {
            if ($this->estaCheckEnBd($arrayEst, $idEstructura)) {
                for ($i = 0; $i < $cant; $i++)
                    $arrayEstructuras[$i]['checked'] = false;
            }
            else
                $arrayEstructuras = $this->marcarEntidades($arrayEstructuras, $arrayEst);
        }
        else
            $arrayEstructuras = $this->marcarEntidades($arrayEstructuras, $arrayEst);
        return $arrayEstructuras;
    }

    function estaCheckEnBd($arrayEst, $idEstructura) {
        $cant = count($arrayEst);
        for ($i = 0; $i < $cant; $i++) {
            if ($arrayEst[$i]['identidad'] == $idEstructura)
                return true;
        }
        return false;
    }

    function cargarEntidadesDadoDominio($arrayEntidades, $idEntidad) {
        return $this->buscarIdCargar($arrayEntidades, $idEntidad);
    }

    function buscarIdCargar($array, $idEntidad = 0) {
        $resultado = array();
        if (!$idEntidad) {
            foreach ($array as $valor) {
                $temp = explode("-", $valor);
                if (!$this->existeValorArray($resultado, substr($temp[0], 0, strlen($temp[0]) - 2)))
                    $resultado[] = substr($temp[0], 0, strlen($temp[0]) - 2);
            }
        } else {
            foreach ($array as $valor) {
                $temp = explode("-", $valor);
                $res = $this->existeValorArrayNoUltimo($temp, $idEntidad);
                if ($res != -1) {
                    if (!$this->existeValorArray($resultado, substr($res, 0, strlen($res) - 2)))
                        $resultado[] = substr($res, 0, strlen($res) - 2);
                }
            }
        }
        return $resultado;
    }

    function existeValorArray($array, $valor) {
        foreach ($array as $valor1)
            if ($valor1 == $valor)
                return true;
        return false;
    }

    function marcarEntidades($arrayEstructuras, $arrayEst) {
        $cant = count($arrayEstructuras);
        $cant1 = count($arrayEst);
        for ($i = 0; $i < $cant; $i++) {
            for ($j = 0; $j < $cant1; $j++) {
                if ($arrayEstructuras[$i]['id'] == $arrayEst[$j]['identidad']) {
                    $arrayEstructuras[$i]['checked'] = true;
                    break;
                }
            }
        }
        return $arrayEstructuras;
    }

    function ponerComoHoja($array, $arrayEntidades) {
        $n = count($array);
        for ($i = 0; $i < $n; $i++) {
            if ($array[$i]['rgt'] - $array[$i]['lft'] == 1) {
                $arrayEstructuras1 = $this->integrator->metadatos->DameEstructurasInternasSeguridad($array[$i]['idestructura'], true);
                if (!count($arrayEstructuras1))
                    $array[$i]['leaf'] = true;
            } else {
                if ($this->tieneHijasDominio($array[$i]['idestructura'], $arrayEntidades) == -1) {
                    $array[$i]['leaf'] = false;
                }
            }
        }
        return $array;
    }

    function tieneHijasDominio($identidad, $arrayEntidades) {
        if (count($arrayEntidades) == 1)
            return -1;
        foreach ($arrayEntidades as $valor) {
            $temp = explode("-", $valor);
            if (count($temp) > 1) {
                $res = $this->existeValorEnCadenaNoUltimo($temp, $identidad);
                if ($res == -1)
                    return -1;
            }
        }
        return 1;
    }

    function obterEntidadesPonerChecBox($array) {
        $resultado = array();
        foreach ($array as $valor) {
            $temp = explode("-", $valor);
            $resultado[] = substr($temp[count($temp) - 1], 0, strlen($temp[count($temp) - 1]) - 2);
        }
        return $resultado;
    }

    function ponerCheck($arrayEntidades, $arrayIdEntidadesUltimas) {
        $cant = count($arrayEntidades);
        for ($i = 0; $i < $cant; $i++) {
            foreach ($arrayIdEntidadesUltimas as $valor) {
                if ($arrayEntidades[$i]['idestructura'] == $valor) {
                    $arrayEntidades[$i]['checked'] = false;
                    break;
                }
            }
        }
        return $arrayEntidades;
    }

    /**
     * Función <b>asignarrolesAction()</b>
     * Se encarga de obtener los usuarios seleccionados
     * crear un rol de login para cada uno de los seleccionados
     * y asignarle el rol de grupo seleccionado a estos ususarios
     * y roles de login creados.
     *
     * Analizar todavía lo de trabajar con más de un usuario
     *
     */
    function asignarrolesAction() {
        /*
         * Arreglo <b>$arrayUsuario</b>
         * arreglo que contiene el id de los
         * usuarios seleccionados.¡De los que se pueden seleccionar!
         */
        $model = new SegUsuarioModel();
        $tipo = SegUsuarioModel::TipoConexion();
        $arrayUsuario = json_decode(stripslashes($this->_request->getPost('arrayUsuario')));
        /**
         * Variable que viene con el id del rol que se seleccionó
         */
        $idrol = $this->_request->getPost("idrol");
        $arrayTiene = json_decode(stripslashes($this->_request->getPost('arrayTiene')));
        $arrayEstructuras = json_decode(stripslashes($this->_request->getPost('arrayEntidades'))); //estructura chekeadas
        $arrayEstructurasEliminar = json_decode(stripslashes($this->_request->getPost('arrayEntidadesEliminar'))); //estructuras a eliminar ke son las ke se deschekearon
        $arrayPadres = json_decode(stripslashes($this->_request->getPost('arrayPadres'))); //array de nodos marcados sin desplegar, es decir los ke hay ke buscar sus hijos y ponerlos como chekeados
        $arrayPadresEliminar = json_decode(stripslashes($this->_request->getPost('arrayPadresEliminar'))); //array de nodos desmarcados sin desplegar, hay ke buscar sus hijos y ponerlos en el array a eliminar
        /*
         * En el árbol de entidades, después de
         * obtener las entidades padres que se seleccionaron,
         * se buscan sus hijos para seleccionarlos también.
         */
        $arrayHijos = array();
        if (count($arrayPadres)) {
            $arrayHijos = $this->integrator->metadatos->buscarArbolHijosEstructuras($arrayPadres);
            $arrayHijos = $this->arregloBidimensionalToUnidimensional($arrayHijos);
        }
        /*
         * En el árbol de entidades, después deobtener
         * las entidades padres que se desseleccionarion,
         * se buscan sus hijos para desseleccionarlos también
         */
        $arrayHijosEliminar = array();
        if (count($arrayPadresEliminar)) {
            $arrayHijosEliminar = $this->integrator->metadatos->buscarArbolHijosEstructuras($arrayPadresEliminar);
            $arrayHijosEliminar = $this->arregloBidimensionalToUnidimensional($arrayHijosEliminar);
        }
        /*
         * Todas las entidades que se seleccionaron o se desseleccionaron.
         */
        $arrayEstructurasEliminar = array_merge($arrayEstructurasEliminar, $arrayHijosEliminar);
        $arrayEstructuras = array_merge($arrayEstructuras, $arrayHijos);
        /*
         * Recorriendo el id de los ususarios
         */
        foreach ($arrayUsuario as $idusuario) {

            $arrayEstGeneral = $model->cargarentidadesusuariorol($idusuario[0], $idrol);
            $arrayEstGeneral = $this->arregloBidimensionalToUnidimensional($arrayEstGeneral);
            $arrayEstructurasUsuario = $arrayEstructuras;
            if (count($arrayEstGeneral))
                $arrayEstructurasUsuario = array_diff($arrayEstructurasUsuario, $arrayEstGeneral);

            $cantEstElim = count($arrayEstructurasEliminar);
            $cantEstrMarcadas = count($arrayEstructurasUsuario);
            $cantEstrGeneral = count($arrayEstGeneral);

            if ($cantEstElim) {

                /*
                 * Eliminación******************************************************
                 */
                if (!$cantEstrMarcadas && ($cantEstElim == $cantEstrGeneral)) {
                    /*
                     * Elimina las entidades de los usuarios de ese rol
                     */
                    SegUsuarioModel::eliminarusuariorol($idusuario[0], $idrol);


                    //En este punto se *******************                      
                    SegUsuarioDatServidorDatGestorModel::ElimminarRolLogin($idrol, $idusuario[0], $tipo);
                    echo "{'bien':4}"; //Se quitan todas las entidades al rol
                    return;
                }
            }

            $arrayExternas = $this->integrator->metadatos->cantIdEstructurasDominio($idusuario[1], $arrayEstructurasUsuario, 1);
            if (!count($arrayExternas) && !count($arrayEstGeneral)) {
                echo("{'codMsg':3,mensaje:perfil.etiquetas.lbMasgAsErr}");
            }

            if (count($arrayEstructurasEliminar)) {
                foreach ($arrayEstructurasEliminar as $entel) {
                    $entidadusuariorolelim = new stdClass();
                    $entidadusuariorolelim->idusuario = $idusuario[0];
                    $entidadusuariorolelim->idrol = $idrol;
                    $entidadusuariorolelim->identidad = $entel;
                    $arrayentidadusuariorolElim[] = $entidadusuariorolelim;
                }
            }
            if (count($arrayEstructurasUsuario)) {

                $datos = $model->comprobarExisteRol($idusuario[0], $idrol);

                foreach ($arrayEstructurasUsuario as $entins) {

                    if (!$this->verificarRol($entins, $datos)) {
                        $funcionalidades1 = array();
                        $funcionalidades2 = array();
                        $entidadusuariorolins = new DatEntidadSegUsuarioSegRol();
                        $entidadusuariorolins->idusuario = $idusuario[0];
                        $entidadusuariorolins->idrol = $idrol;
                        $entidadusuariorolins->identidad = $entins;
                        $arrayentidadusuariorolAdd[] = $entidadusuariorolins;
                    }
                }
            }

            //*****************************//
            $roles = $model->obtenerrol1($idusuario[0], $entins);
            $roles = $this->arregloBidimensionalToUnidimensionalGeneral($roles, 'idrolasig');
            $funcionalidades1 = array();
            $funcionalidades1 = $model->obtenerFuncionalidades1($roles);
            $funcionalidades2 = $model->obtenerFuncionalidades1($idrol);
            $funcionalidades2 = $this->arregloBidimensionalToUnidimensionalGeneral($funcionalidades2, 'idfuncionalidad');
        }
        /*
         * Creación de rol de login
         * asignación de rol de grupo a rol de login
         * 
         */
        foreach ($arrayUsuario as $idusuario) {
            if ($tipo == 3)
                SegUsuarioDatServidorDatGestorModel::CrearRolLogin_AsignarRolGrupoServidores($idrol, $idusuario[0], $tipo);
        }
        if ($model->asignarroles($arrayentidadusuariorolAdd, $arrayentidadusuariorolElim)) {
            echo "{'bien':1}";
        }
        else
            echo("{'codMsg':3,mensaje:perfil.etiquetas.lbMasgAsErr}");
    }

    function verificarRol($elem, $array) {
        foreach ($array as $valores)
            if ($elem == $valores['identidad'])
                return true;
        return false;
    }

    function arrayUltimasEntidades($array) {
        $cantidad = count($array);
        $externa = explode('_', $array[$cantidad - 1]);
        if ($externa[1] == 'e')
            return $externa[0];
        else
            return 0;
    }

    function agregarChekeados($arreglo, $arreglo1, $id, $cadenaPadre = '')
    {
        if ($cadenaPadre == '') {
            $pos = $this->obtenerPos($arreglo, $id);
            $cadenaPadre = $arreglo[$pos];
        }
        if (substr($cadenaPadre, -1) == 'e') {
            $arrayEstructuras = $this->integrator->metadatos->DameEstructuraSeguridad($id);
            $arrayEstructuras1 = $this->integrator->metadatos->DameEstructurasInternasSeguridad($id, true);
            $arrayEstructuras = array_merge_recursive($arrayEstructuras, $arrayEstructuras1);
        }
        else
            $arrayEstructuras = $this->integrator->metadatos->DameHijosInternaSeguridad($id);
        foreach ($arrayEstructuras as $valor) {
            $cad1 = ($valor['tipo'] == 'externa') ? $cadenaPadre . '-' . $valor['id'] . '_e' : $cadenaPadre . '-' . $valor['id'] . '_i';
            $arreglo[] = $cad1;
            $arreglo1[] = $valor['id'];
            $this->agregarChekeados($arreglo, $arreglo1, $valor['id'], $cad1);
        }
    }

    function agregaraArrayEliminar($arreglo, $id, $tipo = '') {
        if ($tipo == 'externa') {
            $arrayEstructuras = $this->integrator->metadatos->DameEstructuraSeguridad($id);
            $arrayEstructuras1 = $this->integrator->metadatos->DameEstructurasInternasSeguridad($id, true);
            $arrayEstructuras = array_merge_recursive($arrayEstructuras, $arrayEstructuras1);
        } else {
            $arrayEstructuras = $this->integrator->metadatos->DameHijosInternaSeguridad($id);
        }
        foreach ($arrayEstructuras as $valor) {
            $arreglo[] = $valor['id'];
            $this->agregaraArrayEliminar($arreglo, $valor['id'], $valor['tipo']);
        }
    }

    function obtenerPos($array, $id) {
        $cant = count($array);
        for ($i = 0; $i < $cant; $i++)
            if ($this->obtenerUltimoIdCadena($array[$i]) == $id)
                return $i;
        return -1;
    }

    function obtenerUltimoIdCadena($cadena) {
        $array = explode('-', $cadena);
        return substr($array[count($array) - 1], 0, strlen($array[count($array) - 1]) - 2);
    }

    function cambiarpasswordAction() {
        $model = new SegUsuarioModel();
        $usuario = $this->_request->getPost('usuariop');
        $newpass = $this->_request->getPost('contrasenap');
        $verificar = $model->verificarpass($usuario);
        $objclaveacceso = new SegClaveacceso();
        if ($this->verificarpass($newpass)) {
            $restricc_array = $model->cargarrestriccion();
            $cant_filas = $model->cargarclaves($verificar[0]->idusuario);

            foreach ($cant_filas as $claves) {

                if ($claves['valor'] == true) {
                    $objupdate = Doctrine::getTable('SegClaveacceso')->find($claves['pkidclaveacceso']);
                    $objupdate->valor = false;
                    $objupdate->save();
                }
                if ($claves['clave'] == md5($newpass))
                    throw new ZendExt_Exception('SEG056');
            }
            if (count($cant_filas) == $restricc_array[0]['canthistorico']) {
                $objclaveacceso = Doctrine::getTable('SegClaveacceso')->find($cant_filas[0]['pkidclaveacceso']);
            }
            $objusuario = Doctrine::getTable('SegUsuario')->find($verificar[0]->idusuario);
            $objusuario->contrasenna = md5($newpass);
            $objusuario->contrasenabd = md5($newpass);
            $objusuario->save();
            $objclaveacceso->valor = true;
            $objclaveacceso->fechainicio = date('d-m-Y H:i:s');
            $objclaveacceso->fechafin = $this->calcularFecha(+$restricc_array[0]['diascaducidad']);
            $objclaveacceso->clave = md5($newpass);
            $objclaveacceso->idusuario = $verificar[0]->idusuario;
            $objclaveaccesomodel = new SegClaveAccesoModel();
            $objclaveaccesomodel->insertClave($objclaveacceso);

            // $this->showMessage('Su contrase&ntilde;a ha sido cambiada correctamente.');
            echo "{'codMsg':1,'mensaje':perfil.etiquetas.lbpassword}";
        }
        else
            return false;
    }

    function validarasignacionrolesAction() {
        $model = new SegUsuarioModel();
        $arrayUsuario = json_decode(stripslashes($this->_request->getPost('ArrayUsuarios')));
        $arrayroles = array();
        $result = $model->validarasignacionroles($arrayUsuario);
        $recorrer = $result[0]['DatEntidadSegUsuarioSegRol'];
        $temp = array();
        $temp = $this->borrarCampoUsurio($recorrer);
        unset($result[0]);
        foreach ($result as $record) {
            if (!$this->compararArreglos($temp, $this->borrarCampoUsurio($record['DatEntidadSegUsuarioSegRol']))) {
                throw new ZendExt_Exception('SEG022');
            }
        }
        echo "{'success':true}";
    }

    function borrarCampoUsurio($recorrer) {
        $temporal = $recorrer;
        foreach ($recorrer as $key => $valores) {
            unset($temporal[$key]['idusuario']);
        }
        return $temporal;
    }

    function compararArreglos($array1, $array2) {
        foreach ($array1 as $key => $value) {
            if (!(isset($array2[$key]) && $value['idrol'] == $array2[$key]['idrol'] && $value['identidad'] == $array2[$key]['identidad']))
                return false;
        }
        return true;
    }

    function ActivarUsuarioAction() {
        $ArrayUserActivar = json_decode(stripslashes($this->_request->getPost('arrUsuarioAct')));
        $bandera = true;
        foreach ($ArrayUserActivar as $usuarios) {
            $objusuario = Doctrine::getTable('SegUsuario')->find($usuarios);
            $objusuario->activo = 1;
            $objusuario->save();
        }
        echo "{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgActivados}";
    }

    function DesactivarUsuarioAction() {
        $arrUsuarioDesact = json_decode(stripslashes($this->_request->getPost('arrUsuarioDesact')));

        if (SegUsuario::DesactivarUsuarios($arrUsuarioDesact))
            echo "{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgDesactivados}";
    }

    function cargarUsuariosConectadosAction() {
        $model = new SegUsuarioModel();
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $usuarios = $model->obtenerUsuariosConectados($limit, $start);
        $arrayusuarios = Array();
        $cantidad = $model->obtenerCantidadUsuariosConectados();
        if (count($usuarios)) {
            foreach ($usuarios as $key => $valor) {
                $arrayusuarios[$key]['idcertificado'] = $valor['idcertificado'];
                $arrayusuarios[$key]['idusuario'] = $valor['idusuario'];
                $arrayusuarios[$key]['idsession'] = $valor['idsession'];
                $arrayusuarios[$key]['nombreusuario'] = $valor[SegUsuario]['nombreusuario'];
                $arrayusuarios[$key]['fecha'] = $valor['fecha'];
                $arrayusuarios[$key]['hora'] = $valor['hora'];
                $arrayusuarios[$key]['rol'] = $valor['rol'];
                $arrayusuarios[$key]['entidad'] = $valor['entidad'];
            }
            $result = array('cantidad_filas' => $cantidad, 'datos' => $arrayusuarios);
            echo json_encode($result);
            return;
        }
        else
            echo json_encode($arrayusuarios);
        return;
    }

    function cerrarSessionAction() {
        $global = ZendExt_GlobalConcept::getInstance();
        $user = $global->Perfil->usuario;
        $ArraySessCerrada = json_decode(stripslashes($this->_request->getPost('ArraySessCerrada')));
        $ArrayIdSess = json_decode(stripslashes($this->_request->getPost('ArrayIdSess')));
        $ArrayUsuario = json_decode(stripslashes($this->_request->getPost('ArrayUsuario')));
        $arrayusuarios = Array();
        foreach ($ArraySessCerrada as $key => $value) {
            $arrayusuarios[$key]['idcertificado'] = $value;
        }
        foreach ($ArrayIdSess as $key => $value) {
            $arrayusuarios[$key]['idsesion'] = $value;
        }
        foreach ($ArrayUsuario as $key => $value) {
            $arrayusuarios[$key]['usuario'] = $value;
        }
        $mensaje = '';
        foreach ($arrayusuarios as $key => $value) {
            if ($value['usuario'] == $user) {
                $mensaje = "{'codMsg':3,'mensaje':perfil.etiquetas.lbMsgErrorCerrar}";
                //echo("{'codMsg':3,'mensaje':perfil.etiquetas.lbMsgErrorCerrar}");                
                break;
            }
            SegUsuarioModel::cerrarSesionAbierta($value['idcertificado']);
            if ($value['idsesion']) {
                $sigis = ZendExt_Aspect_Security_Sgis::getInstance();
                $sigis->deleteSessionFile($value['idsesion']);
            }

            $mensaje = "{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfCerrarSesion}";
        }
        echo($mensaje);
    }

    private function arregloBidimensionalToUnidimensionalGeneral($arrayi, $id) {
        $array = array();
        foreach ($arrayi as $est)
            $array[] = $est[$id];
        return $array;
    }

    function restringirfuncionalidadAction() {
        $arrayFunDechequeadas = json_decode(stripslashes($this->_request->getPost("arrayFunDechequeadas")));
        $arrayFunChequeadas = json_decode(stripslashes($this->_request->getPost("arrayFunChequeadas")));
        $idusuario = $this->_request->getPost("idusuario");
        $idrol = $this->_request->getPost("idrol");
        $identidad = $this->_request->getPost("identidad");
        //echo'<pre>';print_r($arrayFunDechequeadas);die;
        if (count($arrayFunChequeadas)) {
            DatFuncionalidadesRestringidasUsuarioEntidadRol::eliminarfuncionalidades($arrayFunChequeadas, $idusuario, $idrol, $identidad);
        }
        if (count($arrayFunDechequeadas))
            foreach ($arrayFunDechequeadas as $arrayfuncsist) {
                foreach ($arrayfuncsist[1] as $valor) {
                    $obj = new DatFuncionalidadesRestringidasUsuarioEntidadRol();
                    $obj->idsistema = $arrayfuncsist[0];
                    $obj->identidad = $identidad;
                    $obj->idrol = $idrol;
                    $obj->idusuario = $idusuario;
                    $obj->idfuncionalidad = $valor;
                    $obj->save();
                }
            }
        echo "{'bien':1}";
        return;
    }

    function cargarestructurasresfuncionalidadAction() {
        $idusuario = $this->_request->getPost("idusuario");
        $idEstructura = $this->_request->getPost("node");
        $tcheck = $this->_request->getPost('tcheck');
        $iddominio = $this->_request->getPost("iddominio");
        $arrayEst = DatEntidadSegUsuarioSegRol::cargarentidadesusuario($idusuario);
        $arrayEst = $this->arregloBidimensionalToUnidimensional($arrayEst);
        $arrayEstructuras = $this->integrator->metadatos->buscarHijosEstructurasDadoArray($iddominio, $idEstructura, 0, $arrayEst);

        $estructuras = array();
        foreach ($arrayEstructuras as $key => $est) {
            $estructuras[$key] = $est;
            if (!count(array_intersect($arrayEst, array($est['id']))))
                $estructuras[$key]['disabled'] = true;
        }
        echo json_encode($estructuras);
        return;
    }

    function cargarrolesresfuncionalidadAction() {
        $idusuario = $this->_request->getPost('idusuario');
        $idestructura = $this->_request->getPost('idestructura');
        $roles = SegRol::obtenerrolesusuarioentidad($idusuario, $idestructura)->toArray(true);
        echo json_encode(array('datos' => $roles));
    }

    function cargarsistemafuncAction() {
        $idnodo = $this->_request->getPost('node');
        $idsistema = $this->_request->getPost('idsistema');
        $idestructura = $this->_request->getPost('idestructura');
        $idusuario = $this->_request->getPost('idusuario');
        $rol = $this->_request->getPost('idrol');
        if ($idnodo == 0)
            $sistemas = DatSistema::cargarsistemasdelrol($idnodo, $rol);
        else
            $sistemas = DatSistema::cargarsistemasdelrol($idsistema, $rol);


        $valores = 0;
        if (count($sistemas)) {
            foreach ($sistemas as $valor) {
                $funcionalidadArr[$valores]['id'] = $valor['id'] . '_' . $idnodo;
                $funcionalidadArr[$valores]['idsistema'] = $valor['id'];
                $funcionalidadArr[$valores]['text'] = $valor['text'];
                $tiene = $this->tieneFuncionalidades($valor['id'], $rol);
                if ($tiene) {
                    $funcionalidadArr[$valores]['tiene'] = 1;
                    if ($rol)
                        $funcionalidadArr[$valores]['icon'] = '../../views/images/folder.png';
                }
                if (!$tiene && $rol) {
                    $bandera = 0;
                    $validaacceso = $this->tienePermisoFuncionalidades($valor['id'], $rol, $bandera);
                    if ($validaacceso)
                        $funcionalidadArr[$valores]['icon'] = '../../views/images/folder.png';
                }
                $valores++;
            }
        }
        if ($idnodo) {
            $funcionalidad = DatSistemaSegRolDatFuncionalidad::cargarsist_funcionalidades($idsistema, $rol);

            $funcionalidadesRest = DatFuncionalidadesRestringidasUsuarioEntidadRol:: obtenerFuncionalidadesRes($idsistema, $idestructura, $idusuario, $rol);

            if (count($funcionalidadesRest))
                $funcionalidadesRest = $this->arregloUnidimensionalFuncionalidades($funcionalidadesRest);
            //echo'<pre>';print_r( $arrayFuncionalidades);die;
            if ($funcionalidad->getData() != NULL) {
                foreach ($funcionalidad as $valorfun) {
                    $funcionalidadArr[$valores]['id'] = $valorfun->id . '_' . $idnodo;
                    $funcionalidadArr[$valores]['idfuncionalidad'] = $valorfun->id;
                    $funcionalidadArr[$valores]['text'] = $valorfun->text;
                    $funcionalidadArr[$valores]['idsistema'] = $valorfun->idsistema;
                    $funcionalidadArr[$valores]['leaf'] = true;
                    if (count($funcionalidadesRest))
                        if (count(array_intersect($funcionalidadesRest, array($valorfun->id))))
                            $funcionalidadArr[$valores]['checked'] = false;
                        else
                            $funcionalidadArr[$valores]['checked'] = true;
                    else
                        $funcionalidadArr[$valores]['checked'] = true;
                    $valores++;
                }
            }
        }
        if (!count($funcionalidadArr))
            $funcionalidadArr = array();
        echo json_encode($funcionalidadArr);
        return;
    }

    function tieneFuncionalidades($idsistema, $rol) {
        return DatSistemaSegRolDatFuncionalidad::tieneFuncionalidad($rol, $idsistema);
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
                    if ($bandera != $raiz) {
                        $this->tienePermisoFuncionalidades($hijo['idsistema'], $idrol, $bandera);
                    }
                    return 1;
                }
                return 0;
            }
            else
                return 0;
        }
    }

    function arregloUnidimensionalFuncionalidades($arrayFuncionalidades) {
        $array = array();
        foreach ($arrayFuncionalidades as $est)
            $array[] = $est['idfuncionalidad'];
        return $array;
    }

}

?>
