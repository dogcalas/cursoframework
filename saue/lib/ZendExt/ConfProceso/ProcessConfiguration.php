<?php

class ZendExt_ConfProceso_ProcessConfiguration {

    private static $instancia;
    public $name;
    public $Idconexion;
    public $Schemas;
    public $Tables;
    public $Alltablesschemas;
    public $fuentededatos;
    public $organizacionfuente;
    public $descripcion;
    public $version;
    public $autor;
    public $ZendExt_History_ADTHistory;
    public $sql = "";
    public $condicioneswhere = "";
    public $salvaselect = "";
    public $tablaprincipal;
    public $action;
    public $desactivado;
    public $fechadeactivacion;
    public $fechadedesactivacion;
    public $cantidadtraza;

    private function __construct() {
        
    }

    public static function getInstance() {
        if (!self::$instancia instanceof self) {
            self::$instancia = new self;
        }
        return self::$instancia;
    }
    public function getCantidadtrazas() {
        return $this->cantidadtraza;
    }

    
    public function getAlltablesschemas() {
        return $this->Alltablesschemas;
    }

    public function setAlltablesschemas($Alltablesschemas) {
        $this->Alltablesschemas = $Alltablesschemas;
    }

    public function getIdconexion() {
        return $this->Idconexion;
    }

    public function setIdconexion($idconexion) {
        $this->Idconexion = $idconexion;
    }

    public function getName() {
        return $this->name;
    }

    public function getSchemas() {
        return $this->Schemas;
    }

    public function getTables() {
        return $this->Tables;
    }

    public function getFuentededatos() {
        return $this->fuentededatos;
    }

    public function getOrganizacionfuente() {
        return $this->organizacionfuente;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getVersion() {
        return $this->version;
    }

    public function getAutor() {
        return $this->autor;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setSchemas($Schemas) {
        $this->Schemas = $Schemas;
    }

    public function setTables($Tables) {
        $this->Tables = $Tables;
    }

    public function setFuentededatos($fuentededatos) {
        $this->fuentededatos = $fuentededatos;
    }

    public function setOrganizacionfuente($organizacionfuente) {
        $this->organizacionfuente = $organizacionfuente;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setVersion($version) {
        $this->version = $version;
    }

    public function setAutor($autor) {
        $this->autor = $autor;
    }

    function FieldTable($schema, $table) {
        $sql = "SELECT  information_schema.columns.column_name FROM information_schema.columns
		WHERE
  		  information_schema.columns.table_schema != 'pg_catalog' AND 
		  information_schema.columns.table_schema != 'information_schema' AND 
		  information_schema.columns.table_schema = '$schema' AND 
		  information_schema.columns.table_name = '$table'";
        $db = ZendExt_History_Db_Singleton::getInstance();
        $colun = $db->query($sql);
        return $colun;
    }

    function createselect($idevent) {
        $from = array();
        $event = DatEvento::getevent($idevent);
        $this->sql.="select mod_traza.his_traza.idestructuracomun as idestructuracomun,";
        $this->sql.=" mod_traza.his_traza.ip_host as ip_host,";
        $this->sql.=" mod_traza.his_traza.idrol as idrol,";
        $this->sql.=" mod_traza.his_traza.iddominio as iddominio,";
        $this->sql.=" mod_traza.his_traza.dominio as dominio,";
        $this->sql.=" mod_traza.his_traza.estructuracomun as estructuracomun,";
        //$this->addpl($idevent);

        if ($event[0]["accconceptinstance"] != "no action") {
            $num = strripos($event[0]['conceptinstance'], ".");
            $numi = strpos($event[0]['conceptinstance'], ".");
            $this->tablaprincipal[] = substr($event[0]['conceptinstance'], $numi, $num - $numi);
            $this->tablaprincipal[] = substr($event[0]['conceptinstance'], $numi, strlen($event[0]['conceptinstance']) - $numi);
            
            $this->salvaselect.="mod_historial" . substr($event[0]['conceptinstance'], $numi, strlen($event[0]['conceptinstance']) - $numi);
            $this->sql.="mod_historial" . substr($event[0]['conceptinstance'], $numi, strlen($event[0]['conceptinstance']) - $numi) . " as conceptinstance";
            $from[] = "mod_historial" . substr($event[0]['conceptinstance'], $numi, $num - $numi);
            $this->action[] = " mod_historial" . substr($event[0]['conceptinstance'], $numi, $num - $numi) . ".operacion" . " = " . "'" . strtoupper($event[0]["accconceptinstance"]) . "'";
            //$this->action.=" mod_historial".substr($event[0]['conceptinstance'], $numi,$num-$numi).".operacion"." = "."'".strtoupper($event[0]["accconceptinstance"])."'";
        } else {
            $this->sql.="mod_traza.dat_evento.conceptinstance" . " as conceptinstance";
            $this->salvaselect.="mod_traza.dat_evento.conceptinstance";
            $from[] = "mod_traza.dat_evento";
        }
        if ($event[0]["accconceptname"] != "no action") {
            if (!$event[0]['conceptname']) {
                $this->salvaselect.="," . "mod_traza.dat_evento.nombre";
                $this->sql.="," . "mod_traza.dat_evento.nombre as conceptname";
                $from[] = "mod_traza.dat_evento";
            } else {
                $num = strripos($event[0]['conceptname'], ".");
                $numi = strpos($event[0]['conceptname'], ".");
                $this->salvaselect.="," . "mod_historial" . substr($event[0]['conceptname'], $numi, strlen($event[0]['conceptname']) - $numi);
                $this->sql.="," . "mod_historial" . substr($event[0]['conceptname'], $numi, strlen($event[0]['conceptname']) - $numi) . " as conceptname";
                $from[] = "mod_historial" . substr($event[0]['conceptname'], $numi, $num - $numi);

                $this->action[] = " mod_historial" . substr($event[0]['conceptname'], $numi, $num - $numi) . ".operacion" . " = " . "'" . strtoupper($event[0]["accconceptname"]) . "'";
                //$this->action.=" mod_historial".substr($event[0]['conceptname'], $numi,$num-$numi).".operacion"." = "."'".strtoupper($event[0]["accconceptname"])."'";
            }
        } else {
            $this->sql.=",mod_traza.dat_evento.conceptname" . " as conceptname";
            $this->salvaselect.=",mod_traza.dat_evento.conceptname";
            $from[] = "mod_traza.dat_evento";
        }
        if ($event[0]["accorggroup"] != "no action") {
			if (!$event[0]['orggroup']) {
                $this->salvaselect.="," . "mod_traza.his_traza.dominio";
                $this->sql.="," . "mod_traza.his_traza.dominio as orggroup";
                $from[] = "mod_traza.his_traza";
            }
            else{
            $num = strripos($event[0]['orggroup'], ".");
            $numi = strpos($event[0]['orggroup'], ".");
            $this->salvaselect.="," . "mod_historial" . substr($event[0]['orggroup'], $numi, strlen($event[0]['orggroup']) - $numi);
            $this->sql.="," . "mod_historial" . substr($event[0]['orggroup'], $numi, strlen($event[0]['orggroup']) - $numi) . " as orggroup";
            
            $from[] = "mod_historial" . substr($event[0]['orggroup'], $numi, $num - $numi);
            

            $this->action[] = " mod_historial" . substr($event[0]['orggroup'], $numi, $num - $numi) . ".operacion" . " = " . "'" . strtoupper($event[0]["accorggroup"]) . "'";
            }
            //$this->action.=" mod_historial".substr($event[0]['orggroup'], $numi,$num-$numi).".operacion"." = "."'".strtoupper($event[0]["accorggroup"])."'";
        } else {
            $this->sql.=",mod_traza.dat_evento.orggroup" . " as orggroup";
            $this->salvaselect.=",mod_traza.dat_evento.orggroup";
            $from[] = "mod_traza.dat_evento";
        }
        if ($event[0]["accorgresource"] != "no action") {
            if (!$event[0]['orgresource']) {
                $this->salvaselect.="," . "mod_traza.his_traza.usuario";
                $this->sql.="," . "mod_traza.his_traza.usuario as orgresource";
                $from[] = "mod_traza.his_traza";
            } else {
                $num = strripos($event[0]['orgresource'], ".");
                $numi = strpos($event[0]['orgresource'], ".");
                $this->salvaselect.="," . "mod_historial" . substr($event[0]['orgresource'], $numi, strlen($event[0]['orgresource']) - $numi);
                $this->sql.="," . "mod_historial" . substr($event[0]['orgresource'], $numi, strlen($event[0]['orgresource']) - $numi) . " as orgresource";
                $from[] = "mod_historial" . substr($event[0]['orgresource'], $numi, $num - $numi);

                $this->action[] = " mod_historial" . substr($event[0]['orgresource'], $numi, $num - $numi) . ".operacion" . " = " . "'" . strtoupper($event[0]["accorgresource"]) . "'";
                //$this->action.=" mod_historial".substr($event[0]['orgresource'], $numi,$num-$numi).".operacion"." = "."'".strtoupper($event[0]["accorgresource"])."'";
            }
        } else {
            $this->sql.=",mod_traza.dat_evento.orgresource" . " as orgresource";
            $this->salvaselect.=",mod_traza.dat_evento.orgresource";
            $from[] = "mod_traza.dat_evento";
        }
        if ($event[0]["accorgrole"] != "no action") {
            if (!$event[0]['orgrole']) {
                $this->salvaselect.="," . "mod_traza.his_traza.rol";
                $this->sql.="," . "mod_traza.his_traza.rol as orgrole";
                $from[] = "mod_traza.his_traza";
            } else {
                $num = strripos($event[0]['orgrole'], ".");
                $numi = strpos($event[0]['orgrole'], ".");
                $this->salvaselect.="," . "mod_historial" . substr($event[0]['orgrole'], $numi, strlen($event[0]['orgrole']) - $numi);
                $this->sql.="," . "mod_historial" . substr($event[0]['orgrole'], $numi, strlen($event[0]['orgrole']) - $numi) . " as orgrole";
                $from[] = "mod_historial" . substr($event[0]['orgrole'], $numi, $num - $numi);

                $this->action[] = " mod_historial" . substr($event[0]['orgrole'], $numi, $num - $numi) . ".operacion" . " = " . "'" . strtoupper($event[0]["accorgrole"]) . "'";
                //$this->action.=" mod_historial".substr($event[0]['orgrole'], $numi,$num-$numi).".operacion"." = "."'".strtoupper($event[0]["accorgrole"])."'";
            }
        } else {
            $this->sql.=",mod_traza.dat_evento.orgrole" . " as orgrole";
            $this->salvaselect.="," . "mod_traza.dat_evento.orgrole";
            $from[] = "mod_traza.dat_evento";
        }
        if ($event[0]["acctimestamp"] != "no action") {
            if (!$event[0]['timestamp']) {
                $num = strripos($event[0]['conceptinstance'], ".");
                $numi = strpos($event[0]['conceptinstance'], ".");
                $this->salvaselect.="," . "mod_historial" . substr($event[0]['conceptinstance'], $numi, $num - $numi) . ".fecha_creacion";
                ;

                $this->sql.="," . "mod_historial" . substr($event[0]['conceptinstance'], $numi, $num - $numi) . ".fecha_creacion as timestamp";
                $from[] = "mod_historial" . substr($event[0]['conceptinstance'], $numi, $num - $numi);

                //$this->action[]=" mod_historial".substr($event[0]['timestamp'], $numi,$num-$numi).".operacion"." = "."'".strtoupper($event[0]["acctimestamp"])."'";
                //$this->action.=" mod_historial".substr($event[0]['timestamp'], $numi,$num-$numi).".operacion"." = "."'".strtoupper($event[0]["acctimestamp"])."'";
            } else {
                $num = strripos($event[0]['timestamp'], ".");
                $numi = strpos($event[0]['timestamp'], ".");
                $this->salvaselect.="," . "mod_historial" . substr($event[0]['timestamp'], $numi, strlen($event[0]['timestamp']) - $numi);
                $this->sql.="," . "mod_historial" . substr($event[0]['timestamp'], $numi, strlen($event[0]['timestamp']) - $numi) . " as timestamp";
                $from[] = "mod_historial" . substr($event[0]['timestamp'], $numi, $num - $numi);
                $this->action[] = " mod_historial" . substr($event[0]['timestamp'], $numi, $num - $numi) . ".operacion" . " = " . "'" . strtoupper($event[0]["acctimestamp"]) . "'";
            }
        } else {
            $this->sql.=",mod_traza.dat_evento.timestamp" . " as timestamp";
            $this->salvaselect.=",mod_traza.dat_evento.timestamp";
            $from[] = "mod_traza.dat_evento";
        }
        $this->sql.=",mod_traza.dat_evento.semanticmodelreference" . " as semanticmodelreference";
        $this->salvaselect.=",mod_traza.dat_evento.semanticmodelreference";
        //$this->sql.=",mod_traza.dat_evento.piid" . " as piid";
        //$this->salvaselect.=",mod_traza.dat_evento.piid";
        $this->piid($event[0]["piid"]);
        $piids=  split(",", $event[0]["piid"]);
        $piids=$piids[0];
        $num = strripos($piids, ".");
        $numi = strpos($piids, ".");
        $piid = substr($piids, $numi, $num - $numi);
        $from[] = "mod_historial" . $piid;


        $from[] = 'mod_traza.dat_evento';


        return $from;
    }
      function piid($piid) {
        $piids=  split(",", $piid);
        $tabla="";
        $cont=0;
        $cant=count($piids);
        foreach ($piids as $value) {
             $num = strripos($value, ".");
             $numi = strpos($value, ".");
             if($cont==0){
                 if($cant>0){
                       $tabla.= "(";
                 }
              $tabla.= "CAST(mod_historial".substr($value, $numi, strlen($value) - $numi)." as character varying)";
             $cont=1;
             }
               else{         
                  $tabla.=" || CAST(mod_historial" .substr($value, $numi, strlen($value) - $numi)." as character varying)";             
                     }
        }
                if($cant>0){
                     $tabla.= ")";
                 }
//        $num = strripos($piid, ".");
  //      $numi = strpos($piid, ".");
    //    $piid = substr($piid, $numi, strlen($piid) - $numi);
      //  $this->sql.=",mod_historial" . $piid . " as piid";
        //$this->salvaselect.=",mod_historial" . $piid;
        $this->sql.=",".$tabla . " as piid";
        $this->salvaselect.=",".$tabla;                        
       // print_r( $this->sql)  ;die;   
                }

    /*function piid($piid) {
        $num = strripos($piid, ".");
        $numi = strpos($piid, ".");
        $piid = substr($piid, $numi, strlen($piid) - $numi);
        $this->sql.=",mod_historial" . $piid . " as piid";
        $this->salvaselect.=",mod_historial" . $piid;
    }*/

    function createfrom($idp, $partfrom, $idevent) {

        $partfrom = array_unique($partfrom);
        $condiciones = DatEvento::getcondiciones($idevent);
        $condiciones = $condiciones[0]["condiciones"];
        $condiciones = str_replace('{', "", $condiciones);
        $condiciones = str_replace('}', "", $condiciones);
        $condiciones = split(",", $condiciones);
        $condicioneswhere = "";
        if (count($condiciones) > 1) {
            $condicioneswhere.=" and ";
            for ($i = 0; $i < count($condiciones); $i+=4) {
                if ($i == 0) {
                    //$numi = strpos($condiciones[$i], ".");
                    //$numisegundo = strpos($condiciones[$i+2], ".");
                    //$condicioneswhere.="mod_historial".substr($condiciones[$i], $numi, strlen($condiciones[$i])-$numi).$condiciones[$i+1]."mod_historial".substr($condiciones[$i+2], $numisegundo, strlen($condiciones[$i+2])-$numisegundo)." ";
                    if (!(count(split("\'", $condiciones[$i])) == 3)) {
                        $num = strripos($condiciones[$i], ".");
                        $numi = strpos($condiciones[$i], ".");
                        $condicioneswhere.="mod_historial" . substr($condiciones[$i], $numi, strlen($condiciones[$i]) - $numi) . $condiciones[$i + 1];
                        $partfrom[] = "mod_historial" . substr($condiciones[$i], $numi, $num - $numi);
                    }
                    else
                        $condicioneswhere.=$condiciones[$i] . $condiciones[$i + 1];
                    if (!(count(split("\'", $condiciones[$i + 2])) == 3)) {
                        $num = strripos($condiciones[$i + 2], ".");
                        $numi = strpos($condiciones[$i + 2], ".");
                        $condicioneswhere.="mod_historial" . substr($condiciones[$i + 2], $numi, strlen($condiciones[$i + 2]) - $numi) . " ";
                        $partfrom[] = "mod_historial" . substr($condiciones[$i + 2], $numi, $num - $numi);
                    }
                    else
                        $condicioneswhere.=$condiciones[$i + 2] . " ";
                }

                else {
                    //$numi = strpos($condiciones[$i-1], ".");
                    //$numisegundo = strpos($condiciones[$i+1], ".");
                    //$condicioneswhere.=$condiciones[$i+2]." "."mod_historial".substr($condiciones[$i-1], $numi, strlen($condiciones[$i-1])-$numi).$condiciones[$i]."mod_historial".substr($condiciones[$i+1], $numisegundo, strlen($condiciones[$i+1])-$numisegundo)." ";
                    if (!(count(split("\'", $condiciones[$i - 1])) == 3)) {
                        $num = strripos($condiciones[$i - 1], ".");
                        $numi = strpos($condiciones[$i - 1], ".");
                        $condicioneswhere.=" " . $condiciones[$i + 2] . " " . "mod_historial" . substr($condiciones[$i - 1], $numi, strlen($condiciones[$i - 1]) - $numi) . $condiciones[$i];
                        $partfrom[] = "mod_historial" . substr($condiciones[$i - 1], $numi, $num - $numi);
                    }
                    else
                        $condicioneswhere.=" " . $condiciones[$i + 2] . " " . $condiciones[$i - 1] . $condiciones[$i];
                    if (!(count(split("\'", $condiciones[$i + 1])) == 3)) {
                        $num = strripos($condiciones[$i + 1], ".");
                        $numi = strpos($condiciones[$i + 1], ".");
                        $condicioneswhere.="mod_historial" . substr($condiciones[$i + 1], $numi, strlen($condiciones[$i + 1]) - $numi) . " ";
                        $partfrom[] = "mod_historial" . substr($condiciones[$i + 1], $numi, $num - $numi);
                    }
                    else
                        $condicioneswhere.=$condiciones[$i + 1] . " ";
                }
            }
        }
        $this->sql.=" from ";
        $this->sql.="mod_traza.his_dato,mod_traza.dat_registro_proceso, ";
        $partfrom = array_unique($partfrom);
        $_SESSION['partfrom'] = $partfrom;
        $this->condicioneswhere = $condicioneswhere;
        $cont = 0;
        foreach ($partfrom as $part) {
            if ($cont == 0)
                $this->sql.=$part;
            else
                $this->sql.="," . $part;
            $cont++;
        }
    }

    function comprobardeactivado($idp) {
        $sql = "select distinct 1 as activado from mod_traza.dat_registro_proceso where
(select max(fecha) from mod_traza.dat_registro_proceso where mod_traza.dat_registro_proceso.id_proceso=" . $idp . " and mod_traza.dat_registro_proceso.accion='activado')<
(select max(fecha) from mod_traza.dat_registro_proceso where mod_traza.dat_registro_proceso.id_proceso=" . $idp . " and mod_traza.dat_registro_proceso.accion='desactivado')";
        $result = $this->executesql($sql, false);
        if ($result[0]['activado'] >= 1) {
            $this->desactivado = true;
        }
    }

    function createwhere($idevent, $idp) {
        $this->sql.=" where mod_traza.dat_evento.idevento=" . $idevent . " and " . "mod_traza.his_traza.idtraza=mod_traza.his_dato.idtraza and ";
        
        $this->sql.="mod_historial" . $this->tablaprincipal[0] . ".fecha_creacion>='" . $this->fechadeactivacion . "' and ";

        if ($this->fechadedesactivacion != "") {
            $this->sql.="mod_historial" . $this->tablaprincipal[0] . ".fecha_creacion<='" . $this->fechadedesactivacion . "' and ";
        }
        $event = DatEvento::getinstanciaevento($idevent);
        $tablaHistorial = $this->agregarEnlaceHistorial($this->tablaprincipal);
        $this->sql.=$this->condicioneswhere;
        $this->action = array_unique($this->action);
        $actions = "";
        if($this->action){
        foreach ($this->action as $action) {
            if ($actions)
                $actions.=" and ";
            $actions.=$action;
        }
		
        $this->sql.=" and " . $actions . " ";
	}
        return $tablaHistorial;
    }

    function precreatesql($idevent, $idp) {
        $this->addpl($idevent);
        $sql = "select mod_traza.dat_registro_proceso.fecha from mod_traza.dat_registro_proceso  where mod_traza.dat_registro_proceso.id_proceso=$idp;";
        $Db_Concrete = ZendExt_History_Db_Singleton::getInstance();
        $fechas = $Db_Concrete->query($sql);
        $count = count($fechas);
        $this->fechadeactivacion = "";
        $this->fechadedesactivacion = "";
        for ($index = 0; $index < $count; $index++) {
            $this->fechadeactivacion = $fechas[$index]['fecha'];
            $this->fechadedesactivacion = $fechas[$index + 1]['fecha'];
            if ($this->createsql($idevent, $idp) == false)
                return FALSE;
            $index+=1;
        }
        return TRUE;
    }

    function createsql($idevent, $idp) {
        $this->sql = "";
        $this->condicioneswhere = "";
        $this->salvaselect = "";
        $this->tablaprincipal = "";
        $this->action = "";
        $partfrom = $this->createselect($idevent);
        //agregar el select de los pl
        if ($_SESSION['sql'] != "")
            $this->sql.=$_SESSION['sql'];
        $this->createfrom($idp, $partfrom, $idevent);
         
        //agregar el from de los pl
        if ($_SESSION['from'] != "") {
            $froms = array_diff($_SESSION['from'], $_SESSION['partfrom']);
            foreach ($froms as $from)
                $this->sql.="," . $from;
        }
        $tablaHistorial = $this->createwhere($idevent, $idp);
        //agregar el where de los pl
        if ($_SESSION['where'] != "")
            $this->sql.=$_SESSION['where'];
        $this->creategroupby($idevent);
        //agregar el groupby de los pl
        if ($_SESSION['groupby'] != "")
            $this->sql.=$_SESSION['groupby'];
        $tuplas = $this->executesql($this->sql, true);
        $this->cantidadtraza+=count($tuplas);
        $process = DatProcess::getsomeatributtproceso($idp);
        $idproceso = $process[0]['idproceso'];
        $nombre = $process[0]['nombre'];
        $version = $process[0]['version'];
        $this->savealltuplas($tuplas, $idproceso, $nombre, $version);
        return true;
    }

    function creategroupby($idevent) {
        $this->sql.=" group by" . " " . $this->salvaselect;
        $this->sql.=", mod_traza.his_traza.estructuracomun, mod_traza.his_traza.dominio, mod_traza.his_traza.iddominio, mod_traza.his_traza.idrol, mod_traza.his_traza.ip_host, mod_traza.his_traza.idestructuracomun";
    }

    function executesql($sql, $valido) {
        $Db_Concrete = ZendExt_History_Db_Singleton::getInstance();
        $result = $Db_Concrete->query($sql);
        if ($valido)
            $result = $this->eliminarrepetidos($result);
        return $result;
    }

    function eliminarrepetidos($resul) {
        $contador = 0;
        $contadorinterno = 0;
        $result = $resul;
        $totaltuplas = count($result);
        for ($index = 0; $index < $totaltuplas - 1; $index++) {
            for ($index1 = $index + 1; $index1 < $totaltuplas; $index1++) {
                if ($result[$index]['timestamp'] == $result[$index1]['timestamp'] && $result[$index]['conceptinstance'] == $result[$index1]['conceptinstance']) {
                    unset($result[$index1]);
                }
            }
        }

        return $result;
    }

    function agregarEnlaceHistorial($tablaHistorialc) {
        $tablaHistorial = "mod_historial" . $tablaHistorialc[1];
        //print_r("mod_traza.his_dato.idobjeto=CAST(".$tablaHistorial." AS character varying) and ");die();
        $this->sql.="mod_traza.his_dato.idobjeto=CAST(" . $tablaHistorial . " AS character varying)";
        return $tablaHistorial;
    }

    function savealltuplas($tuplas, $idproceso, $nombre, $version) {
        if (count($tuplas) > 0)
            foreach ($tuplas as $tupla) {
                $this->savetupla($tupla, $idproceso, $nombre, $version);
            }
    }

    function savetupla($tupla, $idproceso, $nombre, $version) {
        //los pl
        $pls = $_SESSION['pl'];
        $pl = "{";
        $total = count($pls);

        for ($index = 0; $index < $total; $index++) {
            if ($pls[$index + 2] != "\"no action\"") {
                $pls[$index + 1] = $tupla[$pls[$index]];
            }
            $pl.=$pls[$index] . "," . $pls[$index + 1] . "," . $pls[$index + 1];
            $index+=2;
        }
        $pl.="}";
        
        //las columnas de traza
        $pIpHost = $tupla['ip_host'];
        $pidestructura = $tupla['idestructuracomun'];
        $pEstructura = $tupla['estructuracomun'];
        $pIdRol = $tupla['idrol'];
        $pRol = $tupla['orgrole'];
        $pIdDominio = $tupla['iddominio'];
        $pDominio = $tupla['dominio'];
        $pUser = $tupla['orgresource'];

        //los campos de his_proceso


        $idinstancia = $tupla['piid'];
        $actividad = $tupla['conceptname'];
        $estadoactividad = 'completado';
        $idactividad = $tupla['conceptinstance'];
        $ontologia = $tupla['semanticmodelreference'];
        $fechaevento = $tupla['timestamp'];


        $hisproceso = new ZendExt_Trace_Container_Proceso($pl, $fechaevento, $idproceso, $nombre, $version, $idinstancia, $actividad, $estadoactividad, $idactividad, $ontologia, $pIpHost, $pUser, $pIdRol, $pIdDominio, $pidestructura, $pRol, $pDominio, $pEstructura);
        $instance = ZendExt_Trace :: getInstance();
        $instance->handle($hisproceso);
    }

    function vaciarregistroproceso($idp) {
        $sql = "delete from mod_traza.dat_registro_proceso where id_proceso='" . $idp .
                "'";
        if ($this->fechadedesactivacion == "") {
            $sql.=" and id_registro!= (select max(id_registro) from mod_traza.dat_registro_proceso where id_proceso='" . $idp . "' and accion='activado')";
            $fecha = date("d-m-y H:i:s");
            $sqlactulizarfecha = "update mod_traza.dat_registro_proceso set fecha='" . $fecha . "' where 
mod_traza.dat_registro_proceso.id_proceso='" . $idp . "'";
        }

        $Db_Concrete = ZendExt_History_Db_Singleton::getInstance();
        $Db_Concrete->query($sql);
        if ($this->fechadedesactivacion == "")
            $Db_Concrete->query($sqlactulizarfecha);
        return TRUE;
    }

    function addpl($idevent) {
        $pls = DatEvento::getpl($idevent);
        $_SESSION['pl'] = null;
        $_SESSION['where'] = "";
        $_SESSION['sql'] = "";
        $_SESSION['from'] = "";
        $_SESSION['groupby'] = "";
        if ($pls[0]['pl'] != "{}") {
            $pls = str_replace('{', '', $pls[0]['pl']);
            $pls = str_replace('}', '', $pls);
            $pls = explode(",", $pls);
            $total = count($pls);
            $where = "";
            $sql = "";
            $from = "";
            $groupby = "";
            for ($index = 0; $index < $total; $index++) {
                if ($index == 0) {
                    if ($pls[$index + 2] != "\"no action\"") {
                        $num = strripos($pls[$index + 1], ".");
                        $numi = strpos($pls[$index + 1], ".");
                        $sql.=", " . "mod_historial" . substr($pls[$index + 1], $numi, strlen($pls[$index + 1]) - $numi) . " as $pls[$index]";
                        $from [] = "mod_historial" . substr($pls[$index + 1], $numi, $num - $numi);
                        $where.=" and mod_historial" . substr($pls[$index + 1], $numi, $num - $numi) . ".operacion" . "='" . strtoupper($pls[$index + 2]) . "'";
                        $groupby.=", mod_historial" . substr($pls[$index + 1], $numi, strlen($pls[$index + 1]) - $numi);
                    }
                } else {
                    if ($pls[$index + 2] != "\"no action\"") {

                        $num = strripos($pls[$index + 1], ".");
                        $numi = strpos($pls[$index + 1], ".");
                        $sql.="," . " mod_historial" . substr($pls[$index + 1], $numi, strlen($pls[$index + 1]) - $numi) . " as $pls[$index]";
                        $from [] = "mod_historial" . substr($pls[$index + 1], $numi, $num - $numi);
                        $where.=" and mod_historial" . substr($pls[$index + 1], $numi, $num - $numi) . ".operacion" . "='" . strtoupper($pls[$index + 2]) . "'";
                        $groupby.=", mod_historial" . substr($pls[$index + 1], $numi, strlen($pls[$index + 1]) - $numi);
                    }
                }

                $index+=2;
            }
            $from = array_unique($from);
            $_SESSION['pl'] = $pls;
            $_SESSION['where'] = $where;
            $_SESSION['sql'] = $sql;
            $_SESSION['from'] = $from;
            $_SESSION['groupby'] = $groupby;
        }
    }

}

?>
