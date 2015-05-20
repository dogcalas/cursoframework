<?php

class ZendExt_Db_Role_Pgsql extends ZendExt_Db_Role_Abstract {

    protected $sqlList = array(
        'createRole' => 'CREATE ROLE ',
        'dropRole' => 'DROP ROLE ',
        'alterRole' => 'ALTER ROLE ',
        'rename' => 'RENAME TO ',
        'encrypted' => 'ENCRYPTED ',
        'password' => 'PASSWORD ',
        'canLogin' => 'LOGIN ',
        'superUser' => 'SUPERUSER ',
        'noInherit' => 'NOINHERIT ',
        'Inherit' => 'INHERIT ',
        'canCreateDb' => 'CREATEDB ',
        'canCreateRole' => 'CREATEROLE ',
        'accountExpires' => 'VALID UNTIL ',
        'owner' => 'OWNER',
        'grant' => 'GRANT',
        'revoke' => 'REVOKE',
        'alter' => 'ALTER ',
        'SEL' => 'SELECT',
        'INS' => 'INSERT',
        'UPD' => 'UPDATE',
        'CRT' => 'CREATE',
        'DEL' => 'DELETE',
        'REF' => 'REFERENCES',
        'TRIG' => 'TRIGGER',
        'USG' => 'USAGE',
        'TMP' => 'TEMP',
        'EXEC' => 'EXECUTE',
        'CONN' => 'CONNECT',
        'OWN' => 'OWNER',
        'to' => ' TO ',
        'from' => ' FROM ',
        'on' => ' ON ',
        'not' => 'NO',
        'end' => ';'
    );
    public $canLogin = true;
    public $superUser = false;
    public $inherit = false;
    public $canCreateDb = false;
    public $canCreateRole = false;
    public $canUpdateCat = false;
    public $dateExpires;
    public $timeExpires;
    public $encrypted = true;
    protected $Access = array();
    protected $Deny = array();

    public function __construct($conn = null) {
        if ($conn instanceof Doctrine_Connection)
            $this->conn = $conn;
        else {
            $dm = Doctrine_Manager::getInstance();
            if (is_string($conn))
                $this->conn = $dm->getConnection($conn);
            else
                $this->conn = $dm->getCurrentConnection();
        }
    }

    public function accountExpires($date, $time) {
        $this->dateExpires = $date;
        $this->timeExpires = $time;
    }

    public static function finByRoleName($roleName, Doctrine_Connection $conn = null) {
        $instance = new self($conn);
        $dm = Doctrine_Manager::getInstance();
        $currentConnName = $dm->getCurrentConnection()->getName();
        $dm->setCurrentConnection($instance->conn->getName());
        $role = Doctrine::getTable('PgAuthid')->findBySql('rolname LIKE ?', array($roleName));
        if ($role->getData() != null) {
            $instance->lastFindObject = $role[0];
            $instance->roleName = $role[0]->rolname;
            $instance->superUser = $role[0]->rolsuper;
            $instance->canUpdateCat = $role[0]->rolcatupdate;
            $instance->canCreateDb = $role[0]->rolcreatedb;
            $instance->canCreateRole = $role[0]->rolcreaterole;
            $expires = explode(' ', $role[0]->rolvaliduntil);
            $instance->dateExpires = $expires[0];
            $instance->timeExpires = $expires[1];
            $instance->new = false;
        } else
            $instance = null;
        $dm->setCurrentConnection($currentConnName);
        return $instance;
    }

    public function find($oid, Doctrine_Connection $conn = null) {
        $instance = new self($conn);
        $dm = Doctrine_Manager::getInstance();
        $currentConnName = $dm->getCurrentConnection()->getName();
        $dm->setCurrentConnection($instance->conn->getName());
        $role = Doctrine::getTable('PgAuthid')->find($oid);
        if ($role->getData() != null) {
            $instance->lastFindObject = $role;
            $instance->roleName = $role->rolname;
            $instance->superUser = $role->rolsuper;
            $instance->canUpdateCat = $role->rolcatupdate;
            $instance->canCreateDb = $role->rolcreatedb;
            $instance->canCreateRole = $role->rolcreaterole;
            $instance->inherit = $role->rolinherit;
            $instance->password = $role->rolpassword;
            $instance->new = false;
        } else
            $instance = null;
        $dm->setCurrentConnection($currentConnName);
        return $instance;
    }

    public function getSqlForUpdate() {
        if ($this->lastFindObject->rolname != $this->roleName)
            $sqlRename = $this->sqlList['alterRole'] . $this->lastFindObject->rolname . ' ' . $this->sqlList['rename'] . $this->roleName . $this->sqlList['end'];
        $sqlAlterRol = $this->sqlList['alterRole'] . $this->roleName . ' ';
        $sql = '';
        if ($this->lastFindObject->rolcanlogin != $this->canLogin)
            $sql .= ((!$this->canLogin) ? $this->sqlList['not'] : '') . $this->sqlList['canLogin'];

        if ($this->lastFindObject->rolinherit != $this->inherit)
            $sql .= ((!$this->inherit) ? $this->sqlList['not'] : '') . $this->sqlList['Inherit'];

        if ($this->password) {
            $sql .= ($this->encrypted) ? $this->sqlList['encrypted'] : '';
            $sql .= $this->sqlList['password'] . "'$this->password' ";
        }
        if ($this->lastFindObject->rolsuper != $this->superUser)
            $sql .= ((!$this->superUser) ? $this->sqlList['not'] : '') . $this->sqlList['superUser'];
        if ($this->lastFindObject->rolcreatedb != $this->canCreateDb)
            $sql .= ((!$this->canCreateDb) ? $this->sqlList['not'] : '') . $this->sqlList['canCreateDb'];
        if ($this->lastFindObject->rolcreaterole != $this->canCreateRole)
            $sql .= ((!$this->canCreateRole) ? $this->sqlList['not'] : '') . $this->sqlList['canCreateRole'];
        $expires = explode(' ', $this->lastFindObject->rolvaliduntil);
        if ($this->dateExpires != $expires[0] || $this->timeExpires != $expires[1]) {
            if ($this->dateExpires != 'infinity' && $this->dateExpires != '') {
                $sql .= $this->sqlList['accountExpires'] . "'{$this->dateExpires}";
                $sql .= ($this->timeExpires) ? " {$this->timeExpires}' " : "' ";
            } else
                $sql .= $this->sqlList['accountExpires'] . "'infinity'";
        }
        if ($sql != '')
            $sql = $sqlRename . $sqlAlterRol . $sql . $this->sqlList['end'];
        else
            $sql = $sqlRename;
        return $sql;
    }

    public function getSqlForCreate() {
        $sql = '';
        $sql .= $this->sqlList['createRole'] . $this->roleName . ' ';
        $sql .= ($this->canLogin) ? $this->sqlList['canLogin'] : '';
        $sql .= ($this->encrypted) ? $this->sqlList['encrypted'] : '';
        $sql .= $this->sqlList['password'] . "'$this->password' ";
        $sql .= ($this->superUser) ? $this->sqlList['superUser'] : '';
        $sql .= (!$this->inherit) ? $this->sqlList['noInherit'] : '';
        $sql .= ($this->canCreateDb) ? $this->sqlList['canCreateDb'] : '';
        $sql .= ($this->canCreateRole) ? $this->sqlList['canCreateRole'] : '';
        if ($this->dateExpires) {
            $sql .= $this->sqlList['accountExpires'] . "'{$this->dateExpires}";
            $sql .= ($this->timeExpires) ? " {$this->timeExpires}' " : "' ";
        } else
            $sql .= $this->sqlList['accountExpires'] . "'infinity'";
        $sql .= $this->sqlList['end'];
        return $sql;
    }

    public function getSqlForDelete() {
        $sql = $this->sqlList['dropRole'] . $this->roleName . $this->sqlList['end'];
        return $sql;
    }

    public function save(Doctrine_Connection $conn = null) {
        if (is_null($conn))
            $conn = $this->conn;
        if (isset($this->lastFindObject->rolcatupdate) && $this->lastFindObject->rolcatupdate != $this->canUpdateCat) {
            $this->lastFindObject->rolcatupdate = $this->canUpdateCat;
            $this->lastFindObject->save($conn);
        }
        parent::save($conn);
    }

    public function getDatabases($gestor, $user, $pass, $ipgestorbd) {
        $rsa = new ZendExt_RSA_Facade();
        $bdArr = array();
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd/template1", 'pg_catalog');
        $bdArr = PgDatabase::getPgsqlDatabases($conn);
        $dm->setCurrentConnection($nameCurrentConn);
        return $bdArr;
    }

    public function getPgsqlSchemas($gestor, $user, $pass, $ipgestorbd, $namebd) {
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd/$namebd", 'information_schema');
        $schemas = Schemata::getPgsqlSchemasByDb($conn, $namebd);
        $dm->setCurrentConnection($nameCurrentConn);
        return $schemas;
    }

    function modifyAccess($arrayAccess, $arrayDeny, $conn, $user, $critery) {

        if (count($arrayAccess)) {
            $this->createSqlForAccess($arrayAccess, $user, $critery, $conn);
            foreach ($this->Access as $sql)
                $conn->exec($sql);
        }
        if (count($arrayDeny)) {

            $this->createSqlForDeny($arrayDeny, $user, $critery, $conn);

            foreach ($this->Deny as $sqlDeny)
                $conn->exec($sqlDeny);
        }
    }

    private function createSqlForAccess($arrayAccess, $user, $critery, $conn) {

        foreach ($arrayAccess as $valores)
            $this->createArrayAccess($valores[0], $valores[1], $user, $critery, $conn);
    }

    private function createSqlForDeny($arrayDeny, $user, $critery, $conn) {
        foreach ($arrayDeny as $valores)
            $this->createArrayDeny($valores[0], $valores[1], $user, $critery, $conn);
    }

    private function createArrayAccess($key, $valores, $user, $critery, $conn) {
        $sentencias = ' ';
        $bandera = true;
        $semaforo = true;
        foreach ($valores as $valor) {
            if ($valor != 'OWN') {
                if ($bandera) {
                    $sentencias .= $this->sqlList[$valor];
                    $bandera = false;
                } else
                    $sentencias .= ', ' . $this->sqlList[$valor];
            }
            else {
                if ($critery != 'FUNCTION')
                    $this->Access[] = $this->sqlList['alter'] . $critery . ' ' . $key . ' OWNER' . $this->sqlList['to'] . $user . $this->sqlList['end'];
                else {
                    list($esquema, $funcion) = explode(".", $key);
                    $esquema = trim($esquema);
                    $funcion = trim($funcion);
                    $parametros = PgProc::ObtenerParametrosdeFuncionConn($esquema, $funcion, $conn);

                    $this->Access[] = $this->sqlList['alter'] . $critery . ' ' . $esquema . '.' . '"' . $funcion . '"' . "($parametros)" . ' OWNER' . $this->sqlList['to'] . $user . $this->sqlList['end'];
                }
                if (count($valores) == 1)
                    $semaforo = false;
            }
        }

        if ($critery == 'SECUENCE' || $critery == 'TABLE' && $semaforo)
            $this->Access[] = $this->sqlList['grant'] . $sentencias . $this->sqlList['on'] . $key . $this->sqlList['to'] . $user . $this->sqlList['end'];
        elseif ($semaforo) {
            if ($critery == 'FUNCTION') {
                list($esquema, $funcion) = explode(".", $key);
                $esquema = trim($esquema);
                $funcion = trim($funcion);
                $parametros = PgProc::ObtenerParametrosdeFuncionConn($esquema, $funcion, $conn);

                $this->Access[] = $this->sqlList['grant'] . $sentencias . $this->sqlList['on'] . $critery . ' ' . $esquema . '.' . '"' . $funcion . '"' . "($parametros)" . $this->sqlList['to'] . $user . $this->sqlList['end'];
            } else {
                if ($critery == "VIEW")
                    $critery = "TABLE";
                $this->Access[] = $this->sqlList['grant'] . $sentencias . $this->sqlList['on'] . $critery . ' ' . $key . $this->sqlList['to'] . $user . $this->sqlList['end'];
            }
        }
    }

    private function createArrayDeny($key, $valores, $user, $critery, $conn) {
        $sentencias = ' ';
        $bandera = true;
        $semaforo = true;
        foreach ($valores as $valor) {
            if ($bandera) {
                if ($this->sqlList[$valor] != "OWNER")
                    $sentencias .= $this->sqlList[$valor];
                else
                    $sentencias .= "ALL";
                $bandera = false;
            } else
                $sentencias .= ', ' . $this->sqlList[$valor];
        }
        if ($critery == 'SECUENCE' || $critery == 'TABLE')
            $this->Deny[] = $this->sqlList['revoke'] . $sentencias . $this->sqlList['on'] . $key . $this->sqlList['from'] . $user . $this->sqlList['end'];
        elseif ($critery == 'FUNCTION') {
            list($esquema, $funcion) = explode(".", $key);
            $esquema = trim($esquema);
            $funcion = trim($funcion);
            $parametros = PgProc::ObtenerParametrosdeFuncionConn($esquema, $funcion, $conn);
            $this->Deny[] = $this->sqlList['revoke'] . $sentencias . $this->sqlList['on'] . $critery . ' ' . $esquema . '.' . '"' . $funcion . '"' . "($parametros)" . $this->sqlList['from'] . $user . $this->sqlList['end'];
        } else {
            if ($critery == "VIEW")
                $critery = "TABLE";
            $this->Deny[] = $this->sqlList['revoke'] . $sentencias . $this->sqlList['on'] . $critery . ' ' . $key . $this->sqlList['from'] . $user . $this->sqlList['end'];
        }
    }

//-------------------------------Jose-------------------------------------
//--------------------Obtener Objetos del Catalogo------------------------         

    public function getPgsqlTablasDinamicWhere($gestor, $user, $pass, $ipgestorbd, $namebd, $Where, $puerto) {

        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$namebd", 'information_schema');
        $tablasEsquemas = array();
        try {
            $tablasEsquemas = $this->getPgsqlTablesBySchemaDinamicWhere($conn, $Where);
        } catch (Exception $e) {
            $PgSQL_respuesta = $e->getMessage();

            $dm->closeConnection($conn);
            $dm->setCurrentConnection($nameCurrentConn);
            return array("connectioerror" => $PgSQL_respuesta);
        }
        $dm->closeConnection($conn);
        $dm->setCurrentConnection($nameCurrentConn);
        return $tablasEsquemas;
    }

    public function getPgsqlTablesBySchemaDinamicWhere($conn, $Where) {

        $sql = "select table_name, table_schema from information_schema.tables  where ($Where) and table_type='BASE TABLE' order by table_name";

        $result = $conn->execute($sql)->fetchAll();

        return $result;
    }

//Igual que el de arriba pero ademas se le pasa un criterio por el cual buscar los objetos
    public function getPgsqlTablasDinamicWhereCriterio($gestor, $user, $pass, $ipgestorbd, $namebd, $Where, $criterio, $puerto) {

        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$namebd", 'information_schema');

        $tablasEsquemas = array();

        try {

            $tablasEsquemas = $this->getPgsqlTablesBySchemaDinamicWhereCriterio($conn, $Where, $criterio);
        } catch (Exception $e) {
            $PgSQL_respuesta = $e->getMessage();

            $dm->closeConnection($conn);
            $dm->setCurrentConnection($nameCurrentConn);
            return array("connectioerror" => $PgSQL_respuesta);
        }
        $dm->closeConnection($conn);

        $dm->setCurrentConnection($nameCurrentConn);


        return $tablasEsquemas;
    }

    public function getPgsqlTablesBySchemaDinamicWhereCriterio($conn, $Where, $criterio) {

        $sql = "select table_name, table_schema from information_schema.tables  where ($Where) and table_type='BASE TABLE' and table_name like $criterio ESCAPE '!' order by table_name";

        $result = $conn->execute($sql)->fetchAll();

        return $result;
    }

    public function getPgsqlVistasDinamicWhere($gestor, $user, $pass, $ipgestorbd, $namebd, $where, $puerto) {
        $vistas = array();

        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$namebd", 'information_schema');

        try {

            $vistas = $this->getPgsqlVistasBySchemaDinamicWhere($conn, $where);
        } catch (Exception $e) {

            $PgSQL_respuesta = $e->getMessage();

            $dm->closeConnection($conn);
            $dm->setCurrentConnection($nameCurrentConn);

            return array("connectioerror" => $PgSQL_respuesta);
        }

        $dm->closeConnection($conn);
        $dm->setCurrentConnection($nameCurrentConn);

        return $vistas;
    }

    public function getPgsqlVistasBySchemaDinamicWhere($conn, $where) {
        $sql = "select cl.relname, ns.nspname from Pg_Class cl inner join Pg_namespace ns ON ns.oid=cl.relnamespace where ($where) and cl.relkind='v' order by cl.relname";
        $result = $conn->execute($sql)->fetchAll();
        return $result;
    }

//Lo mismo que el de arriba pero con un criterio de busqueda
    public function getPgsqlVistasDinamicWhereCriterio($gestor, $user, $pass, $ipgestorbd, $namebd, $where, $criterio, $puerto) {
        $vistas = array();

        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$namebd", 'information_schema');

        try {

            $vistas = $this->getPgsqlVistasBySchemaDinamicWhereCriterio($conn, $where, $criterio);
        } catch (Exception $e) {

            $PgSQL_respuesta = $e->getMessage();

            $dm->closeConnection($conn);
            $dm->setCurrentConnection($nameCurrentConn);

            return array("connectioerror" => $PgSQL_respuesta);
        }

        $dm->closeConnection($conn);
        $dm->setCurrentConnection($nameCurrentConn);

        return $vistas;
    }

    public function getPgsqlVistasBySchemaDinamicWhereCriterio($conn, $where, $criterio) {
        $sql = "select cl.relname, ns.nspname from Pg_Class cl inner join Pg_namespace ns ON ns.oid=cl.relnamespace where ($where) and cl.relname like $criterio ESCAPE '!' and cl.relkind='v' order by cl.relname";

        $result = $conn->execute($sql)->fetchAll();
        return $result;
    }

    public function getPgsqlSecuenciasDinamicWhere($gestor, $user, $pass, $ipgestorbd, $namebd, $where, $puerto) {
        $secuencias = array();
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$namebd", 'information_schema');

        try {

            $secuencias = $this->getPgsqlSecuenciasBySchemaDinamicWhere($conn, $where);
        } catch (Exception $e) {

            $PgSQL_respuesta = $e->getMessage();

            $dm->closeConnection($conn);
            $dm->setCurrentConnection($nameCurrentConn);

            return array("connectioerror" => $PgSQL_respuesta);
        }

        $dm->closeConnection($conn);
        $dm->setCurrentConnection($nameCurrentConn);

        return $secuencias;
    }

    public function getPgsqlSecuenciasBySchemaDinamicWhere($conn, $where) {
        $sql = "select cl.relname, ns.nspname from Pg_Class cl inner join Pg_namespace ns ON ns.oid=cl.relnamespace where ($where) and cl.relkind='S' order by cl.relname";

        $result = $conn->execute($sql)->fetchAll();

        return $result;
    }

//******************Lo mismo que el de arriba pero con un criterio de busqueda***************

    public function getPgsqlSecuenciasDinamicWhereCriterio($gestor, $user, $pass, $ipgestorbd, $namebd, $where, $criterio, $puerto) {
        $secuencias = array();
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$namebd", 'information_schema');

        try {

            $secuencias = $this->getPgsqlSecuenciasBySchemaDinamicWhereCriterio($conn, $where, $criterio);
        } catch (Exception $e) {

            $PgSQL_respuesta = $e->getMessage();

            $dm->closeConnection($conn);
            $dm->setCurrentConnection($nameCurrentConn);

            return array("connectioerror" => $PgSQL_respuesta);
        }

        $dm->closeConnection($conn);
        $dm->setCurrentConnection($nameCurrentConn);

        return $secuencias;
    }

    public function getPgsqlSecuenciasBySchemaDinamicWhereCriterio($conn, $where, $criterio) {
        $sql = "select cl.relname, ns.nspname from Pg_Class cl inner join Pg_namespace ns ON ns.oid=cl.relnamespace where ($where) and cl.relname like $criterio ESCAPE '!' and cl.relkind='S' order by cl.relname";

        $result = $conn->execute($sql)->fetchAll();

        return $result;
    }

    public function getPgsqlFuncionesDinamicWhere($gestor, $user, $pass, $ipgestorbd, $namebd, $where, $puerto) {
        $triggers = array();
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$namebd", 'information_schema');
        try {

            $funciones = $this->getPgsqlFuncionesBySchemaDinamicWhere($conn, $where);
        } catch (Exception $e) {

            $PgSQL_respuesta = $e->getMessage();

            $dm->closeConnection($conn);
            $dm->setCurrentConnection($nameCurrentConn);

            return array("connectioerror" => $PgSQL_respuesta);
        }

        $dm->closeConnection($conn);
        $dm->setCurrentConnection($nameCurrentConn);

        return $funciones;
    }

    public function getPgsqlFuncionesBySchemaDinamicWhere($conn, $where) {
        $sql = "select p.proname, ns.nspname from pg_proc p inner join pg_namespace ns ON ns.oid=p.pronamespace where $where order by p.proname";
        $result = $conn->execute($sql)->fetchAll();
        return $result;
    }

//*************************Lo mismo que el de arriba pero con un criterio******************
    public function getPgsqlFuncionesDinamicWhereCriterio($gestor, $user, $pass, $ipgestorbd, $namebd, $where, $criterio, $puerto) {
        $triggers = array();
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$namebd", 'information_schema');
        try {

            $funciones = $this->getPgsqlFuncionesBySchemaDinamicWhereCriterio($conn, $where, $criterio);
        } catch (Exception $e) {

            $PgSQL_respuesta = $e->getMessage();

            $dm->closeConnection($conn);
            $dm->setCurrentConnection($nameCurrentConn);

            return array("connectioerror" => $PgSQL_respuesta);
        }

        $dm->closeConnection($conn);
        $dm->setCurrentConnection($nameCurrentConn);

        return $funciones;
    }

    public function getPgsqlFuncionesBySchemaDinamicWhereCriterio($conn, $where, $criterio) {
        $sql = "select p.proname, ns.nspname from pg_proc p inner join pg_namespace ns ON ns.oid=p.pronamespace where ($where) and p.proname like $criterio ESCAPE '!' order by p.proname";
        $result = $conn->execute($sql)->fetchAll();
        return $result;
    }

//******************************Creacion del Rol en la Base de datos**************************************

    public function PgsqlVerificarDisponibilidadServidor($gestor, $user, $pass, $ipgestorbd, $namebd, $puerto) {
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        try {
            $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$namebd", 'information_schema');
            $conn->connect();
            $dm->closeConnection($conn);
            $dm->setCurrentConnection($nameCurrentConn);
            return 1;
        } catch (Exception $e) {
            $PgSQL_respuesta = $e->getMessage();
            $dm->closeConnection($conn);
            $dm->setCurrentConnection($nameCurrentConn);
            return $PgSQL_respuesta;
        }
    }

    public function PgsqlVerificarNombreRolBD($gestor, $user, $pass, $ipgestorbd, $namebd, $denominacion, $puerto) {
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$namebd", 'information_schema');
        $sql = "create role $denominacion";
        try {
            $conn->execute($sql);
            $sql = "drop role $denominacion";
            $conn->execute($sql);
            $dm->closeConnection($conn);
            $dm->setCurrentConnection($nameCurrentConn);
            return 1;
        } catch (Exception $e) {
            $PgSQL_respuesta = $e->getMessage();
            $dm->closeConnection($conn);
            $dm->setCurrentConnection($nameCurrentConn);
            $respuesta = " 7 ERROR:  role \"$denominacion\" already exists";
            if (strpos($PgSQL_respuesta, $respuesta)) {
                return 0;
            }
            return true;
        }

        $dm->closeConnection($conn);
        $dm->setCurrentConnection($nameCurrentConn);
    }

    public function ModificarRol($gestor, $user, $pass, $ipgestorbd, $namebd, $denominacion, $newname, $comment, $rename, $puerto) {

        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$namebd", 'information_schema');
        $sql = "ALTER ROLE $denominacion RENAME TO $newname;";
        $sqlcomment = "COMMENT ON ROLE $newname IS '$comment';";
        if ($rename)
            $conn->execute($sql);
        $conn->execute($sqlcomment);

        $dm->closeConnection($conn);
        $dm->setCurrentConnection($nameCurrentConn);
    }

    public function EliminarTodosLosPermisos($gestor, $user, $pass, $ipgestorbd, $namebd, $rolname, $puerto) {

        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$namebd", 'information_schema');
        $conn->execute("drop owned by $rolname cascade");
        $dm->closeConnection($conn);
        $dm->setCurrentConnection($nameCurrentConn);
    }

    public function EliminarRol($gestor, $user, $pass, $ipgestorbd, $namebd, $denominacion, $puerto) {

        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$namebd", 'information_schema');
        $sql = "drop role $denominacion";

        $conn->execute($sql);

        $dm->closeConnection($conn);
        $dm->setCurrentConnection($nameCurrentConn);
    }

    public function EliminarRolLogin($gestor, $user, $passC, $ipgestorbd, $namebd, $denominacion, $puerto) {

        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$passC@$ipgestorbd:$puerto/$namebd", 'information_schema');
        $sql = "drop role $denominacion";

        $conn->execute($sql);

        $dm->closeConnection($conn);
        $dm->setCurrentConnection($nameCurrentConn);
    }

    public function EjecutarCadenadeConsultasV2($gestor, $user, $pass, $ipgestorbd, $namebd, $puerto, $execute) {

        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$namebd", 'information_schema');
        $conn->exec($execute);
        $dm->closeConnection($conn);
        $dm->setCurrentConnection($nameCurrentConn);
    }

    public function CrearRolLogin($gestor, $user, $passC, $ipgestorbd, $namebd, $denominacion, $pass, $puerto) {

        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$passC@$ipgestorbd:$puerto/$namebd", 'information_schema');
        $sql = "create role " . $denominacion . " with login password '$pass'";
        $conn->execute($sql);
        $dm->closeConnection($conn);
        $dm->setCurrentConnection($nameCurrentConn);
    }

    public function ActivarDesactivarRole($ipgestorbd, $gestor, $puerto, $user, $passC, $rol, $IsActivated) {
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$passC@$ipgestorbd:$puerto/template1", 'information_schema');
        if ($IsActivated)
            $sql = "ALTER ROLE $rol LOGIN";
        else
            $sql = "ALTER ROLE $rol NOLOGIN";

        $conn->execute($sql);
        $dm->closeConnection($conn);
        $dm->setCurrentConnection($nameCurrentConn);
    }

    public function EjecutarCadenadeConsultas($conn, $exec) {
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection($conn, 'information_schema');
        $conn->exec($exec);
        $dm->closeConnection($conn);
        $dm->setCurrentConnection($nameCurrentConn);
    }

    public function AsignarRolLoginToGrupo($gestor, $user, $pass, $ipgestorbd, $namebd, $denominacion, $rol, $puerto) {

        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$namebd", 'information_schema');
        $sql = "GRANT $rol TO $denominacion";
        $conn->execute($sql);
        $dm->closeConnection($conn);
        $dm->setCurrentConnection($nameCurrentConn);
    }

    /**
     * Insertar Todos los roles en la base de datos 
     * que se relacionan con acciones que tienen permisos sobre
     * objetos de base de datos
     */
    public function InsertarRoles($tipo, $OldTipoConexion = 0) {

        $idaccionesRelacionadas = $this->AccionesRelacionadasConObjetosDeBaseDatos();

        $arregloObjetosPermisoOrdenado = $this->CrearPermisosSobreObjetosBD($idaccionesRelacionadas);

        $roles = SegRol::ObtenerRolesFromAcciones($idaccionesRelacionadas);

        $this->CrearRolesBD($arregloObjetosPermisoOrdenado, $roles, $tipo, $OldTipoConexion);
    }

    /**
     * Me devuelve todas los idacciones relacionadas con objetos, no importa
     * que pase a traves de los servicios
     */
    public function AccionesRelacionadasConObjetosDeBaseDatos() {
        $accionesDeObjetos = DatAccion::IDAccionesRelacionadasObjetosBD();
        $accionesDeServiciosObjetos = DatAccion::IDAccionesRelacionadasConServiciosObjetosBD();
        $Mezcla = array_merge($accionesDeObjetos, $accionesDeServiciosObjetos);
        $idaccionesRelacionadas = array();
        foreach ($Mezcla as $idaccion)
            $idaccionesRelacionadas[] = $idaccion['idaccion'];
        $accionesRelacionadas = array_unique($idaccionesRelacionadas);
        return $accionesRelacionadas;
    }

    /**
     * Crea un rol de base de datos, donde los parametros son
     * Un objetos de rol de la tabla,
     * el id de las acciones relacionadas con ese rol en un arreglo 
     * de la siguiente manera
     * Array($id1,$id2,..,$idn)
     * y el tipo de la conexion que esta vigente.
     * 
     */
    public function CrearUnRolBD($Rol, $idaccionesRelacionadas, $tipo) {

        $arregloObjetosPermisoOrdenado = $this->CrearPermisosSobreObjetosBD($idaccionesRelacionadas);

        $roles = $this->RolChangeToArrayEstructure($Rol, $idaccionesRelacionadas);

        $RolesXAcciones = $this->UnirRolesXAcciones($roles);

        $this->CrearRolBD($RolesXAcciones, $arregloObjetosPermisoOrdenado, $tipo, 0);
    }

    public function EliminarRolesBD($idroles, $NewTipoConexion) {
        $roles_servidores_gestores_bd = SegRolDatServidorDatGestor::getRoles_Servidores_Gestores_BDByIdsRoles($idroles);

        $ArrayOrganizado = $this->Unir__R_S_G_B_con_U_S_G($roles_servidores_gestores_bd, array(), $NewTipoConexion);

        $this->CrearConsultasEliminarRoles($ArrayOrganizado, $NewTipoConexion, 0, false);
        SegRolDatServidorDatGestor::CleanTableByIDRoles($idroles);
    }

    /**
     * Modifica un Rol de Bases de datos
     * pasandole el objeto del rol ,
     * el nuevo nombre el comentario y si se modifico o no el nombre.
     * 
     */
    public function ModificarRolAndPermisosInRol($rol_mod, $tipo, $newName, $comment, $nameModified, $accionesAdicionar, $accionesEliminar) {

        if (count($accionesAdicionar)) {
            $arregloObjetosPermisoOrdenado = $this->CrearPermisosSobreObjetosBD($accionesAdicionar);

            $roles = $this->RolChangeToArrayEstructure($rol_mod, $accionesAdicionar);

            $RolesXAcciones = $this->UnirRolesXAcciones($roles);

            $this->AdicionarPermisosModifyRol($RolesXAcciones, $arregloObjetosPermisoOrdenado, $tipo);
        }

        if (count($accionesEliminar)) {
            $arregloObjetosPermisoOrdenado = $this->CrearPermisosSobreObjetosBD($accionesEliminar, "REVOKE ");

            $roles = $this->RolChangeToArrayEstructure($rol_mod, $accionesEliminar);

            $RolesXAcciones = $this->UnirRolesXAcciones($roles);

            $this->AdicionarPermisosModifyRol($RolesXAcciones, $arregloObjetosPermisoOrdenado, $tipo, false);
        }

        if ($nameModified) {

            $denominacion = $rol_mod->denominacion;
            if ($tipo == 2) {
                $RSA = new ZendExt_RSA_Facade();
                $registry = Zend_Registry::getInstance();
                $dirfile = $registry->config->dir_aplication;
                $dirfile.= DIRECTORY_SEPARATOR . 'seguridad' . DIRECTORY_SEPARATOR . 'comun' . DIRECTORY_SEPARATOR . 'recursos' . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'securitypasswords.xml';
                $content = file_get_contents($dirfile);
                $content = $RSA->decrypt($content);
                $DOM_XML = new DOMDocument('1.0', 'UTF-8');
                $DOM_XML->loadXML($content);
                $Element = $this->getElementsByAttr($DOM_XML, 'name', "rol_$denominacion" . "_acaxia$tipo");
                $Element = $Element->item(0);
                $passWordUser = $Element->getAttribute('password');
                $passWordUser = $this->EncritarPass($tipo, "nada", $passWordUser);
                $Element->setAttribute('name', "rol_$newName" . "_acaxia$tipo");
                $this->renameElement($Element, "rol_$newName" . "_acaxia$tipo");
                $content = $DOM_XML->saveXML();
                $content = $RSA->encrypt($content);
                file_put_contents($dirfile, $content);
            }

            $roles_servidores_gestores_bd = SegRolDatServidorDatGestor::getRoles_Servidores_Gestores_BDByIdsRoles(array($rol_mod->idrol));
            $ArrayOrganizado = $this->Unir__R_S_G_B_con_U_S_G($roles_servidores_gestores_bd, array(), $tipo);
            $this->ModificarNombre_Role($ArrayOrganizado, $tipo, $newName, $comment, $passWordUser);
            if ($denominacion == $_SESSION['denominacion_transaction'])
                $_SESSION['denominacion_transaction'] = $newName;
        }



        $model = new SegRolDatServidorDatGestorModel();
        if ($nameModified) {
            $findObjts = SegRolDatServidorDatGestor::findBy($rol_mod->idrol);
            foreach ($findObjts as $findObjt) {
                $findObjt->denominacion = $newName;
                $model->modificar($findObjt);
            }
        }
    }

    private function renameElement($element, $newName) {
        $newElement = $element->ownerDocument->createElement($newName);
        $parentElement = $element->parentNode;
        $parentElement->insertBefore($newElement, $element);
        $childNodes = $element->childNodes;
        while ($childNodes->length > 0) {
            $newElement->appendChild($childNodes->item(0));
        }

        $attributes = $element->attributes;
        while ($attributes->length > 0) {
            $attribute = $attributes->item(0);
            $newElement->setAttributeNode($attribute);
        }
        $parentElement->removeChild($element);
    }

    public function Unir__R_S_G_B_con_U_S_G($roles_servidores_gestores_bd, $usuario_servidores_gestores, $OldTipoConexion) {
        $ArregloUnidoOrganizado = array();

        foreach ($roles_servidores_gestores_bd as $RSGB) {
            $ip = $RSGB['DatServidor']['ip'];
            $gestor = $RSGB['DatGestor']['gestor'];
            $puerto = $RSGB['DatGestor']['puerto'];
            $bd = $RSGB['DatBd']['denominacion'];
            $nombrerol = $RSGB['SegRolesbd']['nombrerol'];
            $passw = $RSGB['SegRolesbd']['passw'];
            $denominacion = "rol_" . $RSGB['denominacion'] . "_acaxia$OldTipoConexion";
            if ($ArregloUnidoOrganizado[$ip] == "") {
                $ArregloUnidoOrganizado[$ip][$gestor][$puerto][$bd] = array(
                    'rolconex' => $nombrerol,
                    'passconex' => $passw,
                    'roldelete' => array($denominacion => $denominacion)
                );
            } else {
                if ($ArregloUnidoOrganizado[$ip][$gestor] == "") {
                    $ArregloUnidoOrganizado[$ip][$gestor][$puerto][$bd] = array(
                        'rolconex' => $nombrerol,
                        'passconex' => $passw,
                        'roldelete' => array($denominacion => $denominacion)
                    );
                } else {
                    if ($ArregloUnidoOrganizado[$ip][$gestor][$puerto] == "") {
                        $ArregloUnidoOrganizado[$ip][$gestor][$puerto][$bd] = array(
                            'rolconex' => $nombrerol,
                            'passconex' => $passw,
                            'roldelete' => array($denominacion => $denominacion)
                        );
                    } else {
                        if ($ArregloUnidoOrganizado[$ip][$gestor][$puerto][$bd] == "") {
                            $ArregloUnidoOrganizado[$ip][$gestor][$puerto][$bd] = array(
                                'rolconex' => $nombrerol,
                                'passconex' => $passw,
                                'roldelete' => array($denominacion => $denominacion)
                            );
                        } else {
                            $ArregloUnidoOrganizado[$ip][$gestor][$puerto][$bd]['roldelete'][$denominacion] = $denominacion;
                        }
                    }
                }
            }
        }

        foreach ($usuario_servidores_gestores as $USG) {
            $ip = $USG['DatServidor']['ip'];
            $gestor = $USG['DatGestor']['gestor'];
            $puerto = $USG['DatGestor']['puerto'];
            $denominacion = "usuario_" . $USG['SegUsuario']['nombreusuario'] . "_acaxia$OldTipoConexion";
            $ArregloUnidoOrganizado[$ip][$gestor][$puerto]['usuario_deleteNoBD'][$denominacion] = $denominacion;
        }


        return $ArregloUnidoOrganizado;
    }

    public function CrearConsultasEliminarRoles($ArrayOrganizado, $NewTipoConexion, $OldTipoConexion, $todos = true) {
        $RSA = new ZendExt_RSA_Facade();
        $user = "";
        $pass = "";
        $excecRevocarPermisos = "";
        if ($todos) {
            if (($OldTipoConexion == 2 || $OldTipoConexion == 3) && ($NewTipoConexion != 2 && $NewTipoConexion != 3))
                SegRolDatServidorDatGestor::CleanTable();

            if ($OldTipoConexion != 2 && $NewTipoConexion != 3)
                SegUsuarioDatServidorDatGestor::CleanTable();
        }
        foreach ($ArrayOrganizado as $ip => $ArrayGestores) {
            foreach ($ArrayGestores as $gestor => $ArrayPuertos) {
                foreach ($ArrayPuertos as $puerto => $ArrayBDs) {
                    foreach ($ArrayBDs as $bd => $DatasRoles) {
                        if ($user == "") {
                            $user = $DatasRoles['rolconex'];
                            $pass = $RSA->decrypt($DatasRoles['passconex']);
                        }
                        if ($bd != "usuario_deleteNoBD") {
                            foreach ($DatasRoles['roldelete'] as $key => $rolname) {
                                $excecRevocarPermisos.="drop owned by $rolname;drop role $rolname;";
                            }
                            $conn = "$gestor://$user:$pass@$ip:$puerto/$bd";
                            $this->EjecutarCadenadeConsultas($conn, $excecRevocarPermisos);
                            $excecRevocarPermisos = "";
                        } else {
                            $excecRevocarPermisos = "";
                            foreach ($DatasRoles as $key => $Username) {
                                $excecRevocarPermisos.="drop role $Username;";
                            }

                            $conn = "$gestor://$user:$pass@$ip:$puerto/template1";
                            $this->EjecutarCadenadeConsultas($conn, $excecRevocarPermisos);
                            $excecRevocarPermisos = "";
                        }
                    }
                }
                $user = "";
            }
        }
    }

    /**
     * Pasando el rol y las acciones, adicionarle/eliminar los privilegios sobre el objeto de base de
     * datos al rol que estas acciones posean sobre ese objeto.
     */
    public function AdicionarEliminarPrivilegiosOverRole($Rol, $idaccionesRelacionadas, $tipo, $grant_revoke) {
        $arregloObjetosPermisoOrdenado = $this->CrearPermisosSobreObjetosBD($idaccionesRelacionadas, $grant_revoke);
        $roles = $this->RolChangeToArrayEstructure($Rol, $idaccionesRelacionadas);
        $RolesXAcciones = $this->UnirRolesXAcciones($roles);

        $this->EjecutarAdicionar_EliminarPermisosRoles($RolesXAcciones, $arregloObjetosPermisoOrdenado, $tipo);
    }

    /**
     * Crea los permisos GRANT REVOKE sobre los objetos
     * de base de datos a los cuales tienen privilegios las 
     * acciones pasadas por ids, por defecto GRANT
     */
    private function CrearPermisosSobreObjetosBD($IdsAcciones, $grant_revoke = "GRANT ") {

        $arregloObjetosPermiso = DatObjetobd::ObtenerPermisosXAcciones($IdsAcciones);
        $objetosFromServicios = DatObjetobd::ObjetosRelacionadosConServiciosFromAcciones($IdsAcciones);

        $arregloObjetosPermiso = $this->OrdenarObjetosAcciones($arregloObjetosPermiso);
        $arregloObjetosPermiso = $this->OrdenarObjetosAccionesServicios($arregloObjetosPermiso, $objetosFromServicios);

        $arregloObjetosPermiso = $this->UnirPermisosAcciones($arregloObjetosPermiso);

        $arregloObjetosPermiso = $this->CrearConsultasPermisos($arregloObjetosPermiso, $grant_revoke);
        $arregloObjetosPermisoOrdenado = array();
        $arregloObjetosPermisoOrdenado = $this->UnirXParametrosConexion($arregloObjetosPermiso);
        return $arregloObjetosPermisoOrdenado;
    }

    public function CrearPermisosSobreObjetosBDtoUpdateServices($IdAccion, $arrayIdServicios, $grant_revoke = "GRANT ") {
        $arregloObjetosPermiso = array();
        $objetosFromServicios = DatObjetobd::ObjetosRelacionadosConServiciosFromAccionesIdServicio($IdAccion, $arrayIdServicios);

        $arregloObjetosPermiso = $this->OrdenarObjetosAcciones($arregloObjetosPermiso);
        $arregloObjetosPermiso = $this->OrdenarObjetosAccionesServicios($arregloObjetosPermiso, $objetosFromServicios);

        $arregloObjetosPermiso = $this->UnirPermisosAcciones($arregloObjetosPermiso);

        $arregloObjetosPermiso = $this->CrearConsultasPermisos($arregloObjetosPermiso, $grant_revoke);
        $arregloObjetosPermisoOrdenado = array();
        $arregloObjetosPermisoOrdenado = $this->UnirXParametrosConexion($arregloObjetosPermiso);
        return $arregloObjetosPermisoOrdenado;
    }

    private function CrearRolesBD($arregloObjetosPermisoOrdenado, $roles, $tipo, $OldTipoConexion = 0) {
        $RolesXAcciones = $this->UnirRolesXAcciones($roles);
        $this->CrearRolBD($RolesXAcciones, $arregloObjetosPermisoOrdenado, $tipo, $OldTipoConexion);
    }

    private function CrearRolBD($RolesXAcciones, $arregloObjetosPermisoOrdenado, $tipo, $OldTipoConexion) {
        $execute = "";
        $RSA = new ZendExt_RSA_Facade();
        $rolExecuted = array();
        $rolCreated = array();
        $impar = true;
        $rol_serv_gest = array();
        $LastDenominacion = "";
        $conexionesCreateRoles = array();
        $servgestRolForUser = array();
        $UsuariosConRolAsignado = array();
        $ConsultasConexionCreateRolLogin = array();
        $listOfEsquemas = array();
        foreach ($arregloObjetosPermisoOrdenado as $ip => $Arrayservidor) {
            foreach ($Arrayservidor as $gestor => $Arraygestor) {
                foreach ($Arraygestor as $puerto => $Arraypuerto) {
                    foreach ($Arraypuerto as $bd => $Arraybd) {
                        foreach ($Arraybd as $user => $ArrayUser) {
                            $pass = $RSA->decrypt($ArrayUser['pass']);
//Obtener todos los roles de base de datos pertenecientes a la direccion actual
                            $dm = Doctrine_Manager::getInstance();
                            $nameCurrentConn = $dm->getCurrentConnection()->getName();
                            $conn = $dm->openConnection("$gestor://$user:$pass@$ip:$puerto/$bd", 'information_schema');
                            $rolesEnBD = PgAuthid::getRoles($conn);
                            $rolesEnBD = $this->InvertirAtributosArrayRoles($rolesEnBD->toArray());
                            $dm->closeConnection($conn);
                            $dm->setCurrentConnection($nameCurrentConn);

                            foreach ($ArrayUser as $IdObj => $ArrayObjeto) {
                                if ($IdObj != "pass") {
                                    foreach ($ArrayObjeto['accion_privilegio'] as $Idacc => $accion) {
                                        foreach ($RolesXAcciones[$Idacc] as $denominacion => $descripcion) {
                                            if ($impar) {
                                                $LastDenominacion = $denominacion;
                                                if ($rolExecuted[$IdObj][$denominacion] == "") {
                                                    if ($rolCreated[$denominacion] == "") {
                                                        /* para verificar si el rol existe en la base de datos, 
                                                         * podemos tambien mandar a crearlo y capturar la excepcion
                                                         * que es lanzada en el caso de existir.
                                                         */
                                                        if ($rolesEnBD["rol_$denominacion" . "_acaxia$tipo"] == "rol_$denominacion" . "_acaxia$tipo") {
                                                            throw new ZendExt_Exception('SEG018');
                                                        }

                                                        $createRoles = "create role rol_$denominacion" . "_acaxia$tipo";
                                                        if ($tipo == 2) {
                                                            $password = $this->Encrypt($tipo, "rol_$denominacion" . "_acaxia$tipo");
                                                            $createRoles.=" with login password '$password'";
                                                        }
                                                        $createRoles.=";";
                                                        $execute.=$createRoles;
                                                        $execute.="COMMENT ON ROLE rol_$denominacion" . "_acaxia$tipo" . " IS '$descripcion';";
                                                        $rolCreated[$denominacion] = $denominacion;
                                                    }
                                                    $esquema = $ArrayObjeto['esquema'];
                                                    if ($listOfEsquemas[$ip . $gestor . $puerto . $bd . $esquema . $denominacion] == "") {
                                                        $listOfEsquemas[$ip . $gestor . $puerto . $bd . $esquema . $denominacion] = $esquema;
                                                        $execute.=$ArrayObjeto['esquema_execute'] . "rol_$denominacion" . "_acaxia$tipo" . ";";
                                                    }
                                                    $execute.=$ArrayObjeto['consulta'] . "rol_$denominacion" . "_acaxia$tipo" . ";";
                                                    $rolExecuted[$IdObj][$denominacion] = true;
                                                }
                                            } else {
                                                $ParamsRolServGest = array();
                                                $ParamsRolServGest['idrol'] = $descripcion;
                                                $ParamsRolServGest['idservidor'] = $ArrayObjeto['idservidor'];
                                                $ParamsRolServGest['idgestor'] = $ArrayObjeto['idgestor'];
                                                $ParamsRolServGest['idbd'] = $ArrayObjeto['idbd'];
                                                $ParamsRolServGest['idrolbd'] = $ArrayObjeto['idrol'];
                                                $ParamsRolServGest['denominacion'] = $LastDenominacion;
                                                $ParamsRolServGest['idrol'] . $ParamsRolServGest['idservidor'] .
                                                        $keyRolServGest = $ParamsRolServGest['idgestor'] . $ParamsRolServGest['idbd'] . $ParamsRolServGest['idrolbd'] .
                                                        $ParamsRolServGest['denominacion'];
                                                if ($rol_serv_gest[$keyRolServGest] == "")
                                                    $rol_serv_gest[$keyRolServGest] = $ParamsRolServGest;
                                                if ($tipo == 3) {
                                                    $connex = "$gestor://$user:$pass@$ip:$puerto/template1";
                                                    if ($UsuariosConRolAsignado[$descripcion] == "") {
                                                        $UsuariosConRolAsignado[$descripcion] = DatEntidadSegUsuarioSegRol::CargarUsuarioXIdRolV2($descripcion);

                                                        foreach ($UsuariosConRolAsignado[$descripcion] as $Users) {

                                                            $nombreUsuario = $Users['SegUsuario']['nombreusuario'];
                                                            $nombreUsuario = "usuario_$nombreUsuario" . "_acaxia$tipo";

                                                            $nombreRol = "rol_$LastDenominacion" . "_acaxia$tipo";

                                                            $idusuario = $Users['SegUsuario']['idusuario'];
                                                            $password = $Users['SegUsuario']['contrasenabd'];
                                                            $password = $this->Encrypt($tipo, $nombreUsuario, $password);
                                                            if ($rolesEnBD[$nombreUsuario] == $nombreUsuario) {
                                                                throw new ZendExt_Exception('SEG018');
                                                            }

                                                            if ($ConsultasConexionCreateRolLogin[$connex] == "") {

                                                                $consulta = "create role $nombreUsuario with login password '$password';";
                                                                $consulta.="GRANT $nombreRol TO $nombreUsuario;";

                                                                $ConsultasConexionCreateRolLogin[$connex]['consulta'] = $consulta;
                                                                $ConsultasConexionCreateRolLogin[$connex]['user_serv_gest'][$idusuario] = array(
                                                                    "idservidor" => $ArrayObjeto['idservidor'],
                                                                    "idgestor" => $ArrayObjeto['idgestor']
                                                                );
                                                            } else {
                                                                if ($ConsultasConexionCreateRolLogin[$connex]['user_serv_gest'][$idusuario] == "") {

                                                                    $consulta = $ConsultasConexionCreateRolLogin[$connex]['consulta'];
                                                                    $consulta.="create role $nombreUsuario with login password '$password';";
                                                                    $consulta.="GRANT $nombreRol TO $nombreUsuario;";
                                                                    $ConsultasConexionCreateRolLogin[$connex]['consulta'] = $consulta;

                                                                    $ConsultasConexionCreateRolLogin[$connex]['user_serv_gest'][$idusuario] = array(
                                                                        "idservidor" => $ArrayObjeto['idservidor'],
                                                                        "idgestor" => $ArrayObjeto['idgestor']
                                                                    );
                                                                } else {
                                                                    $consulta = $ConsultasConexionCreateRolLogin[$connex]['consulta'];
                                                                    if ($ConsultasConexionCreateRolLogin[$connex][$nombreRol . $nombreUsuario] == "") {
                                                                        $consulta.="GRANT $nombreRol TO $nombreUsuario;";
                                                                        $ConsultasConexionCreateRolLogin[$connex][$nombreRol . $nombreUsuario] = $nombreRol . $nombreUsuario;
                                                                    }
                                                                    $ConsultasConexionCreateRolLogin[$connex]['consulta'] = $consulta;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            $impar = !$impar;
                                        }
                                    }
                                    if ($conexionesCreateRoles[$ip . $gestor . $puerto . $bd] == "") {
                                        if ($execute != "") {
                                            $ConexCreate = array();
                                            $ConexCreate['ip'] = $ip;
                                            $ConexCreate['gestor'] = $gestor;
                                            $ConexCreate['puerto'] = $puerto;
                                            $ConexCreate['bd'] = $bd;
                                            $ConexCreate['user'] = $user;
                                            $ConexCreate['pass'] = $pass;
                                            $ConexCreate['execute'].=$execute;
                                            $conexionesCreateRoles[$ip . $gestor . $puerto . $bd] = $ConexCreate;
                                        }
                                    } else {
                                        $conexionesCreateRoles[$ip . $gestor . $puerto . $bd]['execute'].=$execute;
                                    }
                                    $execute = "";
                                    $rolExecuted = array();
                                }
                            }
                        }
                    }
                    $UsuariosConRolAsignado = array();
                }
            }
            $rolCreated = array();
        }

        foreach ($conexionesCreateRoles as $ParamasConexCreate) {
            $ip = $ParamasConexCreate['ip'];
            $gestor = $ParamasConexCreate['gestor'];
            $puerto = $ParamasConexCreate['puerto'];
            $bd = $ParamasConexCreate['bd'];
            $user = $ParamasConexCreate['user'];
            $pass = $ParamasConexCreate['pass'];
            $execute = $ParamasConexCreate['execute'];
            $this->EjecutarCadenadeConsultasV2($gestor, $user, $pass, $ip, $bd, $puerto, $execute);
        }

        if ($OldTipoConexion != 3 && $OldTipoConexion != 2)
            $this->RegistrarRolesBdCreados($rol_serv_gest);


        foreach ($ConsultasConexionCreateRolLogin as $connex => $executeValue) {
            foreach ($executeValue as $key => $execute) {
                if ($key == "consulta")
                    $this->EjecutarCadenadeConsultas($connex, $executeValue['consulta']);
                else if ($key == "user_serv_gest") {
                    foreach ($executeValue["user_serv_gest"] as $iduser => $value) {

                        $user_serv_gest['idusuario'] = $iduser;
                        $user_serv_gest['idservidor'] = $value['idservidor'];
                        $user_serv_gest['idgestor'] = $value['idgestor'];
                        $this->RegistrarUsuariosLoginCreados(array($user_serv_gest));
                    }
                }
            }
        }
    }

    private function InvertirAtributosArrayRoles($roles) {
        $result = array();
        foreach ($roles as $rol)
            $result[$rol['rolname']] = $rol['rolname'];
        return $result;
    }

    public function VerificarDisponibilidadServidorBD($conexiones) {

        foreach ($conexiones as $parametros) {
            $servidor = $parametros['servidor'];
            $gestor = $parametros['gestor'];
            $puerto = $parametros['puerto'];
            $nameBd = $parametros['bd'];
            $user = $parametros['user'];
            $passWord = $parametros['pass'];
            if ($this->VerifyIPOnline($servidor)) {
                if ($this->PgsqlVerificarDisponibilidadServidor($gestor, $user, $passWord, $servidor, $nameBd, $puerto) == 1) {
                    
                } else {
                    throw new ZendExt_Exception('SEG058');
                }
            } else {
                throw new ZendExt_Exception('SEG058');
            }
        }
    }

    public function VerifyIPOnline($ip) {
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

    private function RegistrarRolesBdCreados($rol_serv_gest) {
        $rol_serv_gestModel = new SegRolDatServidorDatGestorModel();
        foreach ($rol_serv_gest as $ParamsRolServGest) {

            $rol_serv_gest = new SegRolDatServidorDatGestor();
            $rol_serv_gest->idrol = $ParamsRolServGest['idrol'];
            $rol_serv_gest->idservidor = $ParamsRolServGest['idservidor'];
            $rol_serv_gest->idgestor = $ParamsRolServGest['idgestor'];
            $rol_serv_gest->idbd = $ParamsRolServGest['idbd'];
            $rol_serv_gest->idrolbd = $ParamsRolServGest['idrolbd'];
            $rol_serv_gest->denominacion = $ParamsRolServGest['denominacion'];

            $rol_serv_gestModel->insertar($rol_serv_gest);
        }
    }

    private function RegistrarUsuariosLoginCreados($Array_user_serv_gest) {
        $usuarioLoginModel = new SegUsuarioDatServidorDatGestorModel();
        foreach ($Array_user_serv_gest as $user_serv_gest) {
            $usuarioLogin = new SegUsuarioDatServidorDatGestor();
            $usuarioLogin->idusuario = $user_serv_gest['idusuario'];
            $usuarioLogin->idservidor = $user_serv_gest['idservidor'];
            $usuarioLogin->idgestor = $user_serv_gest['idgestor'];
            $usuarioLoginModel->Adicionar($usuarioLogin);
        }
    }

    private function OrdenarObjetosAcciones($arregloObjetosPermiso) {
        $result = array();
        foreach ($arregloObjetosPermiso as $ObjetosPermisos) {
            $idObjeto = $ObjetosPermisos['idobjetobd'];
            $result[$idObjeto]['idobjetobd'] = $idObjeto;
            $result[$idObjeto]['idobjeto'] = $ObjetosPermisos['idobjeto'];
            $result[$idObjeto]['idbd'] = $ObjetosPermisos['idbd'];
            $result[$idObjeto]['bd'] = $ObjetosPermisos['DatBd']['0']['bd'];
            $result[$idObjeto]['objeto'] = $ObjetosPermisos['objeto'];
            $result[$idObjeto]['idservidor'] = $ObjetosPermisos['idservidor'];
            $result[$idObjeto]['ip'] = $ObjetosPermisos['DatServidor']['0']['ip'];
            $result[$idObjeto]['idesquema'] = $ObjetosPermisos['esquema'];
            $result[$idObjeto]['esquema'] = $ObjetosPermisos['DatEsquema']['0']['esquema'];
            $result[$idObjeto]['idgestor'] = $ObjetosPermisos['idgestor'];
            $result[$idObjeto]['gestor'] = $ObjetosPermisos['DatGestor']['0']['gestor'];
            $result[$idObjeto]['puerto'] = $ObjetosPermisos['DatGestor']['0']['puerto'];
            $result[$idObjeto]['idrol'] = $ObjetosPermisos['idrol'];
            $result[$idObjeto]['user'] = $ObjetosPermisos['SegRolesbd']['0']['user'];
            $result[$idObjeto]['pass'] = $ObjetosPermisos['SegRolesbd']['0']['pass'];
            $result[$idObjeto]['accion_privilegio'] = array();
            $cant = 0;
            foreach ($ObjetosPermisos['DatAccionDatObjetobd'] as $AccionPrivilegio) {
                $result[$idObjeto]['accion_privilegio'][$cant]['idaccion'] = $AccionPrivilegio['idaccion'];
                $result[$idObjeto]['accion_privilegio'][$cant]['privilegios'] = $AccionPrivilegio['privilegios'];
                $cant++;
            }

            $cont++;
        }
        return $result;
    }

    private function OrdenarObjetosAccionesServicios($ArrayOrdenado, $objetosFromServicios) {
        $accion_privilegio = array();
        $cant = 0;
        foreach ($objetosFromServicios as $DatObjeto) {
            $idObjeto = $DatObjeto['idobjetobd'];

            if ($ArrayOrdenado[$idObjeto] == "") {
                $result['idobjetobd'] = $idObjeto;
                $result['idbd'] = $DatObjeto['idbd'];
                $result['bd'] = $DatObjeto['DatBd']['0']['bd'];
                $result['objeto'] = $DatObjeto['objeto'];
                $result['idservidor'] = $DatObjeto['idservidor'];
                $result['ip'] = $DatObjeto['DatServidor']['0']['ip'];
                $result['idesquema'] = $DatObjeto['esquema'];
                $result['esquema'] = $DatObjeto['DatEsquema']['0']['esquema'];
                $result['idgestor'] = $DatObjeto['idgestor'];
                $result['gestor'] = $DatObjeto['DatGestor']['0']['gestor'];
                $result['puerto'] = $DatObjeto['DatGestor']['0']['puerto'];
                $result['idrol'] = $DatObjeto['idrol'];
                $result['user'] = $DatObjeto['SegRolesbd']['0']['user'];
                $result['pass'] = $DatObjeto['SegRolesbd']['0']['pass'];
                $result['idobjeto'] = $DatObjeto['idobjeto'];
                $result['accion_privilegio'] = array();
                foreach ($DatObjeto['DatServicioObjetobd'] as $DatServObjBd) {
                    $privilegios = $DatServObjBd['privilegios'];
                    $cont = 0;
                    foreach ($DatServObjBd['DatServicioioc'][0]['DatAccionDatServicioioc'] as $AccServIoC) {
                        $idaccion = $AccServIoC['idaccion'];
                        $result['accion_privilegio'][$cont]['idaccion'] = $idaccion;
                        $result['accion_privilegio'][$cont]['privilegios'] = $privilegios;
                        $cont++;
                    }
                }
                $ArrayOrdenado[$idObjeto] = $result;
            } else {
                foreach ($DatObjeto['DatServicioObjetobd'] as $DatServObjBd) {
                    $privilegios = $DatServObjBd['privilegios'];
                    $cont = count($ArrayOrdenado[$idObjeto]['accion_privilegio']);
                    foreach ($DatServObjBd['DatServicioioc'][0]['DatAccionDatServicioioc'] as $AccServIoC) {
                        $idaccion = $AccServIoC['idaccion'];
                        $ArrayOrdenado[$idObjeto]['accion_privilegio'][$cont]['idaccion'] = $idaccion;
                        $ArrayOrdenado[$idObjeto]['accion_privilegio'][$cont]['privilegios'] = $privilegios;
                        $cont++;
                    }
                }
            }
        }
        return $ArrayOrdenado;
    }

    public function UnirPermisosAcciones($arregloObjetosPermiso) {
        foreach ($arregloObjetosPermiso as $key => $ObjetoPermiso) {
            $acciones_privilegios = $ObjetoPermiso['accion_privilegio'];
            $CadenaPermisos = "";
            $permisos = array();
            $acciones = array();
            foreach ($acciones_privilegios as $ap) {
                $CadenaPermisos.=$ap['privilegios'];
                if (!in_array($ap['idaccion'], $acciones)) {
                    $acciones[$ap['idaccion']] = $ap['idaccion'];
                }
            }
            for ($i = 0; $i < strlen($CadenaPermisos); $i++)
                $permisos[] = $CadenaPermisos[$i];
            $permisos = array_unique($permisos);
            $CadenaPermisos = implode($permisos);
            $arregloObjetosPermiso[$key]['permiso'] = $CadenaPermisos;
            $arregloObjetosPermiso[$key]['accion_privilegio'] = $acciones;
        }
        return $arregloObjetosPermiso;
    }

    public function CrearConsultasPermisos($arrayObjetosPermiso, $grant_revoque) {
        $RSA = new ZendExt_RSA_Facade();
        $para_de = "";
        if ($grant_revoque == "REVOKE ") {
            $para_de = "FROM";
        } else {
            $para_de = "TO";
        }
        $guardarVal = $grant_revoque;
        $ListOfPrivileges = array();
        $ListOfPrivileges['own'] = $grant_revoque . "ALL PRIVILEGES ON";
        $ListOfPrivileges['r'] = "SELECT";
        $ListOfPrivileges['a'] = "INSERT";
        $ListOfPrivileges['w'] = "UPDATE";
        $ListOfPrivileges['d'] = "DELETE";
        $ListOfPrivileges['x'] = "REFERENCES";
        $ListOfPrivileges['t'] = "TRIGGER";
        $ListOfPrivileges['X'] = "EXECUTE";
        $ListOfPrivileges['U'] = "USAGE";
        $conexiones = array();
        $listOfEsquemas = array();
        foreach ($arrayObjetosPermiso as $key => $InformationObject) {
            $gestor = $InformationObject['gestor'];
            $puerto = $InformationObject['puerto'];
            $servidor = $InformationObject['ip'];
            $nameBd = $InformationObject['bd'];
            $user = $InformationObject['user'];
            $pass = $RSA->decrypt($InformationObject['pass']);

            $parametrosConex['servidor'] = $servidor;
            $parametrosConex['gestor'] = $gestor;
            $parametrosConex['puerto'] = $puerto;
            $parametrosConex['bd'] = $nameBd;
            $parametrosConex['user'] = $user;
            $parametrosConex['pass'] = $pass;
            if (!in_array($parametrosConex, $conexiones)) {
                $conexiones[] = $parametrosConex;
                $this->VerificarDisponibilidadServidorBD(array($parametrosConex));
            }

            $NoAll = true;
            $grant_revoque = $guardarVal;
            $Permisos = $InformationObject['permiso'];
            $lengthPermisos = strlen($Permisos);
            $esquema = $InformationObject['esquema'];
            $objeto = $InformationObject['objeto'];
            for ($i = 0; $i < $lengthPermisos; $i++) {
                if ($InformationObject['idobjeto'] == 18 || $InformationObject['idobjeto'] == 19) {
                    if ($lengthPermisos == 6) {
                        $grant_revoque = $ListOfPrivileges['own'] . " TABLE " . $esquema . "." . '"' . $objeto . '"' . " $para_de ";
                        $NoAll = false;
                        break;
                    }
                } else if ($InformationObject['idobjeto'] == 20) {
                    if ($lengthPermisos == 3) {
                        $grant_revoque = $ListOfPrivileges['own'] . " SEQUENCE " . $esquema . "." . '"' . $objeto . '"' . " $para_de ";
                        $NoAll = false;
                        break;
                    }
                }
                if ($grant_revoque == "GRANT " || $grant_revoque == "REVOKE ") {
                    $grant_revoque.=$ListOfPrivileges[$Permisos[$i]];
                } else {
                    $grant_revoque.=", " . $ListOfPrivileges[$Permisos[$i]];
                }
            }
            if (($InformationObject['idobjeto'] == 18 || $InformationObject['idobjeto'] == 19) && $NoAll)
                $grant_revoque .=" ON TABLE " . $esquema . '.' . '"' . $objeto . '"' . " $para_de ";
            else if ($InformationObject['idobjeto'] == 20 && $NoAll)
                $grant_revoque .=" ON SEQUENCE " . $esquema . '.' . '"' . $objeto . '"' . " $para_de ";
            else if ($InformationObject['idobjeto'] == 21) {
                $parametros = PgProc::ObtenerParametrosdeFuncion($esquema, $objeto, $servidor, $gestor, $puerto, $nameBd, $user, $pass);
                $grant_revoque .=" ON FUNCTION " . $esquema . '.' . '"' . $objeto . '"' . "($parametros) $para_de ";
            }
            if ($listOfEsquemas[$servidor . $gestor . $puerto . $nameBd . $esquema] == "") {
                if ($para_de == "TO") {
                    $listOfEsquemas[$servidor . $gestor . $puerto . $nameBd . $esquema] = $esquema;
                    $arrayObjetosPermiso[$key]['esquema_execute'] = "GRANT USAGE ON SCHEMA $esquema TO ";
                } else {
                    $arrayObjetosPermiso[$key]['esquema_execute'] = "REVOKE ALL ON SCHEMA $esquema FROM ";
                }
            }
            $arrayObjetosPermiso[$key]['consulta'] = $grant_revoque;
        }

        return $arrayObjetosPermiso;
    }

    public function UnirXParametrosConexion($arregloObjetosPermiso) {
        $RSA = new ZendExt_RSA_Facade();
        $ArregloOrdenado = array();
        foreach ($arregloObjetosPermiso as $key => $ObjetoPermisos) {
            $gestor = $ObjetoPermisos['gestor'];
            $puerto = $ObjetoPermisos['puerto'];
            $servidor = $ObjetoPermisos['ip'];
            $nameBd = $ObjetoPermisos['bd'];
            $user = $ObjetoPermisos['user'];
            $pass = $ObjetoPermisos['pass'];
            if ($ArregloOrdenado[$servidor] != "") {
                if ($ArregloOrdenado[$servidor][$gestor] != "") {
                    if ($ArregloOrdenado[$servidor][$gestor][$puerto] != "") {
                        if ($ArregloOrdenado[$servidor][$gestor][$puerto][$nameBd] != "") {
                            if ($ArregloOrdenado[$servidor][$gestor][$puerto][$nameBd][$user] != "") {
                                if ($ArregloOrdenado[$servidor][$gestor][$puerto][$nameBd][$user][$key] != "") {
                                    
                                } else {
                                    $ArregloOrdenado[$servidor][$gestor][$puerto][$nameBd][$user][$key] = array(
                                        'idobjetobd' => $ObjetoPermisos['idobjetobd'],
                                        'idbd' => $ObjetoPermisos['idbd'],
                                        'bd' => $ObjetoPermisos['bd'],
                                        'objeto' => $ObjetoPermisos['objeto'],
                                        'idservidor' => $ObjetoPermisos['idservidor'],
                                        'ip' => $ObjetoPermisos['ip'],
                                        'idesquema' => $ObjetoPermisos['idesquema'],
                                        'esquema' => $ObjetoPermisos['esquema'],
                                        'esquema_execute' => $ObjetoPermisos['esquema_execute'],
                                        'idgestor' => $ObjetoPermisos['idgestor'],
                                        'gestor' => $ObjetoPermisos['gestor'],
                                        'puerto' => $ObjetoPermisos['puerto'],
                                        'idrol' => $ObjetoPermisos['idrol'],
                                        'user' => $ObjetoPermisos['user'],
                                        'pass' => $ObjetoPermisos['pass'],
                                        'idobjeto' => $ObjetoPermisos['idobjeto'],
                                        'accion_privilegio' => $ObjetoPermisos['accion_privilegio'],
                                        'consulta' => $ObjetoPermisos['consulta']
                                    );
                                }
                            } else {
                                $ArregloOrdenado[$servidor][$gestor][$puerto][$nameBd][$user] = array(
                                    'pass' => $pass,
                                    $key => array(
                                        'idobjetobd' => $ObjetoPermisos['idobjetobd'],
                                        'idbd' => $ObjetoPermisos['idbd'],
                                        'bd' => $ObjetoPermisos['bd'],
                                        'objeto' => $ObjetoPermisos['objeto'],
                                        'idservidor' => $ObjetoPermisos['idservidor'],
                                        'ip' => $ObjetoPermisos['ip'],
                                        'idesquema' => $ObjetoPermisos['idesquema'],
                                        'esquema' => $ObjetoPermisos['esquema'],
                                        'esquema_execute' => $ObjetoPermisos['esquema_execute'],
                                        'idgestor' => $ObjetoPermisos['idgestor'],
                                        'gestor' => $ObjetoPermisos['gestor'],
                                        'puerto' => $ObjetoPermisos['puerto'],
                                        'idrol' => $ObjetoPermisos['idrol'],
                                        'user' => $ObjetoPermisos['user'],
                                        'pass' => $ObjetoPermisos['pass'],
                                        'idobjeto' => $ObjetoPermisos['idobjeto'],
                                        'accion_privilegio' => $ObjetoPermisos['accion_privilegio'],
                                        'consulta' => $ObjetoPermisos['consulta']
                                    )
                                );
                            }
                        } else {
                            $ArregloOrdenado[$servidor][$gestor][$puerto][$nameBd] = array(
                                $user => array(
                                    'pass' => $pass,
                                    $key => array(
                                        'idobjetobd' => $ObjetoPermisos['idobjetobd'],
                                        'idbd' => $ObjetoPermisos['idbd'],
                                        'bd' => $ObjetoPermisos['bd'],
                                        'objeto' => $ObjetoPermisos['objeto'],
                                        'idservidor' => $ObjetoPermisos['idservidor'],
                                        'ip' => $ObjetoPermisos['ip'],
                                        'idesquema' => $ObjetoPermisos['idesquema'],
                                        'esquema' => $ObjetoPermisos['esquema'],
                                        'esquema_execute' => $ObjetoPermisos['esquema_execute'],
                                        'idgestor' => $ObjetoPermisos['idgestor'],
                                        'gestor' => $ObjetoPermisos['gestor'],
                                        'puerto' => $ObjetoPermisos['puerto'],
                                        'idrol' => $ObjetoPermisos['idrol'],
                                        'user' => $ObjetoPermisos['user'],
                                        'pass' => $ObjetoPermisos['pass'],
                                        'idobjeto' => $ObjetoPermisos['idobjeto'],
                                        'accion_privilegio' => $ObjetoPermisos['accion_privilegio'],
                                        'consulta' => $ObjetoPermisos['consulta']
                                    )
                                )
                            );
                        }
                    } else {
                        $ArregloOrdenado[$servidor][$gestor][$puerto] = array(
                            $nameBd => array(
                                $user => array(
                                    'pass' => $pass,
                                    $key => array(
                                        'idobjetobd' => $ObjetoPermisos['idobjetobd'],
                                        'idbd' => $ObjetoPermisos['idbd'],
                                        'bd' => $ObjetoPermisos['bd'],
                                        'objeto' => $ObjetoPermisos['objeto'],
                                        'idservidor' => $ObjetoPermisos['idservidor'],
                                        'ip' => $ObjetoPermisos['ip'],
                                        'idesquema' => $ObjetoPermisos['idesquema'],
                                        'esquema' => $ObjetoPermisos['esquema'],
                                        'esquema_execute' => $ObjetoPermisos['esquema_execute'],
                                        'idgestor' => $ObjetoPermisos['idgestor'],
                                        'gestor' => $ObjetoPermisos['gestor'],
                                        'puerto' => $ObjetoPermisos['puerto'],
                                        'idrol' => $ObjetoPermisos['idrol'],
                                        'user' => $ObjetoPermisos['user'],
                                        'pass' => $ObjetoPermisos['pass'],
                                        'idobjeto' => $ObjetoPermisos['idobjeto'],
                                        'accion_privilegio' => $ObjetoPermisos['accion_privilegio'],
                                        'consulta' => $ObjetoPermisos['consulta']
                                    )
                                )
                            )
                        );
                    }
                } else {
                    $ArregloOrdenado[$servidor][$gestor] = array(
                        $puerto => array(
                            $nameBd => array(
                                $user => array(
                                    'pass' => $pass,
                                    $key => array(
                                        'idobjetobd' => $ObjetoPermisos['idobjetobd'],
                                        'idbd' => $ObjetoPermisos['idbd'],
                                        'bd' => $ObjetoPermisos['bd'],
                                        'objeto' => $ObjetoPermisos['objeto'],
                                        'idservidor' => $ObjetoPermisos['idservidor'],
                                        'ip' => $ObjetoPermisos['ip'],
                                        'idesquema' => $ObjetoPermisos['idesquema'],
                                        'esquema' => $ObjetoPermisos['esquema'],
                                        'esquema_execute' => $ObjetoPermisos['esquema_execute'],
                                        'idgestor' => $ObjetoPermisos['idgestor'],
                                        'gestor' => $ObjetoPermisos['gestor'],
                                        'puerto' => $ObjetoPermisos['puerto'],
                                        'idrol' => $ObjetoPermisos['idrol'],
                                        'user' => $ObjetoPermisos['user'],
                                        'pass' => $ObjetoPermisos['pass'],
                                        'idobjeto' => $ObjetoPermisos['idobjeto'],
                                        'accion_privilegio' => $ObjetoPermisos['accion_privilegio'],
                                        'consulta' => $ObjetoPermisos['consulta']
                                    )
                                )
                            )
                        )
                    );
                }
            } else {
                $ArregloOrdenado[$servidor] = array(
                    $gestor => array(
                        $puerto => array(
                            $nameBd => array(
                                $user => array(
                                    'pass' => $pass,
                                    $key => array(
                                        'idobjetobd' => $ObjetoPermisos['idobjetobd'],
                                        'idbd' => $ObjetoPermisos['idbd'],
                                        'bd' => $ObjetoPermisos['bd'],
                                        'objeto' => $ObjetoPermisos['objeto'],
                                        'idservidor' => $ObjetoPermisos['idservidor'],
                                        'ip' => $ObjetoPermisos['ip'],
                                        'idesquema' => $ObjetoPermisos['idesquema'],
                                        'esquema' => $ObjetoPermisos['esquema'],
                                        'esquema_execute' => $ObjetoPermisos['esquema_execute'],
                                        'idgestor' => $ObjetoPermisos['idgestor'],
                                        'gestor' => $ObjetoPermisos['gestor'],
                                        'puerto' => $ObjetoPermisos['puerto'],
                                        'idrol' => $ObjetoPermisos['idrol'],
                                        'user' => $ObjetoPermisos['user'],
                                        'pass' => $ObjetoPermisos['pass'],
                                        'idobjeto' => $ObjetoPermisos['idobjeto'],
                                        'accion_privilegio' => $ObjetoPermisos['accion_privilegio'],
                                        'consulta' => $ObjetoPermisos['consulta']
                                    )
                                )
                            )
                        )
                    )
                );
            }
        }

        return $ArregloOrdenado;
    }

    public function UnirRolesXAcciones($roles) {
        $rolesXacciones = array();
        foreach ($roles as $rol) {
            foreach ($rol['DatSistemaSegRolDatFuncionalidadDatAccion'] as $rol_accion) {
                $idaccion = $rol_accion['idaccion'];
                if ($rolesXacciones[$idaccion] == "") {
                    $rolesXacciones[$idaccion][$rol['denominacion']] = $rol['descripcion'];
                    $rolesXacciones[$idaccion]['1id_' . $rol['denominacion']] = $rol['idrol'];
                } else {
                    $rolesXacciones[$idaccion][$rol['denominacion']] = $rol['descripcion'];
                    $rolesXacciones[$idaccion]['1id_' . $rol['denominacion']] = $rol['idrol'];
                }
            }
        }
        return $rolesXacciones;
    }

    public function RolChangeToArrayEstructure($Rol, $idacciones) {
        $acciones = array();
        foreach ($idacciones as $id) {
            $acciones[] = array('idaccion' => $id);
        }
        $arrayRole = array();
        $arrayRole['idrol'] = $Rol->idrol;
        $arrayRole['denominacion'] = $Rol->denominacion;
        $arrayRole['descripcion'] = $Rol->descripcion;
        $arrayRole['DatSistemaSegRolDatFuncionalidadDatAccion'] = $acciones;
        return array($arrayRole);
    }

    private function ModificarNombre_Role($ArrayOrganizado, $tipo, $newName, $comment, $passWordUser) {
        $RSA = new ZendExt_RSA_Facade();
        $user = "";
        $pass = "";
        $excecRevocarPermisos = "";

        foreach ($ArrayOrganizado as $ip => $ArrayGestores) {
            foreach ($ArrayGestores as $gestor => $ArrayPuertos) {
                foreach ($ArrayPuertos as $puerto => $ArrayBDs) {
                    foreach ($ArrayBDs as $bd => $DatasRoles) {
                        if ($bd != "usuario_deleteNoBD") {
                            if ($user == "") {
                                $user = $DatasRoles['rolconex'];
                                $pass = $RSA->decrypt($DatasRoles['passconex']);
                            }

                            foreach ($DatasRoles['roldelete'] as $key => $rolname) {

                                $createRoles = "ALTER ROLE $rolname RENAME TO " . "rol_$newName" . "_acaxia$tipo;";
                                if ($tipo == 2) {
                                    $createRoles.="ALTER ROLE rol_$newName" . "_acaxia$tipo WITH LOGIN PASSWORD '$passWordUser';";
                                }
                                $createRoles.="COMMENT ON ROLE rol_$newName" . "_acaxia$tipo" . " IS '$comment';";
                            }
                            $conn = "$gestor://$user:$pass@$ip:$puerto/$bd";

                            $this->EjecutarCadenadeConsultas($conn, $createRoles);
                        }
                    }
                }
                $user = "";
            }
        }
    }

    private function AdicionarPermisosModifyRol($RolesXAcciones, $arregloObjetosPermisoOrdenado, $tipo, $QuitarEsquema = true) {
        $execute = "";
        $RSA = new ZendExt_RSA_Facade();
        $rolExecuted = array();
        $rolCreated = array();
        $impar = true;
        $CreatedRole = false;
        $rol_serv_gest = array();
        $LastDenominacion = "";
        $conexionesCreateRoles = array();
        $UsuariosConRolAsignado = array();
        $ConsultasConexionCreateRolLogin = array();
        $listOfEsquemas = array();
        foreach ($arregloObjetosPermisoOrdenado as $ip => $Arrayservidor) {
            foreach ($Arrayservidor as $gestor => $Arraygestor) {
                foreach ($Arraygestor as $puerto => $Arraypuerto) {
                    foreach ($Arraypuerto as $bd => $Arraybd) {
                        foreach ($Arraybd as $user => $ArrayUser) {

                            $pass = $RSA->decrypt($ArrayUser['pass']);
                            $dm = Doctrine_Manager::getInstance();
                            $nameCurrentConn = $dm->getCurrentConnection()->getName();
                            $conn = $dm->openConnection("$gestor://$user:$pass@$ip:$puerto/$bd", 'information_schema');
                            $rolesEnBD = PgAuthid::getRoles($conn);
                            $RoleArray = $rolesEnBD->toArray();
                            $rolesEnBD = $this->InvertirAtributosArrayRoles($RoleArray);
                            $dm->closeConnection($conn);
                            $dm->setCurrentConnection($nameCurrentConn);
                            foreach ($ArrayUser as $IdObj => $ArrayObjeto) {
                                if ($IdObj != "pass") {
                                    foreach ($ArrayObjeto['accion_privilegio'] as $Idacc => $accion) {
                                        foreach ($RolesXAcciones[$Idacc] as $denominacion => $descripcion) {
                                            if ($impar) {
                                                $LastDenominacion = $denominacion;
                                                if ($rolExecuted[$IdObj][$denominacion] == "") {
                                                    if ($rolCreated[$denominacion] == "") {

                                                        if (SegRolDatServidorDatGestor::Existe_rol_servidor_gestor($denominacion, $ArrayObjeto['idservidor'], $ArrayObjeto['idgestor']) == 0) {
                                                            if ($rolesEnBD["rol_$denominacion" . "_acaxia$tipo"] == "rol_$denominacion" . "_acaxia$tipo") {
                                                                throw new ZendExt_Exception('SEG018');
                                                                $createRoles = "create role rol_$denominacion" . "_acaxia$tipo";
                                                                if ($tipo == 2) {
                                                                    $password = $this->Encrypt($tipo, "rol_$denominacion" . "_acaxia$tipo");
                                                                    $createRoles.=" with login password '$password'";
                                                                }
                                                                $createRoles.=";";
                                                                $CreatedRole = true;
                                                            }
                                                        }
                                                        $createRoles.="COMMENT ON ROLE rol_$denominacion" . "_acaxia$tipo" . " IS '$descripcion';";
                                                        $execute.=$createRoles;
                                                        $rolCreated[$denominacion] = $denominacion;
                                                    }
                                                    $esquema = $ArrayObjeto['esquema'];
                                                    if ($listOfEsquemas[$ip . $gestor . $puerto . $bd . $esquema . $denominacion] == "" && $QuitarEsquema) {
                                                        $listOfEsquemas[$ip . $gestor . $puerto . $bd . $esquema . $denominacion] = $esquema;
                                                        $execute.=$ArrayObjeto['esquema_execute'] . "rol_$denominacion" . "_acaxia$tipo" . ";";
                                                    }
                                                    $execute.=$ArrayObjeto['consulta'] . " rol_$denominacion" . "_acaxia$tipo" . ";";
                                                    $rolExecuted[$IdObj][$denominacion] = true;
                                                }
                                            } else if ($CreatedRole) {

                                                $ParamsRolServGest = array();
                                                $ParamsRolServGest['idrol'] = $descripcion;
                                                $ParamsRolServGest['idservidor'] = $ArrayObjeto['idservidor'];
                                                $ParamsRolServGest['idgestor'] = $ArrayObjeto['idgestor'];
                                                $ParamsRolServGest['idbd'] = $ArrayObjeto['idbd'];
                                                $ParamsRolServGest['idrolbd'] = $ArrayObjeto['idrol'];
                                                $ParamsRolServGest['denominacion'] = $LastDenominacion;
                                                $ParamsRolServGest['idrol'] . $ParamsRolServGest['idservidor'] .
                                                        $keyRolServGest = $ParamsRolServGest['idgestor'] . $ParamsRolServGest['idbd'] . $ParamsRolServGest['idrolbd'] .
                                                        $ParamsRolServGest['denominacion'];
                                                if ($rol_serv_gest[$keyRolServGest] == "")
                                                    $rol_serv_gest[$keyRolServGest] = $ParamsRolServGest;
                                                if ($tipo == 3) {
                                                    $connex = "$gestor://$user:$pass@$ip:$puerto/template1";
                                                    if ($UsuariosConRolAsignado[$descripcion] == "") {
                                                        $UsuariosConRolAsignado[$descripcion] = DatEntidadSegUsuarioSegRol::CargarUsuarioXIdRolV2($descripcion);

                                                        foreach ($UsuariosConRolAsignado[$descripcion] as $Users) {

                                                            $nombreUsuario = $Users['SegUsuario']['nombreusuario'];
                                                            $nombreUsuario = "usuario_$nombreUsuario" . "_acaxia$tipo";

                                                            $nombreRol = "rol_$LastDenominacion" . "_acaxia$tipo";

                                                            $idusuario = $Users['SegUsuario']['idusuario'];
                                                            $password = $Users['SegUsuario']['contrasenabd'];

                                                            if ($rolesEnBD[$nombreUsuario] == $nombreUsuario) {
                                                                throw new ZendExt_Exception('SEG018');
                                                            }
                                                            $password = $this->Encrypt($tipo, $nombreUsuario, $password);
                                                            if ($ConsultasConexionCreateRolLogin[$connex] == "") {

                                                                $consulta = "create role $nombreUsuario with login password '$password';";
                                                                $consulta.="GRANT $nombreRol TO $nombreUsuario;";

                                                                $ConsultasConexionCreateRolLogin[$connex]['consulta'] = $consulta;
                                                                $ConsultasConexionCreateRolLogin[$connex]['user_serv_gest'][$idusuario] = array(
                                                                    "idservidor" => $ArrayObjeto['idservidor'],
                                                                    "idgestor" => $ArrayObjeto['idgestor']
                                                                );
                                                            } else {
                                                                if ($ConsultasConexionCreateRolLogin[$connex]['user_serv_gest'][$idusuario] == "") {

                                                                    $consulta = $ConsultasConexionCreateRolLogin[$connex]['consulta'];
                                                                    $consulta.="create role $nombreUsuario with login password '$password';";
                                                                    $consulta.="GRANT $nombreRol TO $nombreUsuario;";
                                                                    $ConsultasConexionCreateRolLogin[$connex]['consulta'] = $consulta;

                                                                    $ConsultasConexionCreateRolLogin[$connex]['user_serv_gest'][$idusuario] = array(
                                                                        "idservidor" => $ArrayObjeto['idservidor'],
                                                                        "idgestor" => $ArrayObjeto['idgestor']
                                                                    );
                                                                } else {
                                                                    $consulta = $ConsultasConexionCreateRolLogin[$connex]['consulta'];
                                                                    if ($ConsultasConexionCreateRolLogin[$connex][$nombreRol . $nombreUsuario] == "") {
                                                                        $consulta.="GRANT $nombreRol TO $nombreUsuario;";
                                                                        $ConsultasConexionCreateRolLogin[$connex][$nombreRol . $nombreUsuario] = $nombreRol . $nombreUsuario;
                                                                    }
                                                                    $ConsultasConexionCreateRolLogin[$connex]['consulta'] = $consulta;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                $CreatedRole = false;
                                            }
                                            $impar = !$impar;
                                        }
                                    }
                                    if ($conexionesCreateRoles[$ip . $gestor . $puerto . $bd] == "") {
                                        $ConexCreate = array();
                                        $ConexCreate['ip'] = $ip;
                                        $ConexCreate['gestor'] = $gestor;
                                        $ConexCreate['puerto'] = $puerto;
                                        $ConexCreate['bd'] = $bd;
                                        $ConexCreate['user'] = $user;
                                        $ConexCreate['pass'] = $pass;
                                        $ConexCreate['execute'].=$execute;
                                        $conexionesCreateRoles[$ip . $gestor . $puerto . $bd] = $ConexCreate;
                                    } else {
                                        $conexionesCreateRoles[$ip . $gestor . $puerto . $bd]['execute'].=$execute;
                                    }
                                    $execute = "";
                                    $rolExecuted = array();
                                }
                            }
                        }
                    }
                }
            }
            $rolCreated = array();
        }

        foreach ($conexionesCreateRoles as $ParamasConexCreate) {
            $ip = $ParamasConexCreate['ip'];
            $gestor = $ParamasConexCreate['gestor'];
            $puerto = $ParamasConexCreate['puerto'];
            $bd = $ParamasConexCreate['bd'];
            $user = $ParamasConexCreate['user'];
            $pass = $ParamasConexCreate['pass'];
            $execute = $ParamasConexCreate['execute'];
            $this->EjecutarCadenadeConsultasV2($gestor, $user, $pass, $ip, $bd, $puerto, $execute);
        }

        $this->RegistrarRolesBdCreados($rol_serv_gest);

        foreach ($ConsultasConexionCreateRolLogin as $connex => $executeValue) {
            foreach ($executeValue as $key => $execute) {
                if ($key == "consulta")
                    $this->EjecutarCadenadeConsultas($connex, $executeValue['consulta']);
                else if ($key == "user_serv_gest") {
                    foreach ($executeValue["user_serv_gest"] as $iduser => $value) {

                        $user_serv_gest['idusuario'] = $iduser;
                        $user_serv_gest['idservidor'] = $value['idservidor'];
                        $user_serv_gest['idgestor'] = $value['idgestor'];
                        $this->RegistrarUsuariosLoginCreados(array($user_serv_gest));
                    }
                }
            }
        }
    }

    public function EjecutarAdicionar_EliminarPermisosRoles($RolesXAcciones, $arregloObjetosPermisoOrdenado, $tipo, $QuitarPonerEsquema = true) {
        $execute = "";
        $RSA = new ZendExt_RSA_Facade();
        $rolExecuted = array();
        $rolCreated = array();
        $impar = true;
        $rol_serv_gest = array();
        $LastDenominacion = "";
        $conexionesCreateRoles = array();
        $servgestRolForUser = array();
        $UsuariosConRolAsignado = array();
        $ConsultasConexionCreateRolLogin = array();
        $WasCreated = false;
        $listOfEsquemas = array();
        foreach ($arregloObjetosPermisoOrdenado as $ip => $Arrayservidor) {
            foreach ($Arrayservidor as $gestor => $Arraygestor) {
                foreach ($Arraygestor as $puerto => $Arraypuerto) {
                    foreach ($Arraypuerto as $bd => $Arraybd) {
                        foreach ($Arraybd as $user => $ArrayUser) {
                            $pass = $RSA->decrypt($ArrayUser['pass']);

                            $dm = Doctrine_Manager::getInstance();
                            $nameCurrentConn = $dm->getCurrentConnection()->getName();
                            $conn = $dm->openConnection("$gestor://$user:$pass@$ip:$puerto/$bd", 'information_schema');
                            $rolesEnBD = PgAuthid::getRoles($conn);
                            $rolesEnBD = $this->InvertirAtributosArrayRoles($rolesEnBD->toArray());
                            $dm->closeConnection($conn);
                            $dm->setCurrentConnection($nameCurrentConn);

                            foreach ($ArrayUser as $IdObj => $ArrayObjeto) {
                                if ($IdObj != "pass") {
                                    foreach ($ArrayObjeto['accion_privilegio'] as $Idacc => $accion) {
                                        foreach ($RolesXAcciones[$Idacc] as $denominacion => $descripcion) {
                                            if ($impar) {
                                                $LastDenominacion = $denominacion;
                                                if ($rolExecuted[$IdObj][$denominacion] == "") {
                                                    if ($rolCreated[$denominacion] == "") {

                                                        if ($rolesEnBD["rol_$denominacion" . "_acaxia$tipo"] != "rol_$denominacion" . "_acaxia$tipo") {

                                                            $createRoles = "create role rol_$denominacion" . "_acaxia$tipo";
                                                            if ($tipo == 2) {
                                                                $password = $this->Encrypt($tipo, "rol_$denominacion" . "_acaxia$tipo");
                                                                $createRoles.=" with login password '$password'";
                                                            }
                                                            $createRoles.=";";
                                                            $execute.=$createRoles;
                                                            $rolCreated[$denominacion] = $denominacion;
                                                            $WasCreated = true;
                                                        } else {
                                                            if (SegRolDatServidorDatGestor::Existe_rol_servidor_gestor($denominacion, $ArrayObjeto['idservidor'], $ArrayObjeto['idgestor']) == 0) {
                                                                throw new ZendExt_Exception('SEG018');
                                                            }
                                                        }
                                                    }
                                                    $esquema = $ArrayObjeto['esquema'];
                                                    if ($listOfEsquemas[$ip . $gestor . $puerto . $bd . $esquema . $denominacion] == "" && $QuitarPonerEsquema) {
                                                        $listOfEsquemas[$ip . $gestor . $puerto . $bd . $esquema . $denominacion] = $esquema;
                                                        $execute.=$ArrayObjeto['esquema_execute'] . "rol_$denominacion" . "_acaxia$tipo" . ";";
                                                    }
                                                    $execute.=$ArrayObjeto['consulta'] . "rol_$denominacion" . "_acaxia$tipo" . ";";
                                                    $rolExecuted[$IdObj][$denominacion] = true;
                                                }
                                            } else {
                                                if ($WasCreated) {
                                                    $ParamsRolServGest = array();
                                                    $ParamsRolServGest['idrol'] = $descripcion;
                                                    $ParamsRolServGest['idservidor'] = $ArrayObjeto['idservidor'];
                                                    $ParamsRolServGest['idgestor'] = $ArrayObjeto['idgestor'];
                                                    $ParamsRolServGest['idbd'] = $ArrayObjeto['idbd'];
                                                    $ParamsRolServGest['idrolbd'] = $ArrayObjeto['idrol'];
                                                    $ParamsRolServGest['denominacion'] = $LastDenominacion;
                                                    $ParamsRolServGest['idrol'] . $ParamsRolServGest['idservidor'] .
                                                            $keyRolServGest = $ParamsRolServGest['idgestor'] . $ParamsRolServGest['idbd'] . $ParamsRolServGest['idrolbd'] .
                                                            $ParamsRolServGest['denominacion'];
                                                    if ($rol_serv_gest[$keyRolServGest] == "")
                                                        $rol_serv_gest[$keyRolServGest] = $ParamsRolServGest;
                                                    $WasCreated = false;
                                                }
                                                if ($tipo == 3) {
                                                    $connex = "$gestor://$user:$pass@$ip:$puerto/template1";
                                                    if ($UsuariosConRolAsignado[$descripcion] == "") {
                                                        $UsuariosConRolAsignado[$descripcion] = DatEntidadSegUsuarioSegRol::CargarUsuarioXIdRolV2($descripcion);

                                                        foreach ($UsuariosConRolAsignado[$descripcion] as $Users) {

                                                            $nombreUsuario = $Users['SegUsuario']['nombreusuario'];
                                                            $nombreUsuario = "usuario_$nombreUsuario" . "_acaxia$tipo";

                                                            $nombreRol = "rol_$LastDenominacion" . "_acaxia$tipo";

                                                            $idusuario = $Users['SegUsuario']['idusuario'];
                                                            $password = $Users['SegUsuario']['contrasenabd'];
                                                            $password = $this->Encrypt($tipo, $nombreUsuario, $password);
                                                            if ($rolesEnBD[$nombreUsuario] != $nombreUsuario) {

                                                                if ($ConsultasConexionCreateRolLogin[$connex] == "") {

                                                                    $consulta = "create role $nombreUsuario with login password '$password';";
                                                                    $consulta.="GRANT $nombreRol TO $nombreUsuario;";

                                                                    $ConsultasConexionCreateRolLogin[$connex]['consulta'] = $consulta;
                                                                    $ConsultasConexionCreateRolLogin[$connex]['user_serv_gest'][$idusuario] = array(
                                                                        "idservidor" => $ArrayObjeto['idservidor'],
                                                                        "idgestor" => $ArrayObjeto['idgestor']
                                                                    );
                                                                } else {
                                                                    if ($ConsultasConexionCreateRolLogin[$connex]['user_serv_gest'][$idusuario] == "") {

                                                                        $consulta = $ConsultasConexionCreateRolLogin[$connex]['consulta'];
                                                                        $consulta.="create role $nombreUsuario with login password '$password';";
                                                                        $consulta.="GRANT $nombreRol TO $nombreUsuario;";
                                                                        $ConsultasConexionCreateRolLogin[$connex]['consulta'] = $consulta;

                                                                        $ConsultasConexionCreateRolLogin[$connex]['user_serv_gest'][$idusuario] = array(
                                                                            "idservidor" => $ArrayObjeto['idservidor'],
                                                                            "idgestor" => $ArrayObjeto['idgestor']
                                                                        );
                                                                    } else {
                                                                        $consulta = $ConsultasConexionCreateRolLogin[$connex]['consulta'];
                                                                        if ($ConsultasConexionCreateRolLogin[$connex][$nombreRol . $nombreUsuario] == "") {
                                                                            $consulta.="GRANT $nombreRol TO $nombreUsuario;";
                                                                            $ConsultasConexionCreateRolLogin[$connex][$nombreRol . $nombreUsuario] = $nombreRol . $nombreUsuario;
                                                                        }
                                                                        $ConsultasConexionCreateRolLogin[$connex]['consulta'] = $consulta;
                                                                    }
                                                                }
                                                            } else if (SegUsuarioDatServidorDatGestor::Find($idusuario, $ArrayObjeto['idservidor'], $ArrayObjeto['idgestor']) == null) {
                                                                throw new ZendExt_Exception('SEG018');
                                                            } else {
                                                                $consulta = $ConsultasConexionCreateRolLogin[$connex]['consulta'];
                                                                $consulta.="GRANT $nombreRol TO $nombreUsuario;";
                                                                $ConsultasConexionCreateRolLogin[$connex]['consulta'] = $consulta;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            $impar = !$impar;
                                        }
                                    }
                                    if ($conexionesCreateRoles[$ip . $gestor . $puerto . $bd] == "") {
                                        $ConexCreate = array();
                                        $ConexCreate['ip'] = $ip;
                                        $ConexCreate['gestor'] = $gestor;
                                        $ConexCreate['puerto'] = $puerto;
                                        $ConexCreate['bd'] = $bd;
                                        $ConexCreate['user'] = $user;
                                        $ConexCreate['pass'] = $pass;
                                        $ConexCreate['execute'].=$execute;
                                        $conexionesCreateRoles[$ip . $gestor . $puerto . $bd] = $ConexCreate;
                                    } else {
                                        $conexionesCreateRoles[$ip . $gestor . $puerto . $bd]['execute'].=$execute;
                                    }

                                    $execute = "";
                                    $rolExecuted = array();
                                }
                            }
                        }
                    }
                }
            }
            $rolCreated = array();
        }
        foreach ($conexionesCreateRoles as $ParamasConexCreate) {
            $ip = $ParamasConexCreate['ip'];
            $gestor = $ParamasConexCreate['gestor'];
            $puerto = $ParamasConexCreate['puerto'];
            $bd = $ParamasConexCreate['bd'];
            $user = $ParamasConexCreate['user'];
            $pass = $ParamasConexCreate['pass'];
            $execute = $ParamasConexCreate['execute'];
            $this->EjecutarCadenadeConsultasV2($gestor, $user, $pass, $ip, $bd, $puerto, $execute);
        }

        if ($tipo == 3 || $tipo == 2)
            $this->RegistrarRolesBdCreados($rol_serv_gest);


        foreach ($ConsultasConexionCreateRolLogin as $connex => $executeValue) {
            foreach ($executeValue as $key => $execute) {
                if ($key == "consulta")
                    $this->EjecutarCadenadeConsultas($connex, $executeValue['consulta']);
                else if ($key == "user_serv_gest") {
                    foreach ($executeValue["user_serv_gest"] as $iduser => $value) {

                        $user_serv_gest['idusuario'] = $iduser;
                        $user_serv_gest['idservidor'] = $value['idservidor'];
                        $user_serv_gest['idgestor'] = $value['idgestor'];
                        $this->RegistrarUsuariosLoginCreados(array($user_serv_gest));
                    }
                }
            }
        }
    }

    public function Encrypt($tipoConex, $RoleName, $passWordUser = "") {
        $RSA = new ZendExt_RSA_Facade();
        if ($tipoConex == 3) {
            return $this->EncritarPass($tipoConex, $RoleName, $passWordUser);
        } else if ($tipoConex == 2) {
            $registry = Zend_Registry::getInstance();
            $dirfile = $registry->config->dir_aplication;
            $dirfile.=DIRECTORY_SEPARATOR . 'seguridad' . DIRECTORY_SEPARATOR . 'comun' . DIRECTORY_SEPARATOR . 'recursos' . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'securitypasswords.xml';
            $DOM_XML = new DOMDocument('1.0', 'UTF-8');
            $passWordUser = $this->GenerarCadena();
            if (file_exists($dirfile)) {
                $content = file_get_contents($dirfile);
                $content = $RSA->decrypt($content);
                $DOM_XML->loadXML($content);
                $Elements = $this->getElementsByAttr($DOM_XML, 'name', $RoleName);
                if ($Elements != false) {
                    $Element = $Elements->item(0);
                    $passWordUser = $Element->getAttribute('password');
                    return $this->EncritarPass($tipoConex, $RoleName, $passWordUser);
                } else {
                    $roleSON = $DOM_XML->createElement($RoleName);
                    $roleSON->setAttribute('name', $RoleName);
                    $roleSON->setAttribute('password', $passWordUser);
                    $root = $DOM_XML->getElementsByTagName('roles')->item(0);
                    $root->appendChild($roleSON);
                    $content = $DOM_XML->saveXML();
                    $content = $RSA->encrypt($content);
                    file_put_contents($dirfile, $content);
                    return $this->EncritarPass($tipoConex, $RoleName, $passWordUser);
                }
            } else {
                $rootNode = $DOM_XML->createElement("roles");
                $roleSON = $DOM_XML->createElement($RoleName);
                $roleSON->setAttribute('name', $RoleName);
                $roleSON->setAttribute('password', $passWordUser);
                $rootNode->appendChild($roleSON);
                $DOM_XML->appendChild($rootNode);
                $content = $DOM_XML->saveXML();
                $content = $RSA->encrypt($content);
                fopen($dirfile, 'x');
                file_put_contents($dirfile, $content);
                return $this->EncritarPass($tipoConex, $RoleName, $passWordUser);
            }
        }
    }

    private function getElementsByAttr($DOM, $nameAtrr, $value) {
        $xpath = new DOMXpath($DOM);
        $elements = $xpath->query("//*[@$nameAtrr='$value']");
        if ($elements->length > 0) {
            return $elements;
        }
        return false;
    }

    private function GenerarCadena($length = 50) {
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890|@#~$%()=^*+[]{}-_.&!?';
        $cadena = "";
        for ($index = 0; $index < $length; $index++) {
            $aleatorio = rand(0, strlen($caracteres) - 1);
            $cadena.=$caracteres[$aleatorio];
        }
        return $cadena;
    }

    public function EncritarPass($tipoConex, $RoleName, $passWordUser) {
        $RSA = new ZendExt_RSA_Facade();
        $CV = $this->KOI($passWordUser, 0);
        $CC = $this->KOI($passWordUser, 1);
        $CD = $this->KOI($passWordUser, 2);
        $valor = ($CC == 0 ? 1 : $CC) * ($CD == 0 ? 1 : $CD) * ($CV == 0 ? 1 : $CV);
        $passWordUser.=$passWordUser . $CC . $CD . $CE . $CV . $passWordUser;
        $passWordUser.=$valor;
        $passWordUser = md5($passWordUser);
        $passWordUser = "Acaxia2.2" . $tipoConex . $passWordUser;
        $passWordUser = md5($passWordUser);
        $passWordUser = $this->O9($passWordUser);
        $passWordUser = $RSA->encrypt($RSA->encrypt($passWordUser));
        $passWordUser = str_replace(' ', '', $passWordUser);

        return $passWordUser;
    }

    private function O9($K) {
        $LL = "";
        $T = 0;
        for ($i = 0; $i < strlen($K); $i++) {
            $c = $K[$i];
            $G = ord($c);
            $LL.=$G;
            $T+=$G;
        }
        return $T . $LL;
    }

    private function KOI($Y, $J) {
        $P = "aeiou";
        $UUI = "bcdfghjklmnñpqrstvwxyz";
        $TH = "0123456789";
        $KKOP = 0;
        for ($i = 0; $i < strlen($Y); $i++) {
            if ($J == 0) {
                if (strpos($P, $Y[$i]) !== false || strpos(strtoupper($P), $Y[$i]) !== false) {
                    $KKOP++;
                }
            } else
            if ($J == 1) {
                if (strpos($UUI, $Y[$i]) !== false || strpos(strtoupper($UUI), $Y[$i]) !== false) {
                    $KKOP++;
                }
            } else
            if ($J == 2) {
                if (strpos($TH, $Y[$i]) !== false) {
                    $KKOP++;
                }
            } else
            if ($J == 3) {
                if (strpos($TH, $Y[$i]) === false &&
                        strpos($UUI, $Y[$i]) === false && strpos(strtoupper($UUI), $Y[$i]) === false &&
                        strpos($P, $Y[$i]) === false && strpos(strtoupper($P), $Y[$i]) === false) {
                    $KKOP++;
                }
            }
        }
        return $KKOP;
    }

    /* ---------------Adicionados por Katia--------------- */
    /* Conexión a nivel de sistema */
    /* Funcionalidad que permite asignarle los permisos a un rol a todos los objetos 
     * pertenecientes al esquema al que se va a conectar */

    public function AsignarPermisosARolSobreObjetos($ipgestorbd, $gestor, $puerto, $user, $passC, $rol, $esquema, $bd) {
        $contador = 0;
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$passC@$ipgestorbd:$puerto/$bd", 'information_schema');
        while ($contador <= 2) {
            if ($contador == 0) {
                $sql = "GRANT ALL ON SCHEMA $esquema TO $rol";
            } elseif ($contador == 1) {
                $sql = "GRANT ALL ON ALL TABLES IN SCHEMA $esquema TO $rol";
            } elseif ($contador == 2) {
                $sql = "GRANT ALL ON ALL SEQUENCES IN SCHEMA $esquema TO $rol";
            }
            $conn->execute($sql);
            $dm->closeConnection($conn);
            $dm->setCurrentConnection($nameCurrentConn);
            $contador++;
        }
        $this->AsignarPermisosTraza($gestor, $user, $passC, $ipgestorbd, $bd, $puerto, $rol);
    }

    /* Funcionalidad que permite asignarle los permisos a todos los roles 
     * sobre las tablas que comienzan por his pertenecientes al esquema traza */

    private function AsignarPermisosTraza($gestor, $RolName, $RolPassw, $host, $bd, $port, $rol) {
        $where = "table_schema='mod_traza'";
        $tablasTraza = $this->getPgsqlTablasDinamicWhere($gestor, $RolName, $RolPassw, $host, $bd, $where, $port);
        $tablasHIS = array();
        $contador = 0;
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$RolName:$RolPassw@$host:$port/$bd", 'mod_traza');
        foreach ($tablasTraza as $tablas) {
            if (substr($tablas['table_name'], 0, 3) == 'his') {
                $tablasHIS[$contador] = $tablas['table_name'];
            }
            $contador++;
        }
        $opcion = 0;
        while ($opcion <= 2) {
            if ($opcion == 0) {
                $sql = "GRANT ALL ON SCHEMA mod_traza TO $rol";
            } elseif ($opcion == 1) {
                foreach ($tablasHIS as $arreglo) {
                    $sql = "GRANT ALL ON TABLE mod_traza.$arreglo TO $rol";
                    $conn->execute($sql);
                    $dm->closeConnection($conn);
                    $dm->setCurrentConnection($nameCurrentConn);
                }
            } elseif ($opcion == 2) {
                $sql = "GRANT ALL ON TABLE mod_traza.his_traza_idtraza_seq TO $rol";
            }
            $conn->execute($sql);
            $dm->closeConnection($conn);
            $dm->setCurrentConnection($nameCurrentConn);
            $opcion++;
        }
    }

    /* Funcionalidad que permite eliminarle los permisos a un rol a todos los objetos 
     * pertenecientes al esquema al que se va a conectar */

    public function EliminarPermisosARolSobreObjetos($ipgestorbd, $gestor, $puerto, $user, $passC, $esquemas, $bd, $roles) {
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$passC@$ipgestorbd:$puerto/$bd", 'pg_catalog');
        $contador = 0;
        foreach ($roles as $rol) {
            foreach ($esquemas as $lista) {
                $denominacion = $lista['denominacion'];
                while ($contador <= 2) {
                    if ($contador == 0) {
                        $sql = "REVOKE ALL PRIVILEGES ON SCHEMA $denominacion FROM $rol";
                    } elseif ($contador == 1) {
                        $sql = "REVOKE ALL PRIVILEGES ON ALL TABLES IN SCHEMA $denominacion FROM $rol";
                    } elseif ($contador == 2) {
                        $sql = "REVOKE ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA $denominacion FROM $rol";
                    }
                    $conn->execute($sql);
                    $dm->closeConnection($conn);
                    $dm->setCurrentConnection($nameCurrentConn);
                    $contador++;
                }
                $contador = 0;
            }
        }
    }

}
