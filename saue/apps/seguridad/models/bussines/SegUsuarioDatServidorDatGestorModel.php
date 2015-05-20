<?php

class SegUsuarioDatServidorDatGestorModel extends ZendExt_Model {

    public function SegUsuarioDatServidorDatGestorModel() {
        parent::ZendExt_Model();
    }

    public function Adicionar($usuarioLogin) {
        $usuarioLogin->save();
    }

    public function Eliminar($usuarioLogin) {
        $usuarioLogin->delete();
    }

    static function ElimminarRolLogin($idrol, $idusuario, $tipo) {        
        $rol_serv_gest_bd_rolbd = array();
        $rol_serv_gest_bd_rolbd = SegRolDatServidorDatGestor::getRoles_Servidores_Gestores_BDByIdsRoles(array($idrol));
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $RSA = new ZendExt_RSA_Facade();
        $rol_serv_gest_bd_rolbd = SegUsuarioDatServidorDatGestorModel::OrdenarArregloROl_SERV_GEST_BD($rol_serv_gest_bd_rolbd);                
        $user_pass = SegUsuario::nombusuariocontrasennabd($idusuario);
        $denominacion = "usuario_" . $user_pass[0]['nombreusuario'] . "_acaxia$tipo";    

        foreach ($rol_serv_gest_bd_rolbd as $conn => $datos) {
            $sql = "drop role $denominacion;";
            $usuario = SegUsuarioDatServidorDatGestor::Find($idusuario, $datos['datos']['idservidor'], $datos['datos']['idgestor']);
            $Pgsql->EjecutarCadenadeConsultas($conn, $sql);
            $this->Eliminar($usuario);                    
        }
    }
    
        static function CrearRolLogin_AsignarRolGrupoServidores($idrol, $idusuario, $tipo) {
        $rol_serv_gest_bd_rolbd = array();
        $rol_serv_gest_bd_rolbd = SegRolDatServidorDatGestor::getRoles_Servidores_Gestores_BDByIdsRoles(array($idrol));

        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $RSA = new ZendExt_RSA_Facade();

        $rol_serv_gest_bd_rolbd = $this->OrdenarArregloROl_SERV_GEST_BD($rol_serv_gest_bd_rolbd);

        $user_pass = SegUsuario::nombusuariocontrasennabd($idusuario);
        $denominacion = "usuario_" . $user_pass[0]['nombreusuario'] . "_acaxia$tipo";
        $passWordUser = $user_pass[0]['contrasenabd'];
        $passWordUser = $Pgsql->EncritarPass($tipo, " ", $passWordUser);        

        foreach ($rol_serv_gest_bd_rolbd as $conn => $datos) {

            $usuario = SegUsuarioDatServidorDatGestor::Find($idusuario, $datos['datos']['idservidor'], $datos['datos']['idgestor']);
            $rol = $datos['datos']['denominacion'];
            $rol = "rol_$rol" . "_acaxia3";

            if ($usuario == null) {
                $sql = "create role " . $denominacion . " with login password '$passWordUser';";
                $Pgsql->EjecutarCadenadeConsultas($conn, $sql);
                $usuarioLogin = new SegUsuarioDatServidorDatGestor();
                $usuarioLogin->idusuario = $idusuario;
                $usuarioLogin->idservidor = $datos['datos']['idservidor'];
                $usuarioLogin->idgestor = $datos['datos']['idgestor'];
                $sql = "GRANT $rol TO $denominacion;";
                $Pgsql->EjecutarCadenadeConsultas($conn, $sql);
                $this->Adicionar($usuarioLogin);
            } else {
                $sql = "GRANT $rol TO $denominacion;";
                $Pgsql->EjecutarCadenadeConsultas($conn, $sql);
            }
        }
    }
    
     public static function OrdenarArregloROl_SERV_GEST_BD($rol_serv_gest_bd_rolbd) {         
        $array = array();
        $RSA = new ZendExt_RSA_Facade();
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        foreach ($rol_serv_gest_bd_rolbd as $value) {

            $ip = $value['DatServidor']['ip'];
            $gestor = $value['DatGestor']['gestor'];
            $puerto = $value['DatGestor']['puerto'];
            $bd = $value['DatBd']['denominacion'];
            $user = $value['SegRolesbd']['nombrerol'];
            $pass = $RSA->decrypt($value['SegRolesbd']['passw']);
            $connex = "$gestor://$user:$pass@$ip:$puerto/$bd";

            $conexiones['servidor'] = $ip;
            $conexiones['gestor'] = $gestor;
            $conexiones['puerto'] = $puerto;
            $conexiones['bd'] = $bd;
            $conexiones['user'] = $user;
            $conexiones['pass'] = $pass;

            $Pgsql->VerificarDisponibilidadServidorBD(array($conexiones));
            if ($array[$connex] == "") {
                $datos = array();
                $datos["denominacion"] = $value['denominacion'];
                $datos["idservidor"] = $value['idservidor'];
                $datos["idgestor"] = $value['idgestor'];
                $array[$connex]['datos'] = $datos;
            }
        }
        return $array;
    }

}

