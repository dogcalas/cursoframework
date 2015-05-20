<?php

class ZendExt_SqlServer {
    //Entrada configurada en /etc/freetds/freetds.conf
//    protected $server = 'mssql';
    protected $server;

    //Usuario
//    protected $username = 'abs';
    protected $username;

    //Contraseña
//    protected $password = 'amada123';
    protected $password;

    //Base de datos a la cual
//    protected $database = 'FINAN';
    protected $database;

    protected $conexion = null;

    static public function getInstance(){
        static $instance;
        if (!isset($instance))
            $instance = new self();
        return $instance;
    }

    private function Conectar(){
        $modulesconfig = ZendExt_FastResponse::getXML('modulesconfig');
        $bdconfig = $modulesconfig->connMSSQL;
        $this->server = $bdconfig['host'].':'.$bdconfig['port'];
        $this->username = $bdconfig['usuario'];
//        $RSA = new ZendExt_RSA();
        $this->password = $bdconfig['password'];//$RSA->decrypt ($bdconfig['password'], '85550694285145230823', '99809143352650341179');
//        $host = $bdconfig['host'];
//        $port = $bdconfig['port'];
        $this->database = $bdconfig['bd'];

        $this->conexion = mssql_connect($this->server, $this->username, $this->password);
        mssql_select_db($this->database, $this->conexion);
    }

    private function Desconectar(){
        mssql_close($this->conexion);
    }

    /**
     * Metodo para ejecutar un procedimiento de almacenado a una BD SQL Server.
     * El parametro $params debe tener el siguiente formato:
     * $params = array(
     *      'nombreParametro1' => array(
     *           'value' => 20142120,   //valor (si es de salida, aqui estara el valor, luego de la llamada)
     *           'type' => SQLVARCHAR,  //tipo de dato
     *           'is_output' => false,  //si es de salida
     *           'is_null' => false,    //si permite null
     *           'maxlen' => -1,        //longitud maxima
     *      )
     * )
     * @param string $nombreProc
     * @param array $params
     * @return array
     */
    public function EjecutarProcedimiento($nombreProc,$params = array()){
        $resultado = array();
        $this->Conectar();
        $stmt = mssql_init($nombreProc, $this->conexion);
        mssql_query('SET ANSI_NULLS ON');
        mssql_query('SET ANSI_WARNINGS ON');

        foreach($params as $param => $options){
            if (!isset($options['type']))
                $options['type'] = SQLVARCHAR;
            if (!isset($options['is_output']))
                $options['is_output'] = false;
            if (!isset($options['is_null']))
                $options['is_null'] = false;
            if (!isset($options['maxlen']))
                $options['maxlen'] = -1;
            mssql_bind($stmt, '@'.$param, $options['value'], $options['type'], $options['is_output'], $options['is_null'], $options['maxlen']);
        }

        $result = mssql_execute($stmt);

//        do{
        while ($row = mssql_fetch_row($result)){
            $resultado[] = $row;
        }
//        }while (mssql_next_result($result) !== false);

        mssql_free_result($result);
        mssql_free_statement($stmt);

        $this->Desconectar();
        return $resultado;
    }
} 
