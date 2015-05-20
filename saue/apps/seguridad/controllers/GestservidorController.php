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

class GestservidorController extends ZendExt_Controller_Secure {

    function init() 
    {
        parent::init();
    }

    function gestservidorAction() {
        $this->render();
    }
//Funcionalidad que permite insertar un servidor
    function insertarservAction() {

        $openldap = $this->_request->getPost('openldap');
        $ldap = $this->_request->getPost('ldap');
        $cadenaconexion = $this->_request->getPost('baseDN');
        $passw = $this->_request->getPost('passw');
        $user = $this->_request->getPost('user');
        $puerto = $this->_request->getPost('puerto');
        $chbssl = $this->_request->getPost('chekssl');
        $chbtsl = $this->_request->getPost('chektsl');
        if ($openldap == 'openldap')
            $tipoServidor = 'openldap';
        elseif ($ldap == 'ldap')
            $tipoServidor = 'ldap';
        $servidor = new DatServidor();
        $tiposerv = $this->_request->getPost('tiposervidor');
        $servidor->denominacion = $this->_request->getPost('denominacion');
        $servidor->descripcion = $this->_request->getPost('descripcion');
        $servidor->ip = $this->_request->getPost('ip');
        $servidor->tiposervidor = $this->_request->getPost('tiposervidor');
        if (DatServidorModel::verificadatosservidor($servidor->denominacion))
            throw new ZendExt_Exception('SEG050');
        if (DatServidorModel::verificarExisteIpConTipo($servidor->tiposervidor, $servidor->ip))
            throw new ZendExt_Exception('SEG055');
        $servidor->save();
        if ($tiposerv == 'autenticaci&oacute;n') {
            $servidorauth = new DatSerautenticacion();
            $servidorauth->idservidor = $servidor->idservidor;
            $servidorauth->basedn = $cadenaconexion;
            $servidorauth->tservidor = $tipoServidor;
            $servidorauth->usuario = $user;
            if ($passw) {
                $RSA = new ZendExt_RSA_Facade();
                $servidorauth->clave = $RSA->encrypt($passw);
            }
            if ($chbssl)
                $servidorauth->ssl = 1;
            if ($chbtsl)
                $servidorauth->tsl = 1;
            $servidorauth->puerto = $puerto;
            $model = new DatSerautenticacionModel();
            $model->insertar($servidorauth);
            echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgAddSer}";
        }
        else {
            $servidorbd = new DatServidorbd();
            $servidorbd->idservidor = $servidor->idservidor;
            $model = new DatServidorModel();
            $model->insertar($servidorbd);
    echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgAddSerBD}";    
        }
    }
//Funcionalidad que permite modificar un servidor
    function modificarservAction() {
        $servidor = new DatServidor();
        $model = new DatServidorModel();
        $idservidor = $this->_request->getPost('idservidor');
        $descripcion = $this->_request->getPost('descripcion');
        $denominacion = $this->_request->getPost('denominacion');
        $tpservaux = $this->_request->getPost('tiposervidor');
        $passw = $this->_request->getPost('passw');
        $user = $this->_request->getPost('user');
        $puerto = $this->_request->getPost('puerto');
        $chbssl = $this->_request->getPost('chekssl');
        $chbtsl = $this->_request->getPost('chektsl');
        $ip = $this->_request->getPost('ip');
        $openldap = $this->_request->getPost('openldap');
        $ldap = $this->_request->getPost('ldap');
        $tpserv = 'autenticaci&oacute;n';
        $tpservbd = 'bd';
        $cadenaconexion = $this->_request->getPost('baseDN');
        $server_mod = Doctrine::getTable('DatServidor')->find($idservidor);
        if ($server_mod->denominacion != $denominacion) {
            if (DatServidorModel::verificadatosservidor($denominacion))
                throw new ZendExt_Exception('SEG050');
        }
        if (DatServidorModel::verificarExisteIpConTipo($tpservaux, $ip) && $server_mod->ip != $ip)
            throw new ZendExt_Exception('SEG055');
        
        if ($openldap == 'openldap')
            $tipoServidor = 'openldap';
        elseif ($ldap == 'ldap')
            $tipoServidor = 'ldap';
        if ($tpservaux == 'bd') {
            if ($server_mod->denominacion == $denominacion &&
                    $server_mod->descripcion == $descripcion && $server_mod->tiposervidor == $tpservaux &&
                    $server_mod->ip == $ip)
                throw new ZendExt_Exception('SEG053');
        }else {

            $serautenticacion = Doctrine::getTable('DatSerautenticacion')->find($idservidor);
            $sslaux = 0;
            $tslaux = 0;
            if ($chbssl)
                $sslaux = 1;
            if ($chbtsl)
                $tslaux = 1;

            if ($server_mod->denominacion == $denominacion &&
                    $server_mod->descripcion == $descripcion && $server_mod->tiposervidor == $tpservaux &&
                    $server_mod->ip == $ip && $serautenticacion->tservidor == $tipoServidor &&
                    $serautenticacion->usuario == $user && $serautenticacion->basedn == $cadenaconexion && $serautenticacion->ssl == $sslaux && $serautenticacion->tsl == $tslaux &&
                    $serautenticacion->puerto == $puerto && !$passw)
                throw new ZendExt_Exception('SEG053');
        }
        $tiposervidor = DatServidor::gettiposerv($idservidor);
        $tiposerv = $tiposervidor[0]->tiposervidor;
        if (($tpservaux != $tiposerv) && ($tpservaux == 'autenticaci&oacute;n')) {
            $cantservidoresbdsist = DatSistemaDatServidores::obtenercantservsistema($idservidor);
            if ($cantservidoresbdsist != 0)
                throw new ZendExt_Exception('SEG010');
            if (($cantservidoresbdsist == 0)) {
                $serbd = new DatServidorbd();
                $serbd = Doctrine::getTable('DatServidorbd')->find($idservidor);
                $serbdm = new DatServidorbdModel();
                $serbdm->eliminarservbd($serbd);
                $serauth = new DatSerautenticacion();
                $serauthm = new DatSerautenticacionModel();
                $serauth->idservidor = $idservidor;
                $serauth->tservidor = $tipoServidor;
                $serauth->basedn = $cadenaconexion;
                $serauth->usuario = $user;
                if ($passw) {
                    $RSA = new ZendExt_RSA_Facade();
                    $serauth->clave = $RSA->encrypt($passw);
                }
                if ($chbssl)
                    $servidorauth->ssl = 1;
                if ($chbtsl)
                    $servidorauth->tsl = 1;
                $serauth->puerto = $puerto;
                $servidor = Doctrine::getTable('DatServidor')->find($idservidor);
                $servidor->descripcion = $descripcion;
                $servidor->denominacion = $denominacion;
                $servidor->tiposervidor = $tpservaux;
                $servidor->ip = $ip;
                $model->modificarservidor($servidor, $serauth);
                $serauthm->modificarservidor($serauth);
                echo"{'codMsg':1,perfil.etiquetas.lbMsgModSer}";
            }
        }
        elseif (($tpservaux != $tiposerv) && ($tpservaux == 'bd')) {
            $cantservidoresauthusers = SegUsuarioDatSerautenticacion::obtenercantservuser($idservidor);
            if ($cantservidoresauthusers > 0)
                throw new ZendExt_Exception('SEG010');
            if ($cantservidoresauthusers == 0) {
                $serautenticacion = new DatSerautenticacion();
                $serautenticacion = Doctrine::getTable('DatSerautenticacion')->find($idservidor);
                $serautenticacionmodel = new DatSerautenticacionModel();
                $serautenticacionmodel->eliminarservauth($serautenticacion);
                $serbd = new DatServidorbd();
                $serbdm = new DatServidorbdModel();
                $serbd->idservidor = $idservidor;
                $servidor = Doctrine::getTable('DatServidor')->find($idservidor);
                $servidor->descripcion = $descripcion;
                $servidor->denominacion = $denominacion;
                $servidor->tiposervidor = $tpservaux;
                $servidor->ip = $ip;
                $model->modificarservidor($servidor);
                $serbdm->modificarservidor($serbd);
               echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgModSerBD}";
            }
        } else if (($tpservaux == $tiposerv) && ($tpservaux == 'autenticaci&oacute;n')) {
            $cantservidoresbdsist = DatSistemaDatServidores::obtenercantservsistema($idservidor);
            if ($cantservidoresbdsist != 0)
                throw new ZendExt_Exception('SEG010');
            if (($cantservidoresbdsist == 0)) {
                $serbd = new DatServidorbd();
                $serauth = new DatSerautenticacion();
                $serauthm = new DatSerautenticacionModel();
                $serauth->idservidor = $idservidor;
                $servidor = Doctrine::getTable('DatServidor')->find($idservidor);
                $serauth = Doctrine::getTable('DatSerautenticacion')->find($idservidor);
                $serauth->tservidor = $tipoServidor;
                $serauth->basedn = $cadenaconexion;
                $serauth->usuario = $user;
                if ($passw) {
                    $RSA = new ZendExt_RSA_Facade();
                    $serauth->clave = $RSA->encrypt($passw);
                }
                if ($chbssl)
                    $servidorauth->ssl = 1;
                if ($chbtsl)
                    $servidorauth->tsl = 1;
                $serauth->puerto = $puerto;
                $servidor->descripcion = $descripcion;
                $servidor->denominacion = $denominacion;
                $servidor->tiposervidor = $tpservaux;
                $servidor->ip = $ip;
                $model->modificarservidor($servidor, $serauth);
                $serauthm->modificarservidor($serauth);
                echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgModSer}";
            }
        }
        elseif (($tpservaux == $tiposerv) && ($tpservaux == 'bd')) {

            $cantservidoresauthusers = SegUsuarioDatSerautenticacion::obtenercantservuser($idservidor);
            if ($cantservidoresauthusers > 0)
                throw new ZendExt_Exception('SEG010');
            if ($cantservidoresauthusers == 0) {
                $serautenticacion = new DatSerautenticacion();
                $serbd = new DatServidorbd();
                $serbdm = new DatServidorbdModel();
                $servidor = Doctrine::getTable('DatServidor')->find($idservidor);
                $serbd = Doctrine::getTable('DatServidorbd')->find($idservidor);
                $serbd->idservidor = $idservidor;
                $servidor->descripcion = $descripcion;
                $servidor->denominacion = $denominacion;
                $servidor->tiposervidor = $tpservaux;
                $servidor->ip = $ip;
                $result = $model->modificarservidorsabd($servidor);
                $serbdm->modificarservidor($serbd);
                echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgModSerBD}";
            }
        }
    }

    //Funcionalidad que permite eliminar los servidores	
    function comprobarservidorAction() {
        $model=new DatServidorModel();
        $arrayServ = json_decode(stripcslashes($this->_request->getPost('arrayServ')));
        $arrayElim = array();
        foreach ($arrayServ as $servidor) {
            $cantserv=$model->obtenercantidad($servidor);
            $cantservsist=$model->obtenercantservsistema($servidor);
            if ($cantserv == 0 && $cantservsist == 0) {
                $arrayElim[] = $servidor;
            }
        }
        if (count($arrayServ) == count($arrayElim)) {
            $model->elimirarServidores($arrayServ);            
           echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgDeSerMod}";
        } elseif (count($arrayElim) > 0) {
            $model->elimirarServidores($arrayElim);            
            echo"{'codMsg':3,perfil.etiquetas.lbMsgDelSerErr}";
        }
        else
            throw new ZendExt_Exception('SEG011');
    }
//Funcionalidad que permite cargar los servidores en la interfaz visual
    function cargarservidoresAction() {
        $start = $this->_request->getPost("start");
        $limit = $this->_request->getPost("limit");
        $datosserv = DatServidor::cargarservidores($limit, $start)->toArray(true);
        $datos = array();
        $RSA = new ZendExt_RSA_Facade();
        foreach ($datosserv as $key => $valor) {
            $datos[$key]['ip'] = $valor['ip'];
            $datos[$key]['id'] = $valor['id'];
            $datos[$key]['text'] = $valor['text'];
            $datos[$key]['descripcion'] = $valor['descripcion'];
            $datos[$key]['tiposervidor'] = $valor['tiposervidor'];
            if (isset($valor['DatSerautenticacion']) && count($valor['DatSerautenticacion']) > 0) {
                $datos[$key]['type'] = $valor['DatSerautenticacion']['tservidor'];
                $datos[$key]['cadconexion'] = $valor['DatSerautenticacion']['basedn'];
                $datos[$key]['puerto'] = $valor['DatSerautenticacion']['puerto'];
                $datos[$key]['ssl'] = ($valor['DatSerautenticacion']['ssl']) ? true : false;
                $datos[$key]['tsl'] = ($valor['DatSerautenticacion']['tsl']) ? true : false;
                $datos[$key]['user'] = $valor['DatSerautenticacion']['usuario'];
            }
        }
        $canfilas = DatServidor::obtenercantserv();
        $result = array('cantidad_filas' => $canfilas, 'datos' => $datos);
        echo json_encode($result);
        return;
    }
//Funcionalidad que permite probar que exista conexión al LDAP
    function probarConexionAction() {
        $model=new DatServidorModel();
        $openldap = $this->_request->getPost('openldap');
        $ldap = $this->_request->getPost('ldap');
        $baseDN = $this->_request->getPost('baseDN');
        $passw = $this->_request->getPost('passw');
        $userDN = $this->_request->getPost('user');
        $puerto = $this->_request->getPost('puerto');
        $chbssl = $this->_request->getPost('chekssl');
        $denominacion = $this->_request->getPost('denominacion');
        $descripcion = $this->_request->getPost('descripcion');
        $host = $this->_request->getPost('ip');
        $password = $this->_request->getPost('password');
        $usuario = $this->_request->getPost('usuario');
        $arrayOptions = array();
        $acountDomainName = ($baseDN) ? DatServidorModel::createAccountDomainName($baseDN) : $this->createAccountDomainName($userDN);
        $arrayOptions['host'] = $host;
        $arrayOptions['port'] = $puerto;

        if ($ldap) {
            $arrayOptions['accountDomainName'] = $acountDomainName;
        } else {
            $arrayOptions['username'] = $userDN;
            $arrayOptions['password'] = $passw;
        }

        $arrayOptions['accountDomainNameShort'] = DatServidorModel::accountDomainNameShort($acountDomainName);
        $arrayOptions['baseDn'] = $baseDN;

        if ($openldap == 'true')
            $arrayOptions['bindRequiresDn'] = true;
        else
            $arrayOptions['bindRequiresDn'] = false;

        if (self::AD_Autentication($arrayOptions, $usuario, $password))
           echo"{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgConesatis}";
        else
            throw new ZendExt_Exception('SEGSERV051');
    }
    function AD_Autentication($arrayOptions, $user, $password) {
        $os = PHP_OS;
        $ip = $arrayOptions['host'];
        if ($os == "Linux")
            $ping = exec("ping -c1 -W2 $ip", $input, $result);
        else
            $ping = exec("ping -n 1 -w 1 $ip", $input, $result);
        if ($result == 0) {
            $options = array('server' => $arrayOptions);
            $auth = Zend_Auth::getInstance();
            $adapter = new Zend_Auth_Adapter_Ldap($options, $user, $password);
            $result = $auth->authenticate($adapter);
            $array = $result->getMessages();
            if (!$array[0])
                return true;
            return false;
        }
        return false;
    }

   

}

?>
