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

class SegUsuarioModel extends ZendExt_Model {

    public function SegUsuarioModel() {
        parent::ZendExt_Model();
    }

    public function buscarHijosEstructurasDadoArray($iddominio, $idEstructura, $arrayEst){
        return $this->integrator->metadatos->buscarHijosEstructurasDadoArray($iddominio, $idEstructura, 1, $arrayEst);
    }

    function insertarservicio($instance) {
        $instance->save();
        return true;
    }

    public function insertarservidoraut($sistemaservautenticacion) {
        if ($sistemaservautenticacion)
            $sistemaservautenticacion->save();
    }

    public function insertarperfil($result) {
        foreach ($result as $valor) {
            $valor->save();
        }
        return true;
    }

    public function modificarperfil($resultModificar, $resultEliminar, $resultAdicionar) {
        if ($resultModificar) {
            foreach ($resultModificar as $valor) {
                $valor->save();
            }
        }
        if ($resultEliminar) {
            foreach ($resultEliminar as $valor)
                $valor->delete();
        }
        if ($resultAdicionar) {
            foreach ($resultAdicionar as $valor)
                $valor->save();
        }
        return true;
    }

    public function modificarusuario($sistemaservautenticacion, $usuario) {
        $usuario->save();
        if ($sistemaservautenticacion) {
            $sistemaservautenticacion->save();
        }
        return true;
    }

    public function asignarroles($arrayentidadusuariorolAdd, $arrayentidadusuariorolElim) {
        if (count($arrayentidadusuariorolElim)) {
            foreach ($arrayentidadusuariorolElim as $valor)
                DatEntidadSegUsuarioSegRol::eliminarentidadusuariorol($valor->idusuario, $valor->idrol, $valor->identidad);
        }
        if (count($arrayentidadusuariorolAdd)) {
            foreach ($arrayentidadusuariorolAdd as $valor1)
                $valor1->save();
        }
        return true;
    }

    public function obtenerrolesusuario($idusuario) {
        $roles = SegRol::obtenerrolesusuario($idusuario)->toArray(true);
        return $roles;
    }

    public function obtenerCantRolesUsuario($idusuario) {
        $cantidad = SegRol::obtenerCantRolesUsuario($idusuario);
        return $cantidad;
    }

    public function cargarperfilusuario($idusuario) {
        $perfil = SegUsuario::cargarperfilusuario($idusuario);
        return $perfil;
    }

    public function cargarcamposdadovalores($idvalor) {
        $arraycampos = NomValor::cargarcamposdadovalores($idvalor);
        return $arraycampos;
    }

    public function cargardominioUsuario($idusuario) {
        $permisos = SegCompartimentacionusuario::cargardominioUsuario($idusuario);
        return $permisos;
    }

    public function cargarUsuariosconpermisosaDominios($filtroDominio) {
        $usuariosconpermisosadominios = SegUsuario::cargarUsuariosconpermisosaDominios($filtroDominio);
        return $usuariosconpermisosadominios;
    }

    public function cargarUsuariosDominios($iddominio) {
        $usuariosdelDominio = SegUsuarioNomDominio::cargarUsuariosDominios($iddominio);
        return $usuariosdelDominio;
    }

    public function usuariosSinDominio() {
        $usuariosSinDominio = SegUsuario::usuariosSinDominio();
        return $usuariosSinDominio;
    }

    public function cargarUsuarioDominioCompartimentado($iddominio) {
        $usuarioscompartimentados = SegCompartimentacionusuario::cargarUsuarioDominio($iddominio);
        return $usuarioscompartimentados;
    }

    public function cargarGridUsuarioBuscados($nombreusuario, $arrayresult, $dominiobuscar, $activar, $limit, $start) {
        $datosusuario = SegUsuario::cargarGridUsuarioBuscados($nombreusuario, $arrayresult, $dominiobuscar, $activar, $limit, $start);
        return $datosusuario;
    }

    public function cantidadFilasUsuariosBuscados($nombreusuario, $arrayresult, $dominiobuscar, $activar) {
        $cantf = SegUsuario::cantidadFilasUsuariosBuscados($nombreusuario, $arrayresult, $dominiobuscar, $activar);
        return $cantf;
    }

    public function cargarGridUsuario($arrayresult, $limit, $start) {
        $datosusuario = SegUsuario::cargarGridUsuario($arrayresult, $limit, $start);
        return $datosusuario;
    }

    public function cantidadFilas($arrayresult) {
        $cantf = SegUsuario::cantidadFilas($arrayresult);
        return $cantf;
    }

    public function cargarservidoraut($idusuario) {
        $datosservidoraut = SegUsuario::cargarservidoraut($idusuario);
        return $datosservidoraut;
    }

    public function cargarroles($idrol, $idusuario, $identidad) {
        $rolmarcado = DatEntidadSegUsuarioSegRol::cargarroles($idrol, $idusuario, $identidad);
        return $rolmarcado;
    }

    public function cargarentidades() {
        $entidades = DatSistemaDatEntidad::cargarentidades();
        return $entidades;
    }

    public function cargarentidadesrol($identidad) {
        $entidadesrol = SegRol::cargarentidadesrol($identidad);
        return $entidadesrol;
    }

    public function comprovarestado($idusuario) {
        $comprovar = SegUsuario::comprovarestado($idusuario);
        return $comprovar;
    }

    public function CargarRolXIdUsuario($idusuario) {
        $roles = DatEntidadSegUsuarioSegRol::CargarRolXIdUsuario($idusuario);
        return $roles;
    }

    public function eliminarusuario($idusuario) {
        SegUsuario::eliminarusuario($idusuario);
    }

    public function nombusuariocontrasennabd($idusuarioNoDelete) {
        $name = SegUsuario::nombusuariocontrasennabd($idusuarioNoDelete);
        return $name;
    }

    public function cargarrestriccion() {
        $restricc_array = SegRestricclaveacceso::cargarrestriccion();
        return $restricc_array;
    }

    public function cantservidoraut($idusuario) {
        $servidor = SegUsuarioDatSerautenticacion::cantservidoraut($idusuario);
        return $servidor;
    }

    public function eliminarusuarioservidoraut($idusuario) {
        SegUsuarioDatSerautenticacion::eliminarusuarioservidoraut($idusuario);
    }

    static function ModificarNombreUsuarioGestor($OldDenominacion, $NewDenominacion, $idusuario, $tipo) {
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $RSA = new ZendExt_RSA_Facade();
        $user = "";
        $pass = "";
        $excecRevocarPermisos = "";
        $IP_GEST_PUERTO = array();
        $roles = CargarRolXIdUsuario($idusuario);
        foreach ($roles as $rol) {
            $rol_serv_gestBD = SegRolDatServidorDatGestor::getRoles_Servidores_Gestores_BDByIdsRoles($rol->idrol);
            $rol_serv_gestBD = $Pgsql->Unir__R_S_G_B_con_U_S_G($rol_serv_gestBD, array(), $tipo);

            foreach ($rol_serv_gestBD as $ip => $ArrayGestores) {
                foreach ($ArrayGestores as $gestor => $ArrayPuertos) {
                    foreach ($ArrayPuertos as $puerto => $ArrayBDs) {
                        if ($IP_GEST_PUERTO[$ip . $gestor . $puerto] == "") {
                            foreach ($ArrayBDs as $bd => $DatasRoles) {
                                if ($bd != "usuario_deleteNoBD") {
                                    if ($user == "") {
                                        $user = $DatasRoles['rolconex'];
                                        $pass = $RSA->decrypt($DatasRoles['passconex']);
                                    }
                                    $forPass = nombusuariocontrasennabd($idusuario);
                                    $passWordUser = $forPass[0]['contrasenabd'];
                                    $passWordUser = $Pgsql->EncritarPass($tipo, " ", $passWordUser);
                                    $Name = "usuario_$OldDenominacion" . "_acaxia$tipo";
                                    $newName = "usuario_$NewDenominacion" . "_acaxia$tipo";
                                    $createRoles = "ALTER ROLE $Name RENAME TO $newName;";
                                    $createRoles .= "ALTER ROLE $newName WITH LOGIN PASSWORD '$passWordUser';";
                                    break;
                                }
                            }
                            $conn = "$gestor://$user:$pass@$ip:$puerto/$bd";
                            $Pgsql->EjecutarCadenadeConsultas($conn, $createRoles);
                            $IP_GEST_PUERTO[$ip . $gestor . $puerto] = true;
                            if ($_SESSION['username_transaction'] == $OldDenominacion) {
                                $_SESSION['username_transaction'] = $NewDenominacion;
                            }
                        }
                    }
                    $user = "";
                }
            }
        }
    }

    public function cargarcombodesktop() {
        $datoscombo = NomDesktop::cargarcombodesktop();
        return $datoscombo;
    }

    public function obtenerIdDominios($iddominio) {
        $dominios = SegUsuario::obtenerIdDominios($iddominio);
        return $dominios;
    }

    public function obtenerIdDominiosCompartimentados($iddominio) {
        $dominios = SegUsuario::obtenerIdDominiosCompartimentados($iddominio);
        return $dominios;
    }

    public function cargarcomboidioma() {
        $datoscombo = NomIdioma::cargarcomboidioma();
        return $datoscombo;
    }

    public function cargarcombotema() {
        $datoscombo = NomTema::cargarcombotema();
        return $datoscombo;
    }

    public function contarusuario($nombusuario) {
        $usuario = SegUsuario::contarusuario($nombusuario);
        return $usuario;
    }

    public function cargarcomboservidoresaut() {
        $servidoresaut = DatServidor::cargarcomboservidoresaut();
        return $servidoresaut;
    }

    public function RolesdelDominio($filtroDominio) {
        $arrayRolesDominio = SegRolNomDominio::RolesdelDominio($filtroDominio);
        return $arrayRolesDominio;
    }

    public function cargarRolesDominio($filtroDominio) {
        $arrayRolesPermisoDominio = SegCompartimentacionroles::cargarRolesDominio($filtroDominio);
        return $arrayRolesPermisoDominio;
    }

    public function obtenerrolesasociados($limit, $start, $idusuario, $arrayResult) {
        $result = SegRol::obtenerrolesasociados($limit, $start, $idusuario, $arrayResult);
        return $result;
    }

    public function cantrolesDominio($arrayResult) {
        $cantrol = SegRol::cantrolesDominio($arrayResult);
        return $cantrol;
    }

    public function obtenerRolesBuscado($rolbuscado, $limit, $start, $idusuario) {
        $result = SegRol::obtenerRolesBuscado($rolbuscado, $limit, $start, $idusuario);
        return $result;
    }

    public function cantrolBuscados($filtroDominio, $rolbuscado) {
        $cantrol = SegRol::cantrolBuscados($filtroDominio, $rolbuscado);
        return $cantrol;
    }

    public function cargarentidadesusuariorol($idusuario, $idrol) {
        $arrayEst = DatEntidadSegUsuarioSegRol::cargarentidadesusuariorol($idusuario, $idrol);
        return $arrayEst;
    }

    public function cargarentidadesusuarionorol($idusuario, $idrol) {
        $arrayEstNoRol = DatEntidadSegUsuarioSegRol::cargarentidadesusuarionorol($idusuario, $idrol);
        return $arrayEstNoRol;
    }

    static function eliminarusuariorol($idusuario, $idrol) {
        DatEntidadSegUsuarioSegRol::eliminarusuariorol($idusuario, $idrol);
    }

    public function comprobarExisteRol($idusuario, $idrol) {
        $datos = DatEntidadSegUsuarioSegRol::comprobarExisteRol($idusuario, $idrol);
        return $datos;
    }

    public function obtenerrol1($idusuario, $entins) {
        $roles = DatEntidadSegUsuarioSegRol::obtenerrol1($idusuario, $entins);
        return $roles;
    }

    public function obtenerFuncionalidades1($roles) {
        $funcionalidades1 = DatFuncionalidad::obtenerFuncionalidades1($roles);
        return $funcionalidades1;
    }

    public function obtenerRegla($idfuncionalidad1, $idfuncionalidad2) {
        $var = DatReglas::obtenerRegla($idfuncionalidad1, $idfuncionalidad2);
        return $var;
    }

    public function nombreFuncionalidad($idfuncionalidad) {
        $nombre = DatFuncionalidad::nombreFuncionalidad($idfuncionalidad);
        return $nombre;
    }

    public function verificarpass($usuario) {
        $verificar = SegUsuario::verificarpass($usuario);
        return $verificar;
    }

    public function cargarclaves($idusuario) {
        $cant_filas = SegClaveacceso::cargarclaves($idusuario);
        return $cant_filas;
    }

    public function validarasignacionroles($arrayUsuario) {
        $result = SegUsuario::validarasignacionroles($arrayUsuario);
        return $result;
    }

    public function obtenerUsuariosConectados($limit, $start) {
        $usuarios = SegCertificado::obtenerUsuariosConectados($limit, $start);
        return $usuarios;
    }

    public function obtenerUserConectados() {
        $usuarios = SegCertificado::obtenerUserConectados();
        return $usuarios;
    }

    public function obtenerCantidadUsuariosConectados() {
        $cantidad = SegCertificado::obtenerCantidadUsuariosConectados();
        return $cantidad;
    }

    static function cerrarSesionAbierta($idcertificado) {
        SegCertificado::cerrarSesionAbierta($idcertificado);
    }

    static function TipoConexion() {
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->configConection;
        $DOM_XML_Conex = new DOMDocument();
        $contentfile = file_get_contents($dirconfigConection);
        $DOM_XML_Conex->loadXML($contentfile);
        $elements = SegUsuarioModel::getElementsByAttr($DOM_XML_Conex, "seleccion", "true");
        $element = $elements->item(0);
        return $element->getAttribute('tipo');
    }

    public static function getElementsByAttr($DOM, $nameAtrr, $value) {
        $xpath = new DOMXpath($DOM);
        $elements = $xpath->query("//*[@$nameAtrr='$value']");
        if ($elements->length > 0) {
            return $elements;
        }
        return false;
    }

}

?>