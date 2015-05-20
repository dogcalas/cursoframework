<?php

/*
 * Componente para gestinar permisos a roles de bases de datos.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @author Yoel Hernández Mendoza
 * @version 3.0-0
 */

class GestrolesbdController extends ZendExt_Controller_Secure {

    protected $lista = array(
        'Tablas' => 'TABLE',
        'Vistas' => 'VIEW',
        'Sequencias' => 'SECUENCE',
        'Esquemas' => 'SCHEMA',
        'Base de datos' => 'DATABASE',
        'Funciones' => 'FUNCTION'
    );
    protected $criterios = array(
        'Tablas' => 'Tables',
        'Vistas' => 'Views',
        'Secuencias' => 'Sequences',
        'Esquemas' => 'Schemas',
        'Base de datos' => 'Databases',
        'Funciones' => 'Functions'
    );

    function init() {
        parent::init();
    }

    function gestrolesbdAction() {
        $this->render();
    }

    
    function cargarservidoresAction() {
        $idnodo = $this->_request->getPost('node');
        $accion = $this->_request->getPost('accion');
        $idsistema = $this->_request->getPost('idsistema');
        if (!$idnodo) {
            $servidores = DatServidor::cargarservidores(0, 0);
            if ($servidores->getData() != NULL) {
                foreach ($servidores as $valores => $valor) {
                    $servidoresArr[$valores]['id'] = $valor->id;
                    $servidoresArr[$valores]['idservidor'] = $valor->id;
                    $servidoresArr[$valores]['text'] = $valor->text;
                    $servidoresArr[$valores]['type'] = 'servidores';
                    $servidoresArr[$valores]['icon'] = '../../views/images/server-white.PNG';
                }
                echo json_encode($servidoresArr);
                return;
            } else {
                $serv = $servidores->toArray();
                echo json_encode($serv);
                return;
            }
        } elseif ($accion == 'cargargestores') {
            $idservidor = $this->_request->getPost('idservidor');
            $gestores = DatGestor::cargargestores($idservidor, 0, 0);
            if ($gestores->getData() != NULL) {
                foreach ($gestores as $valores => $valor) {
                    $gestoresArr[$valores]['id'] = $valor->idgestor . '-' . $idnodo;
                    $gestoresArr[$valores]['text'] = $valor->gestor . ':' . $valor->puerto;
                    $gestoresArr[$valores]['idgestor'] = $valor->idgestor;
                    $gestoresArr[$valores]['idservidor'] = $idservidor;
                    $gestoresArr[$valores]['gestor'] = $valor->gestor;
                    $gestoresArr[$valores]['puerto'] = $valor->puerto;
                    if ($valor->gestor == 'pgsql')
                        $gestoresArr[$valores]['icon'] = '../../views/images/pgadmin.png';
                    else
                        $gestoresArr[$valores]['icon'] = '../../views/images/oracle.png';
                    $gestoresArr[$valores]['ipgestorbd'] = $valor->DatGestorDatServidorbd[0]->DatServidorbd->DatServidor->ip;
                }
                echo json_encode($gestoresArr);
                return;
            }
            else {
                $gest = $gestores->toArray();
                echo json_encode($gest);
                return;
            }
        } else {
            $user = $this->_request->getPost('user');
            $pass = $this->_request->getPost('passw');
            $gestor = $this->_request->getPost('gestor');
            $ipgestorbd = $this->_request->getPost('ipgestorbd');
            $idservidor = $this->_request->getPost('idservidor');
            $idgestor = $this->_request->getPost('idgestor');
            $puerto = $this->_request->getPost('puerto');
            $getDatabases = 'get' . ucfirst($gestor) . 'Databases';
            $arrayBD = $this->$getDatabases($gestor, $user, $pass, $ipgestorbd, $idgestor, $idservidor, $idsistema, $idnodo, $puerto);
            echo json_encode($arrayBD);
        }
    }

    
    private function getPgsqlDatabases($gestor, $user, $pass, $ipgestorbd, $idgestor, $idservidor, $idsistema, $idnodo, $puerto) {
        $bdArr = array();
        $RSA = new ZendExt_RSA_Facade();
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $this->VerifyConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/template1");
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/template1", 'pg_catalog');
        $db = PgDatabase::getPgsqlDatabases($conn);
        $dm->setCurrentConnection($nameCurrentConn);
        if ($db->getData() != null) {
            foreach ($db as $key => $valor) {
                $bdArr[$key]['id'] = $valor->datname . '-' . $idnodo;
                $bdArr[$key]['text'] = $valor->datname;
                $bdArr[$key]['namebd'] = $valor->datname;
                $bdArr[$key]['gestor'] = $gestor;
                $bdArr[$key]['ipgestorbd'] = $ipgestorbd;
                $bdArr[$key]['puerto'] = $puerto;
                $bdArr[$key]['user'] = $user;
                $bdArr[$key]['passw'] = $RSA->encrypt($pass);
                $bdArr[$key]['idgestor'] = $idgestor;
                $bdArr[$key]['idservidor'] = $idservidor;
                $bdArr[$key]['icon'] = '../../views/images/server.PNG';
                $bdArr[$key]['type'] = $gestor;
                $bdArr[$key]['leaf'] = true;
            }
        }
        return $bdArr;
    }

    //funcion de oracle para cargar bases de datos
    private function getOracleDatabases($gestor, $user, $pass, $ipgestorbd, $idgestor, $idservidor, $idsistema, $idnodo, $puerto) {

        $rsa = new ZendExt_RSA_Facade();
        //$SID = "orac";
        $SID = DatGestorDatServidorbd::getSid($idgestor, $idservidor);
        $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
        $bdArr = array();
        $key = 0;
        @ $conn = oci_connect("$user", "$pass", "$cadenaConex");

        if ($conn == true) {
            $query = 'select name from sys.v_$database';
            $stid = oci_parse($conn, $query);
            oci_execute($stid);
            while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
                $bdArr[$key]['id'] = $row['NAME'] . '-' . $idnodo;
                $bdArr[$key]['text'] = $row['NAME'];
                $bdArr[$key]['namebd'] = $row['NAME'];
                $bdArr[$key]['gestor'] = $gestor;
                $bdArr[$key]['ipgestorbd'] = $ipgestorbd;
                $bdArr[$key]['puerto'] = $puerto;
                $bdArr[$key]['user'] = $user;
                $bdArr[$key]['passw'] = $rsa->encrypt($pass);
                $bdArr[$key]['idgestor'] = $idgestor;
                $bdArr[$key]['idservidor'] = $idservidor;
                $bdArr[$key]['icon'] = '../../views/images/server.PNG';
                $bdArr[$key]['type'] = 'oracle';
                $bdArr[$key]['leaf'] = true;
                $key++;
            }
            oci_close($conn);
            return $bdArr;
        } else {

            echo"{'codMsg':3,'mensaje': 'Error de conexión.'}";
            die;
        }
    }

    function loadInterfaceAction() {
        $opcion = $this->_request->getPost('tipo');
        $this->render($opcion);
    }

    //Usado
    function cargarRolesBDAction() {
        $RSA = new ZendExt_RSA_Facade();
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $bd = $this->_request->getPost('bd');
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $nombreRol = $this->_request->getPost('nombreRol');
        $idserv = DatServidor::getidServidorPorIP($ipgestorbd);
        $idserv = $idserv[0]['id'];
        $idgestor = DatGestor::getIdPorDenominacionPuerto($gestor, $puerto);
        $idgestor = $idgestor[0]->idgestor;
        $rolConexion = SegRolesbd::loadRoleBD($idserv, $idgestor);
        $rolConexion = $rolConexion[0]->nombrerol;
        $funcion = $gestor . 'CargarRolesBD';        
        $result = $this->$funcion($gestor, $puerto, $user, $pass, $ipgestorbd, $bd, $limit, $start, $nombreRol, $rolConexion);        
        echo json_encode($result);
    }

    //Usado
    function pgsqlCargarRolesBD($gestor, $puerto, $user, $pass, $ipgestorbd, $bd, $limit, $start, $nombreRol, $rolConexion) {
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$bd", 'pg_catalog');
        $nombreRol = $this->TratarCriterioBusqueda($nombreRol);
        if ($nombreRol) {
            $result = PgAuthid::getRolBDbyName($conn, $nombreRol, $limit, $start)->toArray();
            $roles = $this->devolverDatosRol($result);
            $cant = PgAuthid::cantRolBDbyName($conn, $nombreRol);
        } else {
            $result = PgAuthid::getRolBD($conn, $limit, $start)->toArray();
            $roles = $this->devolverDatosRol($result);
            $cant = PgAuthid::cantRolBD($conn);
        }
        $dm->setCurrentConnection($nameCurrentConn);
        $roles = $this->Modificaciones_ArregloRoles($roles, $ipgestorbd, $gestor, $puerto, $rolConexion);
        return array('cantidad_filas' => $cant, 'datos' => $roles);
    }

    private function Modificaciones_ArregloRoles($roles, $ipgestorbd, $gestor, $puerto, $rolConexion) {
        $result = array();
        foreach ($roles as $rol) {
            if (!$this->IsRolSistemaOrUserSistema($rol['rolname'], $ipgestorbd, $gestor, $puerto)) {
                if ($rol['rolname'] == $rolConexion)
                    $rol['estado'] = 1;
                else
                    $rol['estado'] = 0;
                if ($rolConexion != "") {
                    $rol['existconex'] = 1;
                } else {
                    $rol['existconex'] = 0;
                }
                $result[] = $rol;
            }
        }
        return $result;
    }

    private function IsRolSistemaOrUserSistema($rolname, $ipgestorbd, $gestor, $puerto) {
        $arrayNombres = explode('_', $rolname);
        if (count($arrayNombres) == 3) {
            if ($arrayNombres[0] == 'rol') {
                if ($arrayNombres[2] == 'acaxia') {
                    $segroles = Doctrine::getTable('SegRol')->
                            findByDql('denominacion=?', array($arrayNombres[1]));

                    if (count($segroles)) {
                        $idrol = $segroles[0]->idrol;
                        $cantidadRolServidorGestor = SegRolDatServidorDatGestor::CantidadRolServidorGestor($idrol, $ipgestorbd, $gestor, $puerto);
                        if ($cantidadRolServidorGestor != 0) {
                            return true;
                        }
                    }
                }
            } else {
                if ($arrayNombres[0] == 'usuario') {
                    if ($arrayNombres[2] == 'acaxia') {
                        $segusuario = Doctrine::getTable('SegUsuario')->
                                findByDql('nombreusuario=?', array($arrayNombres[1]));
                        if (count($segusuario)) {
                            $idusuario = $segusuario[0]->idusuario;
                            $cantidadUsuarioServidorGestor = SegUsuarioDatServidorDatGestor::CantidadUsuarioServidorGestor($idusuario, $ipgestorbd, $gestor, $puerto);
                            if ($cantidadUsuarioServidorGestor != 0) {
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    // funcion util para oracle convertir formato de fecha
    function conv_fech($date) {
        $fecha3 = substr($date, 0, 10);
        $dia = substr($fecha3, 0, 2);
        $mes = substr($fecha3, 3, 2);
        $ano = substr($fecha3, 6, 4);
        $fecha = $mes . '/' . $dia . '/' . $ano;
        return $fecha;
    }

    // funcion de oracle para cargar usuarios
    function oracleCargarUsuariosBDAction() {
        $key = 0;
        $usuarios = array();
        $arr = array();
        $RSA = new ZendExt_RSA_Facade();
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $limit = $start + $limit - 1;
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');        
        $SID = DatGestorDatServidorbd::getSid($idgestor, $idservidor);
        $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
        $conn = oci_connect("$user", "$pass", "$cadenaConex");

        $query = "select username,USER_ID,ACCOUNT_STATUS,CREATED,EXPIRY_DATE,LOCK_DATE,PASSWORD from
            (
            select rownum parte,
            username,USER_ID,ACCOUNT_STATUS,CREATED,EXPIRY_DATE,LOCK_DATE,PASSWORD
            from (
            select username,USER_ID,ACCOUNT_STATUS,CREATED,EXPIRY_DATE,LOCK_DATE,PASSWORD
            from dba_users order by username
            )
            )
            where parte between $start and $limit";


        $stid = oci_parse($conn, $query);
        oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
            $usuarios[$key]['user'] = $row['USERNAME'];
            $usuarios[$key]['id'] = $row['USER_ID'];
            $usuarios[$key]['estado'] = $row['ACCOUNT_STATUS'];
            $usuarios[$key]['creado'] = $row['CREATED'];
            $usuarios[$key]['expira'] = $row['EXPIRY_DATE'];
            $usuarios[$key]['bloqueado'] = $row['LOCK_DATE'];
            $usuarios[$key]['password'] = $row['PASSWORD'];
            $usuarios[$key]['fecha'] = $this->conv_fech($row['EXPIRY_DATE']);

            if ($row['ACCOUNT_STATUS'] == "OPEN") {
                $usuarios[$key]['checkactivacion'] = true;
            } else {
                $usuarios[$key]['checkactivacion'] = false;
            }

            $key++;
        }

        $consult_cant = "select count (*) as cantidad from dba_users";
        $stid1 = oci_parse($conn, $consult_cant);
        oci_execute($stid1);
        $row = oci_fetch_array($stid1, OCI_ASSOC);
        $cantidad = $row['CANTIDAD'];

        $arr['cantidad_filas'] = $cantidad;
        $arr['datos'] = $usuarios;

        oci_close($conn);
        echo json_encode($arr);
    }

    // funcion de oracle para cargar roles
    function oracleCargarRolesBDAction() {
        $RSA = new ZendExt_RSA_Facade();
        $key = 0;
        $roles = array();
        $arr = array();
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $limit = $start + $limit - 1;
        $SID = DatGestorDatServidorbd::getSid($idgestor, $idservidor);
        $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
        $conn = oci_connect("$user", "$pass", "$cadenaConex");
        $query = "select ROLE,PASSWORD_REQUIRED from
            (
            select rownum parte,
            ROLE,PASSWORD_REQUIRED
            from (
            select ROLE,PASSWORD_REQUIRED
            from dba_roles
            )
            )
            where parte between $start and $limit";
        $stid = oci_parse($conn, $query);
        oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
            $roles[$key]['rol'] = $row['ROLE'];
            $roles[$key]['pidepass'] = $row['PASSWORD_REQUIRED'];
            $pas = $row['PASSWORD_REQUIRED'];
            if ($pas == "GLOBAL") {
                $radiobutton7 = on;
                $radiobutton6 = off;
                $radiobutton5 = off;
                $radiobutton4 = off;
            } else if ($pas == "EXTERNAL") {
                $radiobutton6 = on;
                $radiobutton7 = off;
                $radiobutton4 = off;
                $radiobutton5 = off;
            } else if ($pas == "YES") {
                $radiobutton5 = on;
                $radiobutton7 = off;
                $radiobutton6 = off;
                $radiobutton4 = off;
            } else if ($pas == "NO") {
                $radiobutton4 = on;
                $radiobutton7 = off;
                $radiobutton6 = off;
                $radiobutton5 = off;
            }
            $roles[$key]['radiobutton7'] = $radiobutton7;
            $roles[$key]['radiobutton6'] = $radiobutton6;
            $roles[$key]['radiobutton5'] = $radiobutton5;
            $roles[$key]['radiobutton4'] = $radiobutton4;

            $key++;
        }
        $consult_cant = "select count (*) as cantidad from dba_roles";
        $stid1 = oci_parse($conn, $consult_cant);
        oci_execute($stid1);
        $row = oci_fetch_array($stid1, OCI_ASSOC);
        $cantidad = $row['CANTIDAD'];
        $arr['cantidad_filas'] = $cantidad;
        $arr['datos'] = $roles;
        oci_close($conn);
        echo json_encode($arr);
    }

    // funcion para restar fechas util para oracle
    function dateDiff($fecha1, $fecha2) {
        //Calcula los dias entre dos Fechas
        //Descomponemos la fechas en dia, mes y año.
        list($dia1, $mes1, $ano1) = split("[/.-]", $fecha1);
        list($dia2, $mes2, $ano2) = split("[/.-]", $fecha2);
        //calculo timestam de las dos fechas
        $timestamp1 = mktime(0, 0, 0, $mes1, $dia1, $ano1);
        $timestamp2 = mktime(0, 0, 0, $mes2, $dia2, $ano2);
        //resto a una fecha la otra. Esto entrega como resultado un valor en segundos
        $segundos_diferencia = $timestamp1 - $timestamp2;
        //convierto segundos en días
        $dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
        //obtengo el valor absoulto de los días (quito el posible signo negativo)
        $dias_diferencia = abs($dias_diferencia);
        //quito los decimales a los días de diferencia
        $dias_diferencia = floor($dias_diferencia);
        return $dias_diferencia;
    }

    // funcion de oracle para insertar un usuario
    function oracleInsertarUsuarioAction() {
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $user = $this->_request->getPost('user');
        $RSA = new ZendExt_RSA_Facade();
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $SID = DatGestorDatServidorbd::getSid($idgestor, $idservidor);
        $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
        $conn = oci_connect("$user", "$pass", "$cadenaConex");
        $cadena = $this->_request->getPost('denominacion1');
        $user_insert = strtoupper($cadena);
        $pass_insert = $this->_request->getPost('newpass1');
        $checkactivacion = $this->_request->getPost('checkactivacion1');
        $fechaexpira = date('d/m/Y', strtotime($this->_request->getPost('fechaF')));
        $fechaactual = date('d/m/Y');
        $dias = $this->dateDiff("$fechaexpira", "$fechaactual");
        $fechaexp = $this->dateDiff("$fechaexpira", "1,1,1950");
        $fechaact = $this->dateDiff("$fechaactual", "1,1,1950");
        if ($fechaexp > $fechaact) {
            $consulta = "select * from dba_users where username='$user_insert'";
            $stid = oci_parse($conn, $consulta);
            oci_execute($stid);
            $row = oci_fetch_array($stid, OCI_ASSOC);
            $consulta1 = "select * from dba_roles where ROLE='$user_insert'";
            $stid1 = oci_parse($conn, $consulta1);
            oci_execute($stid1);
            $row1 = oci_fetch_array($stid1, OCI_ASSOC);
            if (($row == Null) && ($row1 == Null)) {
                if ($checkactivacion == off) {
                    $query = "CREATE USER $user_insert IDENTIFIED BY $pass_insert ACCOUNT LOCK";
                    $stid = oci_parse($conn, $query);
                    oci_execute($stid);
                } else {
                    $query = "CREATE USER $user_insert IDENTIFIED BY $pass_insert";
                    $stid = oci_parse($conn, $query);
                    oci_execute($stid);
                }
                $perfil = "CREATE PROFILE $user_insert LIMIT
                      PASSWORD_LIFE_TIME $dias";
                $stid2 = oci_parse($conn, $perfil);
                oci_execute($stid2);
                $asig = "ALTER USER $user_insert  PROFILE $user_insert";
                $stid3 = oci_parse($conn, $asig);
                oci_execute($stid3);
                $objRolesbd = new SegRolesbd();
                $objRolesbd->idservidor = $idservidor; // tiene que mandarlo de la interfaz
                $objRolesbd->idgestor = $idgestor; // tiene que mandarlo de la interfaz
                $objRolesbd->nombrerol = $user_insert; //usuario a insertar
                $objRolesbd->passw = $RSA->encrypt($pass_insert);
                $objRolesbd->save();
                $this->showMessage('Usuario insertado satisfactoriamente.');
            }
            else
                $this->showMessage('El nombre de usuario ya existe en otro Rol o Usuario.');
        }
        else
            $this->showMessage('La fecha de expiracion debe ser mayor que la fecha actual.');
    }

    //funcion de oracle para eliminar un usuario
    function oracleEliminarUsuarioAction() {
        $RSA = new ZendExt_RSA_Facade();
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $SID = DatGestorDatServidorbd::getSid($idgestor, $idservidor);
        $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
        $conn = oci_connect("$user", "$pass", "$cadenaConex");
        $cadena = $this->_request->getPost('userelim');
        $user_eliminar = strtoupper($cadena);
        $query = "select count(PROFILE) from dba_profiles where PROFILE='$user_eliminar'";
        $stid = oci_parse($conn, $query);
        oci_execute($stid);
        $cant = oci_fetch_array($stid, OCI_ASSOC);

        if ($cant != null) {
            $perfil = "DROP PROFILE $user_eliminar CASCADE";
            $stid2 = oci_parse($conn, $perfil);
            oci_execute($stid2);
        }

        $query = "DROP USER $user_eliminar CASCADE";
        $stid = oci_parse($conn, $query);
        oci_execute($stid);



        $exist = SegRolesbd::exist($user_eliminar, $idservidor, $idgestor);
        if (!$this->verifySistems($exist[0]->idrolesbd)) {
            SegRolesbd::deleteRol($exist[0]->idrolesbd);
        }

        $this->showMessage('Usuario eliminado satisfactoriamente.');

        oci_close($conn);
    }

    //funcion de oracle para modificar un usuario
    function oracleModificarUsuarioAction() {
        $RSA = new ZendExt_RSA_Facade();
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $SID = DatGestorDatServidorbd::getSid($idgestor, $idservidor);
        $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
        $conn = oci_connect("$user", "$pass", "$cadenaConex");
        $cadena = $this->_request->getPost('denominacion');
        $user_mod = strtoupper($cadena);
        $newpass = $this->_request->getPost('newpass');
        $checkactivacion = $this->_request->getPost('checkactivacion');
        $cambiapass = $this->_request->getPost('cambiar');
        $fechaexpira = date('d/m/Y', strtotime($this->_request->getPost('fechaF')));
        $fechaactual = date('d/m/Y');
        $dias = $this->dateDiff("$fechaexpira", "$fechaactual");
        $fechaexp = $this->dateDiff("$fechaexpira", "1,1,1950");
        $fechaact = $this->dateDiff("$fechaactual", "1,1,1950");
        $query = "select count(PROFILE) as cant from dba_profiles where PROFILE='$user_mod'";
        $stid = oci_parse($conn, $query);
        oci_execute($stid);
        $cant = oci_fetch_array($stid, OCI_ASSOC);
        if ($fechaexp > $fechaact) {
            if ($cant['CANT'] > 0) {
                $perfil = "ALTER PROFILE $user_mod LIMIT PASSWORD_LIFE_TIME $dias";
                $stid2 = oci_parse($conn, $perfil);
                oci_execute($stid2);
            } else {
                $perfil = "CREATE PROFILE $user_mod LIMIT PASSWORD_LIFE_TIME $dias";
                $stid2 = oci_parse($conn, $perfil);
                oci_execute($stid2);

                $asig = "ALTER USER $user_mod PROFILE $user_mod";
                $stid3 = oci_parse($conn, $asig);
                oci_execute($stid3);
            }
            if ($cambiapass == on) {
                $query = "ALTER USER $user_mod IDENTIFIED BY $newpass";
                $stid = oci_parse($conn, $query);
                oci_execute($stid);
            }
            if ($checkactivacion == on) {
                $query = "ALTER USER $user_mod ACCOUNT UNLOCK";
                $stid = oci_parse($conn, $query);
                oci_execute($stid);
            } else {
                $query = "ALTER USER $user_mod ACCOUNT LOCK";
                $stid = oci_parse($conn, $query);
                oci_execute($stid);
            }
            $this->updateSegRolesbd($user_mod, $user_mod, $idservidor, $idgestor, $newpass); //ver datos enviados
            $this->showMessage('Usuario modificado satisfactoriamente.');
        }
        else
            $this->showMessage('La fecha de expiracion debe ser mayor que la fecha actual.');
        oci_close($conn);
    }

    //funcion de oracle para isertar un rol
    function oracleInsertarRolAction() {
        $RSA = new ZendExt_RSA_Facade();
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $SID = DatGestorDatServidorbd::getSid($idgestor, $idservidor);
        $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
        $conn = oci_connect("$user", "$pass", "$cadenaConex");
        $cadena = $this->_request->getPost('denominacionR1');
        $rol_insert = strtoupper($cadena);
        $pass_insert = $this->_request->getPost('newpassr');
        $radiobuton1 = $this->_request->getPost('radiobuton7');
        $radiobuton2 = $this->_request->getPost('radiobuton4');
        $radiobuton3 = $this->_request->getPost('radiobuton5');
        $radiobuton4 = $this->_request->getPost('radiobuton6');
        if ($radiobuton1 == on) {
            $consulta1 = "select username from dba_users where username='$rol_insert'";
            $stid1 = oci_parse($conn, $consulta1);
            oci_execute($stid1);
            $row1 = oci_fetch_array($stid1, OCI_ASSOC);
            $consulta = "select * from dba_roles where ROLE='$rol_insert'";
            $stid = oci_parse($conn, $consulta);
            oci_execute($stid);
            $row = oci_fetch_array($stid, OCI_ASSOC);
            if (($row == Null) && ($row1 == Null)) {
                $query = "CREATE ROLE $rol_insert NOT IDENTIFIED";
                $stid = oci_parse($conn, $query);
                oci_execute($stid);
                $this->showMessage('Rol insertado satisfactoriamente.');
            }
            else
                $this->showMessage('El nombre de rol ya existe en otro Rol o Usuario.');
        }
        else if ($radiobuton2 == on) {
            $consulta1 = "select username from dba_users where username='$rol_insert'";
            $stid1 = oci_parse($conn, $consulta1);
            oci_execute($stid1);
            $row1 = oci_fetch_array($stid1, OCI_ASSOC);
            $consulta = "select * from dba_roles where ROLE='$rol_insert'";
            $stid = oci_parse($conn, $consulta);
            oci_execute($stid);
            $row = oci_fetch_array($stid, OCI_ASSOC);
            if (($row == Null) && ($row1 == Null)) {
                $query = "CREATE ROLE $rol_insert IDENTIFIED BY $pass_insert";
                $stid = oci_parse($conn, $query);
                oci_execute($stid);
                $this->showMessage('Rol insertado satisfactoriamente.');
            }
            else
                $this->showMessage('El nombre de rol ya existe en otro Rol o Usuario.');
        }
        else if ($radiobuton3 == on) {
            $consulta1 = "select username from dba_users where username='$rol_insert'";
            $stid1 = oci_parse($conn, $consulta1);
            oci_execute($stid1);
            $row1 = oci_fetch_array($stid1, OCI_ASSOC);
            $consulta = "select * from dba_roles where ROLE='$rol_insert'";
            $stid = oci_parse($conn, $consulta);
            oci_execute($stid);
            $row = oci_fetch_array($stid, OCI_ASSOC);
            if (($row == Null) && ($row1 == Null)) {
                $query = "CREATE ROLE $rol_insert IDENTIFIED EXTERNALLY";
                $stid = oci_parse($conn, $query);
                oci_execute($stid);
                $this->showMessage('Rol insertado satisfactoriamente.');
            }
            else
                $this->showMessage('El nombre de rol ya existe en otro Rol o Usuario.');
        }
        else if ($radiobuton4 == on) {
            $consulta1 = "select username from dba_users where username='$rol_insert'";
            $stid1 = oci_parse($conn, $consulta1);
            oci_execute($stid1);
            $row1 = oci_fetch_array($stid1, OCI_ASSOC);
            $consulta = "select * from dba_roles where ROLE='$rol_insert'";
            $stid = oci_parse($conn, $consulta);
            oci_execute($stid);
            $row = oci_fetch_array($stid, OCI_ASSOC);
            if (($row == Null) && ($row1 == Null)) {
                $query = "CREATE ROLE $rol_insert IDENTIFIED GLOBALLY";
                $stid = oci_parse($conn, $query);
                oci_execute($stid);
                $this->showMessage('Rol insertado satisfactoriamente.');
            }
            else
                $this->showMessage('El nombre de rol ya existe en otro Rol o Usuario.');
        }
        oci_close($conn);
    }

    //funcion de oracle para eliminar un rol
    function oracleEliminarRolAction() {
        $RSA = new ZendExt_RSA_Facade();
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $SID = DatGestorDatServidorbd::getSid($idgestor, $idservidor);
        $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
        $conn = oci_connect("$user", "$pass", "$cadenaConex");
        $rol_eliminar = $this->_request->getPost('rol');
        $query = "DROP ROLE $rol_eliminar";
        $stid = oci_parse($conn, $query);
        oci_execute($stid);
        $this->showMessage('Rol eliminado satisfactoriamente.');
        oci_close($conn);
    }

    //funcion de oracle para modificar un rol
    function oracleModificarRolAction() {
        $RSA = new ZendExt_RSA_Facade();
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $SID = DatGestorDatServidorbd::getSid($idgestor, $idservidor);
        $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
        $conn = oci_connect("$user", "$pass", "$cadenaConex");
        $cadena = $this->_request->getPost('rol');
        $rol_insert = strtoupper($cadena);
        $pass_insert = $this->_request->getPost('newpassrM');
        $radiobuton1 = $this->_request->getPost('radiobuton7');
        $radiobuton2 = $this->_request->getPost('radiobuton4');
        $radiobuton3 = $this->_request->getPost('radiobuton5');
        $radiobuton4 = $this->_request->getPost('radiobuton6');
        if ($radiobuton1 == on) {
            $query = "ALTER ROLE $rol_insert NOT IDENTIFIED";
            $stid = oci_parse($conn, $query);
            oci_execute($stid);
            $this->showMessage('Rol modificado satisfactoriamente.');
        } else if ($radiobuton2 == on) {
            $query = "ALTER ROLE $rol_insert IDENTIFIED BY $pass_insert";
            $stid = oci_parse($conn, $query);
            oci_execute($stid);
            $this->showMessage('Rol modificado satisfactoriamente.');
        } else if ($radiobuton3 == on) {

            $query = "ALTER ROLE $rol_insert IDENTIFIED EXTERNALLY";
            $stid = oci_parse($conn, $query);
            oci_execute($stid);
            $this->showMessage('Rol modificado satisfactoriamente.');
        } else if ($radiobuton4 == on) {

            $query = "ALTER ROLE $rol_insert IDENTIFIED GLOBALLY";
            $stid = oci_parse($conn, $query);
            oci_execute($stid);
            $this->showMessage('Rol modificado satisfactoriamente.');
        }

        oci_close($conn);
    }

    //funcion de oracle para enviar a interfas el listado de roles que tiene un usuario
    function oracleMuestraRolesAsignadosUserAction() {
        $roles = array();
        $key = 0;
        $check = false;
        $RSA = new ZendExt_RSA_Facade();
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $SID = DatGestorDatServidorbd::getSid($idgestor, $idservidor);
        $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
        $conn = oci_connect("$user", "$pass", "$cadenaConex");
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $limit = $start + $limit - 1;
        $UsuarioRol = $this->_request->getPost('usuariorol');
        $query = "select ROLE from ( select rownum parte,ROLE
		            from (select ROLE from dba_roles where role <> '$UsuarioRol' and PASSWORD_REQUIRED <> 'GLOBAL'))
		            where parte between $start and $limit";
        $stid = oci_parse($conn, $query);
        oci_execute($stid);
        $query1 = "select * from dba_role_privs where GRANTEE='$UsuarioRol'";
        $stid1 = oci_parse($conn, $query1);
        while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
            $roles[$key]['rol'] = $row['ROLE'];
            $roles[$key]['grentee'] = false;
            $roles[$key]['opcion'] = false;
            $roles[$key]['pordefecto'] = false;
            oci_execute($stid1);
            while ($row1 = oci_fetch_array($stid1, OCI_ASSOC)) {
                if ($row['ROLE'] == $row1['GRANTED_ROLE']) {
                    $roles[$key]['grentee'] = true;
                    if ($row1['ADMIN_OPTION'] == "YES") {
                        $roles[$key]['opcion'] = true;
                    }
                    if ($row1['DEFAULT_ROLE'] == "YES") {
                        $roles[$key]['pordefecto'] = true;
                    }
                }
            }
            $key++;
        }
        $arr = array();
        $consult_cant = "select count (*) as cantidad from dba_roles where role <> '$UsuarioRol' and PASSWORD_REQUIRED <> 'GLOBAL'";
        $stid1 = oci_parse($conn, $consult_cant);
        oci_execute($stid1);
        $row = oci_fetch_array($stid1, OCI_ASSOC);
        $cantidad = $row['CANTIDAD'];
        $arr['cantidad_filas'] = $cantidad;
        $arr['datos'] = $roles;
        oci_close($conn);
        echo json_encode($arr);
    }

    //funcion de oracle para enviar a interfas el listado de roles que tiene un rol
    function oracleMuestraRolesAsignadosRolAction() {
        $roles = array();
        $key = 0;
        $check = false;
        $RSA = new ZendExt_RSA_Facade();
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $SID = DatGestorDatServidorbd::getSid($idgestor, $idservidor);
        $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
        $conn = oci_connect("$user", "$pass", "$cadenaConex");
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $limit = $start + $limit - 1;
        $UsuarioRol = $this->_request->getPost('usuariorol');
        $query = "select ROLE from (select rownum parte,ROLE
		            from (select ROLE from dba_roles where role <> '$UsuarioRol' and PASSWORD_REQUIRED <> 'GLOBAL'))
		            where parte between $start and $limit";
        $stid = oci_parse($conn, $query);
        oci_execute($stid);
        $query1 = "select * from dba_role_privs where GRANTEE='$UsuarioRol'";
        $stid1 = oci_parse($conn, $query1);
        while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
            $roles[$key]['rol'] = $row['ROLE'];
            $roles[$key]['grentee'] = false;
            $roles[$key]['opcion'] = false;
            oci_execute($stid1);
            while ($row1 = oci_fetch_array($stid1, OCI_ASSOC)) {
                if ($row['ROLE'] == $row1['GRANTED_ROLE']) {
                    $roles[$key]['grentee'] = true;
                    if ($row1['ADMIN_OPTION'] == "YES") {
                        $roles[$key]['opcion'] = true;
                    }
                }
            }
            $key++;
        }
        $arr = array();
        $consult_cant = "select count (*) as cantidad from dba_roles where role <> '$UsuarioRol' and PASSWORD_REQUIRED <> 'GLOBAL'";
        $stid1 = oci_parse($conn, $consult_cant);
        oci_execute($stid1);
        $row = oci_fetch_array($stid1, OCI_ASSOC);
        $cantidad = $row['CANTIDAD'];
        $arr['cantidad_filas'] = $cantidad;
        $arr['datos'] = $roles;
        oci_close($conn);
        echo json_encode($arr);
    }

    //funcion de oracle para enviar a interfas el listado de privilegios que tiene un usuario o un rol
    function oracleMuestraPrivilegiosAsignadosAction() {
        $RSA = new ZendExt_RSA_Facade();
        $key = 0;
        $privilegios = array();
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $SID = DatGestorDatServidorbd::getSid($idgestor, $idservidor);
        $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
        $conn = oci_connect("$user", "$pass", "$cadenaConex");
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $limit = $start + $limit - 1;
        $UsuarioRol = $this->_request->getPost('usuariorol');
        $query = "SELECT PRIVILEGE FROM ( SELECT rownum parte,PRIVILEGE 
           		   FROM ( SELECT distinct (PRIVILEGE) FROM dba_sys_privs where PRIVILEGE <> 'ADMINISTER RESOURCE MANAGER' order by PRIVILEGE ))
		           WHERE parte between $start and $limit";
        $stid = oci_parse($conn, $query);
        oci_execute($stid);
        $query1 = "select * from dba_sys_privs where GRANTEE='$UsuarioRol'";
        $stid1 = oci_parse($conn, $query1);
        while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
            $privilegios[$key]['privilegio'] = $row['PRIVILEGE'];
            $privilegios[$key]['grantee'] = false;
            $privilegios[$key]['opcion'] = false;
            oci_execute($stid1);
            while ($row1 = oci_fetch_array($stid1, OCI_ASSOC)) {
                if ($row1['PRIVILEGE'] == $row['PRIVILEGE']) {
                    $privilegios[$key]['grantee'] = true;
                    if ($row1['ADMIN_OPTION'] == "YES") {
                        $privilegios[$key]['opcion'] = true;
                    }
                }
            }
            $key++;
        }
        $arr = array();
        $consult_cant = "select count (distinct (PRIVILEGE)) as cantidad from dba_sys_privs order by PRIVILEGE";
        $stid1 = oci_parse($conn, $consult_cant);
        oci_execute($stid1);
        $row = oci_fetch_array($stid1, OCI_ASSOC);
        $cantidad = $row['CANTIDAD'];
        $arr['cantidad_filas'] = $cantidad;
        $arr['datos'] = $privilegios;
        oci_close($conn);
        echo json_encode($arr);
    }

    //Funcion de oracle para darle privilegios a un Usuario o Rol 
    function oracleDarPrivilegiosAction() {
        $RSA = new ZendExt_RSA_Facade();
        $arreglo_option = array();
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $SID = DatGestorDatServidorbd::getSid($idgestor, $idservidor);
        $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
        $conn = oci_connect("$user", "$pass", "$cadenaConex");
        $usuario = $this->_request->getPost('usuario');
        $arreglo = json_decode(stripslashes($this->_request->getPost('listadoPrivilegiosAcceso')));
        foreach ($arreglo as $datos) {
            if ($datos[1] == false) {
                $query5 = "select * from dba_sys_privs where grantee = '$usuario' and privilege = '$datos[0]'";
                $stid5 = oci_parse($conn, $query5);
                oci_execute($stid5);
                $row = oci_fetch_array($stid5, OCI_ASSOC);
                if ($row != Null) {
                    $query1 = "revoke $datos[0] from $usuario";
                    $stid1 = oci_parse($conn, $query1);
                    oci_execute($stid1);
                }
            }
        }
        foreach ($arreglo as $datos) {
            if (($datos[1] == true) && ($datos[2] == true)) {
                $query2 = "GRANT $datos[0] TO $usuario WITH ADMIN OPTION";
                $stid2 = oci_parse($conn, $query2);
                oci_execute($stid2);
            }
        }
        foreach ($arreglo as $datos) {
            if (($datos[1] == true) && ($datos[2] == false)) {
                $query5 = "select * from dba_sys_privs where grantee = '$usuario' and privilege = '$datos[0]'";
                $stid5 = oci_parse($conn, $query5);
                oci_execute($stid5);
                $row = oci_fetch_array($stid5, OCI_ASSOC);
                if ($row != Null) {
                    $query1 = "revoke $datos[0] from $usuario";
                    $stid1 = oci_parse($conn, $query1);
                    oci_execute($stid1);
                }
                $query2 = "GRANT $datos[0] TO $usuario";
                $stid2 = oci_parse($conn, $query2);
                oci_execute($stid2);
            }
        }
        //   $this->showMessage('Privilegios asignados satisfactoriamente.');
        oci_close($conn);
    }

    //Funcion de oracle para asignarle roles a un rol
    function oracleDarRolaRolAction() {
        $RSA = new ZendExt_RSA_Facade();
        $arreglo_option = array();
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $SID = DatGestorDatServidorbd::getSid($idgestor, $idservidor);
        $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
        $conn = oci_connect("$user", "$pass", "$cadenaConex");
        $usuario = $this->_request->getPost('usuario');
        $arreglo = json_decode(stripslashes($this->_request->getPost('listadoPrivilegiosAcceso')));
        foreach ($arreglo as $datos) {
            if ($datos[1] == false) {
                $query5 = "select * from dba_role_privs where grantee = '$usuario' and GRANTED_ROLE = '$datos[0]'";
                $stid5 = oci_parse($conn, $query5);
                oci_execute($stid5);
                $row = oci_fetch_array($stid5, OCI_ASSOC);

                if ($row != Null) {
                    $query1 = "revoke $datos[0] from $usuario";
                    $stid1 = oci_parse($conn, $query1);
                    oci_execute($stid1);
                }
            }
        }
        foreach ($arreglo as $datos) {
            if (($datos[1] == true) && ($datos[2] == true)) {
                $query2 = "GRANT $datos[0] TO $usuario WITH ADMIN OPTION";
                $stid2 = oci_parse($conn, $query2);
                oci_execute($stid2);
            }
        }

        foreach ($arreglo as $datos) {
            if (($datos[1] == true) && ($datos[2] == false)) {
                $query5 = "select * from dba_role_privs where grantee = '$usuario' and GRANTED_ROLE = '$datos[0]'";
                $stid5 = oci_parse($conn, $query5);
                oci_execute($stid5);
                $row = oci_fetch_array($stid5, OCI_ASSOC);
                if ($row != Null) {
                    $query1 = "revoke $datos[0] from $usuario";
                    $stid1 = oci_parse($conn, $query1);
                    oci_execute($stid1);
                }
                $query2 = "GRANT $datos[0] TO $usuario";
                $stid2 = oci_parse($conn, $query2);
                oci_execute($stid2);
            }
        }
        //  $this->showMessage('Privilegios asignados satisfactoriamente.');
        oci_close($conn);
    }

    //Funcion de oracle para asignar roles a un Usuario 
    function oracleAsignarRolesAUsuariosAction() {
        $RSA = new ZendExt_RSA_Facade();
        $arr = array();
        $auxiliar = array();
        $pos = 0;
        $cadena = null;
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $SID = DatGestorDatServidorbd::getSid($idgestor, $idservidor);
        $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
        $conn = oci_connect("$user", "$pass", "$cadenaConex");
        $usuario = $this->_request->getPost('usuario');
        $arreglo = json_decode(stripslashes($this->_request->getPost('listadoPrivilegiosAcceso')));
        $defecto = json_decode(stripslashes($this->_request->getPost('listadoDefecto')));
        $defectoFalse = json_decode(stripslashes($this->_request->getPost('listadoDefectoFalse')));
        foreach ($arreglo as $datos) {
            if ($datos[1] == false) {
                $query5 = "select * from dba_role_privs where grantee = '$usuario' and GRANTED_ROLE = '$datos[0]'";
                $stid5 = oci_parse($conn, $query5);
                oci_execute($stid5);
                $row = oci_fetch_array($stid5, OCI_ASSOC);
                if ($row != Null) {
                    $query1 = "revoke $datos[0] from $usuario";
                    $stid1 = oci_parse($conn, $query1);
                    oci_execute($stid1);
                }
            }
        }
        foreach ($arreglo as $datos) {
            if (($datos[1] == true) && ($datos[2] == true)) {
                $query2 = "GRANT $datos[0] TO $usuario WITH ADMIN OPTION";
                $stid2 = oci_parse($conn, $query2);
                oci_execute($stid2);
            }
        }
        foreach ($arreglo as $datos) {
            if (($datos[1] == true) && ($datos[2] == false)) {
                $query5 = "select * from dba_role_privs where grantee = '$usuario' and GRANTED_ROLE = '$datos[0]'";
                $stid5 = oci_parse($conn, $query5);
                oci_execute($stid5);
                $row = oci_fetch_array($stid5, OCI_ASSOC);
                if ($row != Null) {
                    $query1 = "revoke $datos[0] from $usuario";
                    $stid1 = oci_parse($conn, $query1);
                    oci_execute($stid1);
                }
                $query2 = "GRANT $datos[0] TO $usuario";
                $stid2 = oci_parse($conn, $query2);
                oci_execute($stid2);
            }
        }
        $query5 = "select GRANTED_ROLE,DEFAULT_ROLE,grantee from dba_role_privs where DEFAULT_ROLE='YES' and grantee = '$usuario'";
        $stid5 = oci_parse($conn, $query5);
        oci_execute($stid5);
        while ($row = oci_fetch_array($stid5, OCI_ASSOC)) {
            $arr[$pos] = $row['GRANTED_ROLE'];
            $pos++;
        }
        $auxiliar = $arr;
        for ($i = 0; $i < count($arr); $i++) {
            for ($j = 0; $j < count($defectoFalse); $j++) {
                if ($arr[$i] == $defectoFalse[$j]) {
                    unset($auxiliar[$i]);
                }
            }
        }
        $arr = array_values($auxiliar);
        $auxiliar = $arr;
        for ($i = 0; $i < count($arr); $i++) {
            for ($j = 0; $j < count($defecto); $j++) {
                if ($arr[$i] == $defecto[$j]) {
                    unset($auxiliar[$i]);
                }
            }
        }
        $arr = array_values($auxiliar);
        $cadena2 = implode(",", $defecto);
        $cadena1 = implode(",", $arr);
        if ($defecto[0] == null && $arr[0] != null)
            $cadena = $cadena1;
        if ($defecto[0] != null && $arr[0] == null)
            $cadena = $cadena2;
        if ($defecto[0] != null && $arr[0] != null)
            $cadena = $cadena2 . "," . $cadena1;
        if ($defecto[0] == null && $arr[0] == null)
            $cadena = "NONE";
        $query4 = "ALTER USER $usuario DEFAULT ROLE $cadena";
        $stid4 = oci_parse($conn, $query4);
        oci_execute($stid4);
        $auxiliar = null;
        //$this->showMessage('Roles asignados satisfactoriamente.');
        oci_close($conn);
    }

    function devolverDatosRol($array) {
        foreach ($array as $key => $valores) {
            $roles[$key]['oid'] = $valores['oid'];
            $roles[$key]['rolname'] = $valores['rolname'];
            $roles[$key]['rolsuper'] = $valores['rolsuper'];
            $roles[$key]['rolinherit'] = $valores['rolinherit'];
            $roles[$key]['rolcreaterole'] = $valores['rolcreaterole'];
            $roles[$key]['rolcreatedb'] = $valores['rolcreatedb'];
            $roles[$key]['rolcatupdate'] = $valores['rolcatupdate'];
            $roles[$key]['rolcanlogin'] = $valores['rolcanlogin'];
            $roles[$key]['rolpassword'] = $valores['rolpassword'];
            if (isset($valores['rolvaliduntil'])) {
                $separar = explode(' ', $valores['rolvaliduntil']);
                $roles[$key]['fechainicio'] = $separar[0];
                $separar = explode(':', $separar[1]);
                $roles[$key]['horaaa'] = $separar[0] . ':' . $separar[1];
            } else {
                $roles[$key]['fechainicio'] = '';
                $roles[$key]['horaaa'] = '';
            }
        }
        return $roles;
    }

    //Usado
    function insertarRolBaseDatoAction() {
        $RSA = new ZendExt_RSA_Facade();
        $gestor = $this->_request->getPost('gestor');
        $function = $gestor . 'Insert';
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));        
        $ipgestorbd = $this->_request->getPost('ip');
        $bd = $this->_request->getPost('bd');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $puerto = DatGestor::getPuertoPorId($idgestor);
        $rolname = $this->_request->getPost('rolname');
        $password = $this->_request->getPost('contrasena');
        $dateExpires = $this->_request->getPost('fechainicio');
        $hora = $this->_request->getPost('horaaa');
        $permisos = $this->_request->getPost('permisos');
        $rolsuper = $this->_request->getPost('rolsuper');
        $rolcreatedb = $this->_request->getPost('rolcreatedb');
        $rolcreaterole = $this->_request->getPost('rolcreaterole');
        $rolcatupdate = $this->_request->getPost('rolcatupdate');
        $cargarRoles = $gestor . 'CargarRolesBD';
        $this->$function($ipgestorbd, $gestor, $bd, $user, $pass, $idservidor, $idgestor, $rolname, $password, $dateExpires, $hora, $permisos, $rolsuper, $rolcreatedb, $rolcreaterole, $rolcatupdate, $puerto);
        echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfAdd}");      
    }

    //Usado
    function modificarRolBaseDatoAction() {
        $RSA = new ZendExt_RSA_Facade();
        $gestor = $this->_request->getPost('gestor');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $bd = $this->_request->getPost('bd');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $puerto = DatGestor::getPuertoPorId($idgestor);
        $function = $gestor . 'ModifyRole';
        $oid = $this->_request->getPost('oid');
        $rolname = $this->_request->getPost('rolname');
        $password = $this->_request->getPost('contrasena');
        $dateExpires = $this->_request->getPost('fechainicio');
        $hora = $this->_request->getPost('horaaa');
        $permisos = $this->_request->getPost('permisos');
        $rolsuper = $this->_request->getPost('rolsuper');
        $rolcreatedb = $this->_request->getPost('rolcreatedb');
        $rolcreaterole = $this->_request->getPost('rolcreaterole');
        $rolcatupdate = $this->_request->getPost('rolcatupdate');
        $estado = $this->_request->getPost('estado');
        $this->$function($ipgestorbd, $gestor, $bd, $user, $pass, $idservidor, $idgestor, $oid, $rolname, $password, $dateExpires, $hora, $permisos, $rolsuper, $rolcreatedb, $rolcreaterole, $rolcatupdate, $estado, $puerto);
       echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfMod}");         
    }

    //Usado
    function eliminarRolesDBAction() {
        $RSA = new ZendExt_RSA_Facade();
        $gestor = $this->_request->getPost('gestor');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $bd = $this->_request->getPost('bd');
        $function = $gestor . 'DeleteRole';
        $oid = $this->_request->getPost('oid');
        $rolname = $this->_request->getPost('rolname');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $puerto = DatGestor::getPuertoPorId($idgestor);
        $this->$function($ipgestorbd, $gestor, $bd, $idservidor, $idgestor, $user, $pass, $oid, $rolname, $puerto);        
    }

    //Usado
    function crearConexionAction() {
        $RSA = new ZendExt_RSA_Facade();
        $gestor = $this->_request->getPost('gestor');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $bd = $this->_request->getPost('bd');
        $oid = $this->_request->getPost('oid');
        $password = $this->_request->getPost('contrasena');
        $dateExpires = $this->_request->getPost('fechainicio');
        $hora = $this->_request->getPost('horaaa');
        $permisos = $this->_request->getPost('permisos');
        $rolsuper = $this->_request->getPost('rolsuper');
        $rolcreatedb = $this->_request->getPost('rolcreatedb');
        $rolcreaterole = $this->_request->getPost('rolcreaterole');
        $rolcatupdate = $this->_request->getPost('rolcatupdate');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $rolname = $this->_request->getPost('rolname');
        $password = $this->_request->getPost('contrasena');
        $function = $gestor . 'ModifyRole';
        $rsa = new ZendExt_RSA_Facade();
        $objRolesbd = new SegRolesbd();
        $objRolesbd->idservidor = $idservidor;
        $objRolesbd->idgestor = $idgestor;
        $objRolesbd->nombrerol = $rolname;
        $objRolesbd->passw = $rsa->encrypt($password);
        $objRolesbd->save();
        $this->$function($ipgestorbd, $gestor, $bd, $user, $pass, $idservidor, $idgestor, $oid, $rolname, $password, $dateExpires, $hora, $permisos, $rolsuper, $rolcreatedb, $rolcreaterole, $rolcatupdate, 0);
        echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfConex}");           
    }

    function eliminarConexionAction() {
        $rolname = $this->_request->getPost('rolname');
        $idservidor = $this->_request->getPost('idservidor');
        $idgestor = $this->_request->getPost('idgestor');
        $exist = SegRolesbd::exist($rolname, $idgestor, $idservidor);
        $idrolesbd = $exist[0]->idrolesbd;
        if (!$this->verifySistems($idrolesbd)) {
            SegRolesbd::deleteRol($idrolesbd);
            echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfConexI}");                
        }
        else
            throw new ZendExt_Exception('SEGROLBD1');
    }

    private function pgsqlInsert($ipgestorbd, $gestor, $bd, $user, $pass, $idservidor, $idgestor, $roleName, $password, $dateExpires, $hora, $permisos, $rolsuper, $rolcreatedb, $rolcreaterole, $rolcatupdate, $puerto) {
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$bd", 'pg_catalog');
        $newObj = new ZendExt_Db_Role_Pgsql();
        $this->VerificarRolName($conn, $roleName);
        $newObj->roleName = $roleName;
        $newObj->password = $password;         
        $newObj->dateExpires = $dateExpires;
        if ($hora)
            $newObj->timeExpires = $hora;
        if ($permisos == 'on')
            $newObj->inherit = true;
        if ($rolsuper == 'on')
            $newObj->superUser = true;
        if ($rolcreatedb == 'on')
            $newObj->canCreateDb = true;
        if ($rolcreaterole == 'on')
            $newObj->canCreateRole = true;
        if ($rolcatupdate == 'on')
            $newObj->canUpdateCat = true;
        $newObj->save($conn);
        $dm->setCurrentConnection($nameCurrentConn);
    }

    private function VerificarRolName($conn, $roleName) {
        $result = PgAuthid::existRolname($conn, $roleName)->toArray();
        if (count($result) > 0) {
            throw new ZendExt_Exception('SEGROLBD2');
        }
    }

    private function pgsqlModifyRole($ipgestorbd, $gestor, $bd, $user, $pass, $idservidor, $idgestor, $oid, $rolname, $password, $dateExpires, $hora, $permisos, $rolsuper, $rolcreatedb, $rolcreaterole, $rolcatupdate, $estado, $puerto) {
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$bd", 'pg_catalog');
        $newObj = new ZendExt_Db_Role_Pgsql();
        $result = $newObj->find($oid, $conn);
        $name = $result->lastFindObject->rolname;
        $cargarRoles = $gestor . 'CargarRolesBD';
        $result->roleName = $rolname;
        $result->dateExpires = $dateExpires;
        $result->timeExpires = $hora;
        if ($password) {
            $result->password = $password;
        }
        if ($permisos == 'on')
            $result->inherit = true;
        else
            $result->inherit = false;
        if ($rolsuper == 'on')
            $result->superUser = true;
        else
            $result->superUser = false;
        if ($rolcreatedb == 'on')
            $result->canCreateDb = true;
        else
            $result->canCreateDb = false;
        if ($rolcreaterole == 'on')
            $result->canCreateRole = true;
        else
            $result->canCreateRole = false;
        if ($rolcatupdate == 'on')
            $result->canUpdateCat = true;
        else
            $result->canUpdateCat = false;

        $result->save($conn);
        $dm->setCurrentConnection($nameCurrentConn);
        if ($estado == 1)
            $this->updateSegRolesbd($name, $rolname, $idservidor, $idgestor, $password);
    }

    private function updateSegRolesbd($name, $rolname, $idservidor, $idgestor, $password) {

        $rsa = new ZendExt_RSA_Facade();
        $exist = SegRolesbd::exist($name, $idservidor, $idgestor);
        if (count($exist)) {
            $objRolesbd = Doctrine::getTable('SegRolesbd')->find($exist[0]->idrolesbd);
            $objRolesbd->nombrerol = $rolname;
            $objRolesbd->passw = $rsa->encrypt($password);
            $objRolesbd->save();
            return;
        }
    }

    private function pgsqlDeleteRole($ipgestorbd, $gestor, $bd, $idservidor, $idgestor, $user, $pass, $oid, $rolname, $puerto) {
        $exception = false;        
        $exist = SegRolesbd::exist($rolname,$idgestor,$idservidor);        
           if (!$this->verifySistems($exist[0]->idrolesbd)) {               
            SegRolesbd::deleteRol($exist[0]->idrolesbd);
            $dm = Doctrine_Manager::getInstance();
            $nameCurrentConn = $dm->getCurrentConnection()->getName();
            $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$bd", 'pg_catalog');
            $newObj = new ZendExt_Db_Role_Pgsql();
            $result = $newObj->find($oid, $conn);         
            try {
                //$conn->execute("drop owned by $rolname cascade");
                $result->delete($conn);                
            } catch (Exception $e) {                
                $dm->setCurrentConnection($nameCurrentConn);
                $mensaje = $e->getMessage();
                $exception = true;
                $mensaje = $this->ArreglarExcepcion($mensaje);
                echo"{'error':3,'mensaje':perfil.etiquetas.lbMsgErrorPrivilegios,'objetos':'$mensaje',}";
                $dm->setCurrentConnection($nameCurrentConn);
            }
            $dm->setCurrentConnection($nameCurrentConn);
            if (!$exception)
                echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfDel}");                 
        }        
        else             
            throw new ZendExt_Exception('SEGROLBD1');
    }

    private function ArreglarExcepcion($mensaje) {
        $arrayMensaes = array();
        $arrayMensaes = split(":", $mensaje);
        //$resultad = "El rol no puede ser eliminado, está siendo referenciado de:";
        if (trim($arrayMensaes[1]) == "Dependent objects still exist") {
            $mensaje = trim($arrayMensaes[4]);            
            $arrLineas = split("\n", $mensaje);            
            $cant = count($arrLineas);
            foreach ($arrLineas as $Linea) {                
                $arraPalabras = split(" ", $Linea);                
                if ($cant > 1)
                    $resultad.=" " .$arraPalabras[3] . ",";
                else
                    $resultad.=" " .$arraPalabras[3] . ".";
                $cant--;
            }            
            return $resultad;
        }
        else {
            echo("{'codMsg':3,'mensaje':perfil.etiquetas.lbMsgErrorInterno}"); 
            //return "Error interno del sistema, por favor contacte al administrador.";
        }
    }

   private function verifySistems($idrolesbd) {       
        $cantidad=DatSistemaDatServidores::countSistemsByIdrolesBd($idrolesbd);       
        if ($cantidad > 0){            
            return true;
        }
        return false;
    }

    //Usado
    function getcriteriosAction() {
        $datos = array(0 => array("criterio" => "Tablas"), 1 => array("criterio" => "Vistas"), 2 => array("criterio" => "Secuencias"), 3 => array("criterio" => "Esquemas"), 4 => array("criterio" => "Base de datos"), 5 => array("criterio" => "Funciones"));
        echo json_encode($datos);
    }

    //Usado
    function configridAction() {
        $result = $result = array('grid' => array('columns' => array()));
        $criterio = $this->_request->getPost('criterio');
        $criterio = $this->criterios["$criterio"];
        switch ($criterio) {
            case "Tables": {
                    $fields = array(0 => "name", 1 => "OWN", 2 => "SEL", 3 => "INS", 4 => "UPD", 5 => "DEL", 6 => "REF", "TRIG");
                }break;
            case 'Views': {
                    $fields = array(0 => "name", 1 => "OWN", 2 => "SEL", 3 => "INS", 4 => "UPD", 5 => "DEL", 6 => "REF", "TRIG");
                }break;
            case "Sequences": {
                    $fields = array(0 => "name", 1 => "SEL", 2 => "UPD", 3 => "USG");
                }break;
            case "Schemas": {
                    $fields = array(0 => "name", 1 => "OWN", 2 => "CRT", 3 => "USG");
                }break;
            case "Databases": {
                    $fields = array(0 => "name", 1 => "OWN", 2 => "CONN", 3 => "CRT", 4 => "TMP");
                }break;
            case "Functions": {
                    $fields = array(0 => "name", 1 => "OWN", 2 => "EXEC");
                }break;
        }
        foreach ($fields as $field) {
            $header = ucwords($field);
            if ($field == 'name')
                $result ['grid'] ['columns'] [] = array('header' => $header, 'width' => 300, 'sortable' => true, 'dataIndex' => $field, 'editor' => true);
            else
                $result ['grid'] ['columns'] [] = array('header' => $header, 'width' => 50, 'sortable' => true, 'dataIndex' => $field, 'editor' => true);
            $result ['grid'] ['campos'] [] = $field;
        }
        echo json_encode($result);
    }

    //Usado
    function cargargriddatosAction() {
        $RSA = new ZendExt_RSA_Facade();
        $limit = $this->_request->getPost('limit');
        $idrolselec = $this->_request->getPost('idrolselec');
        $offset = $this->_request->getPost('start');
        $rolbd = $this->_request->getPost('rolbd');
        $gestor = $this->_request->getPost('gestor');
        $criterio = $this->_request->getPost('criterio');
        $criterio = $this->criterios[$criterio];
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $bd = $this->_request->getPost('bd');
        $esqSelected = $this->_request->getPost('esqSelected');
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd/$bd", 'pg_catalog');
        $funcion = $gestor . 'DataByCritery';
        $result = $this->$funcion($conn, $limit, $offset, $criterio, $rolbd, $idrolselec, $esqSelected);
        $dm->setCurrentConnection($nameCurrentConn);
       
        if (count($result['datos'])) {
            echo json_encode($result);
        } else {     
             echo json_encode(array("cantidad"=>0, "datos"=>array()));            
        }
    }

    private function pgsqlDataByCritery($conn, $limit, $offset, $criterio, $rolbd, $idrolselec, $esqSelected) {

        $esqSelected = $this->TratarCriterioBusqueda($esqSelected);
        $arrayMiembros = explode(".", $esqSelected);
        $esquema = $esqSelected;
        $objeto = $esqSelected;
        $DosMiembros = false;
        if (count($arrayMiembros) > 1) {
            $esquema = $arrayMiembros[0];
            $objeto = $arrayMiembros[1];
            $DosMiembros = true;
        }
        switch ($criterio) {
            case "Tables": {
                    if ($esqSelected) {
                        $result = PgNamespace::getInformationByCriteria($conn, $criterio, $esquema, $objeto, $limit, $offset, $DosMiembros)->toArray(true);
                        $filas = PgNamespace::getCantRecordsByCriteria($conn, $criterio, $esquema, $objeto, $DosMiembros);
                    } else {
                        $result = PgNamespace::getInformation($conn, $criterio, $limit, $offset)->toArray(true);
                        $filas = PgNamespace::getCantRecords($conn, $criterio);
                    }
                    foreach ($result as $key => $valores) {
                        $datos[$key]['name'] = $valores['PgNamespace']['nspname'] . '.' . $valores['name'];
                        if ($idrolselec == $valores['relowner'])
                            $datos[$key]['OWN'] = true;
                        else
                            $datos[$key]['OWN'] = false;
                        $result = $this->armarPermisos($valores['relacl'], $rolbd, $criterio);
                        if (isset($result['INS']))
                            $datos[$key]['INS'] = $result['INS'];
                        else
                            $datos[$key]['INS'] = false;
                        if (isset($result['SEL']))
                            $datos[$key]['SEL'] = $result['SEL'];
                        else
                            $datos[$key]['SEL'] = false;
                        if (isset($result['UPD']))
                            $datos[$key]['UPD'] = $result['UPD'];
                        else
                            $datos[$key]['UPD'] = false;
                        if (isset($result['DEL']))
                            $datos[$key]['DEL'] = $result['DEL'];
                        else
                            $datos[$key]['DEL'] = false;
                        if (isset($result['REF']))
                            $datos[$key]['REF'] = $result['REF'];
                        else
                            $datos[$key]['REF'] = false;
                        if (isset($result['TRIG']))
                            $datos[$key]['TRIG'] = $result['TRIG'];
                        else
                            $datos[$key]['TRIG'] = false;
                    }
                    $array = array('cantidad' => $filas, 'datos' => $datos);
                }break;
            case 'Views': {
                    if ($esqSelected) {
                        $result = PgNamespace::getInformationByCriteria($conn, $criterio, $esquema, $objeto, $limit, $offset, $DosMiembros)->toArray(true);
                        $filas = PgNamespace::getCantRecordsByCriteria($conn, $criterio, $esquema, $objeto, $DosMiembros);
                    } else {
                        $result = PgNamespace::getInformation($conn, $criterio, $limit, $offset)->toArray(true);
                        $filas = PgNamespace::getCantRecords($conn, $criterio);
                    }

                    foreach ($result as $key => $valores) {
                        $datos[$key]['name'] = $valores['PgNamespace']['nspname'] . '.' . $valores['name'];
                        if ($idrolselec == $valores['relowner'])
                            $datos[$key]['OWN'] = true;
                        else
                            $datos[$key]['OWN'] = false;
                        $result = $this->armarPermisos($valores['relacl'], $rolbd, $criterio);
                        if (isset($result['INS']))
                            $datos[$key]['INS'] = $result['INS'];
                        else
                            $datos[$key]['INS'] = false;
                        if (isset($result['SEL']))
                            $datos[$key]['SEL'] = $result['SEL'];
                        else
                            $datos[$key]['SEL'] = false;
                        if (isset($result['UPD']))
                            $datos[$key]['UPD'] = $result['UPD'];
                        else
                            $datos[$key]['UPD'] = false;
                        if (isset($result['DEL']))
                            $datos[$key]['DEL'] = $result['DEL'];
                        else
                            $datos[$key]['DEL'] = false;
                        if (isset($result['REF']))
                            $datos[$key]['REF'] = $result['REF'];
                        else
                            $datos[$key]['REF'] = false;
                        if (isset($result['TRIG']))
                            $datos[$key]['TRIG'] = $result['TRIG'];
                        else
                            $datos[$key]['TRIG'] = false;
                    }
                    $array = array('cantidad' => $filas, 'datos' => $datos);
                }break;
            case "Sequences": {
                    if ($esqSelected) {
                        $result = PgNamespace::getInformationByCriteria($conn, $criterio, $esquema, $objeto, $limit, $offset, $DosMiembros)->toArray(true);
                        $filas = PgNamespace::getCantRecordsByCriteria($conn, $criterio, $esquema, $objeto, $DosMiembros);
                    } else {
                        $result = PgNamespace::getInformation($conn, $criterio, $limit, $offset)->toArray(true);
                        $filas = PgNamespace::getCantRecords($conn, $criterio);
                    }
                    foreach ($result as $key => $valores) {
                        $datos[$key]['name'] = $valores['PgNamespace']['nspname'] . '.' . $valores['name'];
                        if ($idrolselec == $valores['relowner'])
                            $datos[$key]['OWN'] = true;
                        else
                            $datos[$key]['OWN'] = false;
                        $result = $this->armarPermisos($valores['relacl'], $rolbd, $criterio);
                        if (isset($result['SEL']))
                            $datos[$key]['SEL'] = $result['SEL'];
                        else
                            $datos[$key]['SEL'] = false;
                        if (isset($result['UPD']))
                            $datos[$key]['UPD'] = $result['UPD'];
                        else
                            $datos[$key]['UPD'] = false;
                        if (isset($result['USG']))
                            $datos[$key]['USG'] = $result['USG'];
                        else
                            $datos[$key]['USG'] = false;
                    }
                    $array = array('cantidad' => $filas, 'datos' => $datos);
                }break;
            case "Schemas": {
                    if ($esqSelected) {
                        $result = PgNamespace::getInformationSchemasByCriteria($conn, $esqSelected, $limit, $offset)->toArray(true);
                        $filas = PgNamespace::getRecordsSchemasByCriteria($conn, $esqSelected);
                    } else {
                        $result = PgNamespace::getInformationSchemas($conn, $limit, $offset)->toArray(true);
                        $filas = PgNamespace::getRecordsSchemas($conn);
                    }
                    foreach ($result as $key => $valores) {
                        $datos[$key]['name'] = $valores['nspname'];
                        if ($valores['nspowner'] == $idrolselec)
                            $datos[$key]['OWN'] = true;
                        else
                            $datos[$key]['OWN'] = false;
                        $result = $this->armarPermisos($valores['nspacl'], $rolbd, $criterio);
                        if (isset($result['CRT']))
                            $datos[$key]['CRT'] = $result['CRT'];
                        else
                            $datos[$key]['CRT'] = false;
                        if (isset($result['USG']))
                            $datos[$key]['USG'] = $result['USG'];
                        else
                            $datos[$key]['USG'] = false;
                    }
                    $array = array('cantidad' => $filas, 'datos' => $datos);
                }break;
            case "Databases": {
                    if ($esqSelected) {
                        $result = PgDatabase::getInformationDatabasesByCriteria($conn, $esqSelected, $limit, $offset)->toArray(true);
                        $filas = PgDatabase::getRecordsDatabasesByCriteria($conn, $esqSelected);
                    } else {
                        $result = PgDatabase::getInformationDatabases($conn, $limit, $offset)->toArray(true);
                        $filas = PgDatabase::getRecordsDatabases($conn);
                    }
                    foreach ($result as $key => $valores) {
                        $datos[$key]['name'] = $valores['datname'];
                        if ($valores['datdba'] == $idrolselec)
                            $datos[$key]['OWN'] = true;
                        else
                            $datos[$key]['OWN'] = false;
                        $result = $this->armarPermisos($valores['datacl'], $rolbd, $criterio);
                        if (isset($result['CRT']))
                            $datos[$key]['CRT'] = $result['CRT'];
                        else
                            $datos[$key]['CRT'] = false;
                        if (isset($result['CONN']))
                            $datos[$key]['CONN'] = $result['CONN'];
                        else
                            $datos[$key]['CONN'] = false;
                        if (isset($result['TMP']))
                            $datos[$key]['TMP'] = $result['TMP'];
                        else
                            $datos[$key]['TMP'] = false;
                    }
                    $array = array('cantidad' => $filas, 'datos' => $datos);
                }break;
            case "Functions": {
                    if ($esqSelected) {
                        $result = PgProc::getInformationByCriteria($conn, $esquema, $objeto, $limit, $offset, $DosMiembros)->toArray(true);
                        $filas = PgProc::getCantRecordsByCriteria($conn, $esquema, $objeto, $DosMiembros);
                    } else {
                        $result = PgProc::getInformation($conn, $limit, $offset)->toArray(true);
                        $filas = PgProc::getCantRecords($conn);
                    }
                    foreach ($result as $key => $valores) {
                        $datos[$key]['name'] = $valores['PgNamespace']['nspname'] . '.' . $valores['proname'];
                        if ($valores['proowner'] == $idrolselec)
                            $datos[$key]['OWN'] = true;
                        else
                            $datos[$key]['OWN'] = false;
                        $result = $this->armarPermisos($valores['proacl'], $rolbd, $criterio);
                        if (isset($result['EXEC']))
                            $datos[$key]['EXEC'] = $result['EXEC'];
                        else
                            $datos[$key]['EXEC'] = false;
                    }
                    $array = array('cantidad' => $filas, 'datos' => $datos);
                }break;
        }
        return $array;
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

    private function armarPermisos($permisos, $rolbd, $criterio) {
        $cadena = $this->armaCadena($permisos);
        $array = explode(',', $cadena);
        $result = array();
        foreach ($array as $valor) {
            $valor = explode('=', $valor);
            if ($valor[0] == $rolbd) {
                $function = 'pgsql' . $criterio;
                $result = $this->$function($valor[1]);
                break;
            }
            else
                continue;
        }
        return $result;
    }

    private function armaCadena($nspacl) {
        for ($i = 1; $i < strlen($nspacl) - 1; $i++)
            $cadena .= $nspacl[$i];
        return $cadena;
    }

    private function pgsqlTables($valor) {
        $val = explode('/', $valor);
        $result['INS'] = $this->validar($val[0], 'a');
        $result['SEL'] = $this->validar($val[0], 'r');
        $result['UPD'] = $this->validar($val[0], 'w');
        $result['DEL'] = $this->validar($val[0], 'd');
        $result['REF'] = $this->validar($val[0], 'x');
        $result['TRIG'] = $this->validar($val[0], 't');
        return $result;
    }

    private function pgsqlViews($valor) {
        $val = explode('/', $valor);
        $result['INS'] = $this->validar($val[0], 'a');
        $result['SEL'] = $this->validar($val[0], 'r');
        $result['UPD'] = $this->validar($val[0], 'w');
        $result['DEL'] = $this->validar($val[0], 'd');
        $result['REF'] = $this->validar($val[0], 'x');
        $result['TRIG'] = $this->validar($val[0], 't');
        return $result;
    }

    private function pgsqlSequences($valor) {
        $val = explode('/', $valor);
        $result['SEL'] = $this->validar($val[0], 'r');
        $result['UPD'] = $this->validar($val[0], 'w');
        $result['USG'] = $this->validar($val[0], 'U');
        return $result;
    }

    private function pgsqlSchemas($valor) {
        $val = explode('/', $valor);
        $result['USG'] = $this->validar($val[0], 'U');
        $result['CRT'] = $this->validar($val[0], 'C');
        return $result;
    }

    private function pgsqlDatabases($valor) {
        $val = explode('/', $valor);
        $result['CRT'] = $this->validar($val[0], 'C');
        $result['CONN'] = $this->validar($val[0], 'c');
        $result['TMP'] = $this->validar($val[0], 'T');
        return $result;
    }

    private function pgsqlFunctions($valor) {
        $val = explode('/', $valor);
        $result['EXEC'] = $this->validar($val[0], 'X');
        return $result;
    }

    private function validar($cadena, $caracter) {
        for ($i = 0; $i < strlen($cadena); $i++)
            if ($cadena[$i] == $caracter)
                return true;
        return false;
    }

    private function oracleDataByCritery() {
        switch ('') {
            case "Tables": {
                    
                }break;
            case 'Views': {
                    
                }break;
            case "Sequences": {
                    
                }break;
            case "Schemas": {
                    
                }break;
            case "Databases": {
                    
                }break;
            case "Functions": {
                    
                }break;
        }
        return;
    }

    //Usado
    function modificarPermisosAction() {
        $RSA = new ZendExt_RSA_Facade();
        $arrayAccess = json_decode(stripslashes($this->_request->getPost('acceso')));
        $arrayDeny = json_decode(stripslashes($this->_request->getPost('denegado')));
        $usuariobd = $this->_request->getPost('usuariobd');
        $rolbd = $this->_request->getPost('rolbd');
        $gestor = $this->_request->getPost('gestor');
        $criterio = $this->_request->getPost('criterio');
        $user = $this->_request->getPost('user');
        $pass = $RSA->decrypt($this->_request->getPost('passw'));
        $ipgestorbd = $this->_request->getPost('ip');
        $bd = $this->_request->getPost('bd');
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd/$bd", 'pg_catalog');
        $obj = new ZendExt_Db_Role_Pgsql();
        $obj->modifyAccess($arrayAccess, $arrayDeny, $conn, $usuariobd, $this->lista[$criterio]);
        $dm->setCurrentConnection($nameCurrentConn);
        echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfPermiso}");
    }

    //Usado             
    private function VerifyConnection($dsn) {
        $c = explode("@", $dsn);
        $ig = explode(":", $c[1]);
        $ip = $ig[0];
        if (PHP_OS == "Linux") {
            $str = exec("ping -c1 -W2 $ip", $input, $result);
        } else {
            $str = exec("ping -n 1 -w 1 $ip", $input, $result);
        }
        if ($result != 0) {
            throw new ZendExt_Exception('SEG060');
        }
    }

}

?>
