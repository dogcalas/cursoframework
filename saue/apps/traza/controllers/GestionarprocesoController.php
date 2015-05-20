<?php

class GestionarprocesoController extends ZendExt_Controller_Secure {

    protected $conn;
    protected $ProcessConfiguration;

    function init() {
        parent::init();
    }

    public function getProceso() {
        return $this->proceso;
    }

    public function setProceso($proceso) {
        $this->proceso = $proceso;
    }

    public function getConn() {
        return $this->conn;
    }

    public function setConn($conn) {
        $this->conn = $conn;
    }

    function GestionarprocesoAction() {
        $this->render();
    }

    function ModconexionAction() {
        $idproceso = $this->getRequest()->getPost('idproceso');
        $_SESSION['idproceso'] = $idproceso;
        $_SESSION['modificar'] = 1;
    }

    function forcheckerConexion($data) {
        $idconexion = DatProcess::getidconexion($_SESSION['idproceso']);
        $cont = 0;
        foreach ($data as $process) {
            if ($process['id'] == $idconexion[0]['idconexion']) {
                $_SESSION['idconexion'] = $cont;
                break;
            }
            else
                $cont++;
        }
    }

    function getselecconexionAction() {
        $idconexion = $_SESSION['idconexion'];
        echo"{'idconexion':$idconexion}";
        return;
    }

    function getconexionsAction() {

        $result = DatConexion::getconexions($offset, $limit);

        $data = array();
        foreach ($result as $row) {
            $data [] = array("id" => $row['id'], "name" => $row['nombre'], "user" => $row['usuario'], "host" => $row['host'], "db" => $row['bd'], "port" => $row['puerto'], "pass" => $row['contrasenna']);
        }
        if ($_SESSION['modificar']) {
            $this->forcheckerConexion($data);
            $_SESSION['modificar']++;
        }

        $result = array('cantidad_filas' => 2, 'datos' => $data);
        echo json_encode($result);
    }

    function crearconexionAction() {
        $id = $this->getRequest()->getPost('id');
        $this->ProcessConfiguration = ZendExt_ConfProceso_ProcessConfiguration::getInstance();
        $this->ProcessConfiguration->setIdconexion($id);
        $_SESSION['ProcessConfiguration'] = $this->ProcessConfiguration;
        $rsa = new ZendExt_RSA_Facade();
        $conn = new Connection($this->getRequest()->getPost('host'), $this->getRequest()->getPost('port'), $this->getRequest()->getPost('bd'), $this->getRequest()->getPost('user'), $rsa->decrypt($this->getRequest()->getPost('pass')), null);
        $d = new PostgreSQL($conn);
        $conn->set_driver($d);
        $this->setConn($conn);
        $_SESSION['conexion'] = $conn;
    }

    function crearschemasAction() {
        $Sschemas = $this->_request->getPost('schemas');
        $this->ProcessConfiguration = $_SESSION['ProcessConfiguration'];
        $this->ProcessConfiguration->setSchemas($Sschemas);
        $_SESSION['ProcessConfiguration'] = $this->ProcessConfiguration;
        $schemas = explode(",", $Sschemas);
        $_SESSION['schemas'] = $schemas;
    }

    function forcheckertables($data) {
        $tables = DatProcess::gettables($_SESSION['idproceso']);
        $cont = 0;
        $arrycontt;

        $tables = str_replace('{', '', $tables[0]['tablas']);
        $tables = str_replace('}', '', $tables);
        $tables = explode(",", $tables);
        $arrycontt = -1;
        foreach ($data as $tableP) {
            foreach ($tables as $table) {

                if ($tableP['name'] == $table) {
                    if ($arrycontt == -1)
                        $arrycontt = $cont;
                    else
                        $arrycontt = $arrycontt . ' ' . $cont;
                    break;
                }
            }
            $cont++;
        }
        $_SESSION['indextables'] = $arrycontt;
    }

    function getselectablesAction() {
        $indextables = $_SESSION['indextables'];
        echo"{'indextables':'$indextables'}";
        return;
    }

    function gettablesAction() {
        $schemas = $_SESSION['schemas'];
        $this->ProcessConfiguration = $_SESSION['ProcessConfiguration'];
        foreach ($schemas as $schema) {
            $tables = $this->findTable($tables, $schema);
        }

        $data = $this->creararray($tables);
        if ($_SESSION['modificar']) {
            $this->forcheckertables($data);
            $_SESSION['modificar']++;
        }
        $result = array('cantidad_filas' => 2, 'datos' => $data);
        $this->ProcessConfiguration->setAlltablesschemas($result);
        $_SESSION['ProcessConfiguration'] = $this->ProcessConfiguration;
        echo json_encode($result);
    }

    function creararray($arraytables) {
        foreach ($arraytables as $tablesbame) {
            $data [] = array("name" => $tablesbame);
        }
        return $data;
    }

    function findTable($tablesname, $schema) {
        $sql = "select tablename from pg_tables where schemaname = '$schema'";
        $tablesbd = $this->getConn()->execute($sql)->fetchAll();
        foreach ($tablesbd as $table) {
            $tablesname[] = $schema . "." . $table['tablename'];
        }

        return $tablesname;
    }

    function verificarcambiosschemas($newschemas) {
        $idproceso = $_SESSION['idproceso'];
        $newschemas = explode(",", $newschemas);

        $schemas = DatProcess::getschemas($_SESSION['idproceso']);
        $schemas = str_replace('{', '', $schemas[0]['esquemas']);
        $schemas = str_replace('}', '', $schemas);
        $schemas = explode(",", $schemas);
///desactivo y desvalido el proceso
        if (count($newschemas) == count($schemas)) {
            $dife = array_diff($newschemas, $schemas);
            if (count($dife) > 0) {
                $activovalidado = DatProcess::getprocesoactivo($idproceso);
                if ($activovalidado[0]['activado']) {
                    $registro = new DatRegistroProceso();
                    $registro->fecha = date("d-m-y");
                    $registro->accion = "desactivado";
                    $registro->id_proceso = $idproceso;
                    $actM = new DatRegistroProcesoModel();
                    $actM->Insertar($registro);
                }
                $proceso = DatProcess::activarProceso($idproceso);
                
                $proceso->validado = 0;
				$versionado=DatRegistroProceso::getRegistros($idproceso);
					if(count($versionado)>0){
					
						
						$proceso->modificarversion=1;
						
						}
						$proceso->activado = 0;
                $m = new DatProcessModel();
                $m->Modificar($proceso);
                return true;
            }
        } else {
            $activovalidado = DatProcess::getprocesoactivo($idproceso);
            if ($activovalidado[0]['activado']) {
                $registro = new DatRegistroProceso();
                $registro->fecha = date("d-m-y");
                $registro->accion = "desactivado";
                $registro->id_proceso = $idproceso;
                $actM = new DatRegistroProcesoModel();
                $actM->Insertar($registro);
            }
            $proceso = DatProcess::activarProceso($idproceso);
       
            $proceso->validado = 0;
			$versionado=DatRegistroProceso::getRegistros($idproceso);
					if(count($versionado)>0){
					
						
						$proceso->modificarversion=1;
						
						}
						 $proceso->activado = 0;
            $m = new DatProcessModel();
            $m->Modificar($proceso);
            return true;
        }
        return false;
    }

    function verificarcambiostables($newtables) {
        $idproceso = $_SESSION['idproceso'];
        $newtables = explode(",", $newtables);

        $tables = DatProcess::gettables($_SESSION['idproceso']);
        $tables = str_replace('{', '', $tables[0]['tablas']);
        $tables = str_replace('}', '', $tables);
        $tables = explode(",", $tables);
///desactivo y desvalido el proceso
        if (count($newtables) == count($tables)) {
            $dife = array_diff($newtables, $tables);
            if (count($dife) > 0) {
                $activovalidado = DatProcess::getprocesoactivo($idproceso);
                if ($activovalidado[0]['activado']) {
                    $registro = new DatRegistroProceso();
                    $registro->fecha = date("d-m-y");
                    $registro->accion = "desactivado";
                    $registro->id_proceso = $idproceso;
                    $actM = new DatRegistroProcesoModel();
                    $actM->Insertar($registro);
                }
                $proceso = DatProcess::activarProceso($idproceso);
       
            $proceso->validado = 0;
			$versionado=DatRegistroProceso::getRegistros($idproceso);
					if(count($versionado)>0){
					
						//print_r($versionado);die;
						$proceso->modificarversion=1;
						
						}
						 $proceso->activado = 0;
            $m = new DatProcessModel();
            $m->Modificar($proceso);
            }
        } else {
            $activovalidado = DatProcess::getprocesoactivo($idproceso);
            if ($activovalidado[0]['activado']) {
                $registro = new DatRegistroProceso();
                $registro->fecha = date("d-m-y");
                $registro->accion = "desactivado";
                $registro->id_proceso = $idproceso;
                $actM = new DatRegistroProcesoModel();
                $actM->Insertar($registro);
            }
            $proceso = DatProcess::activarProceso($idproceso);
       
            $proceso->validado = 0;
			$versionado=DatRegistroProceso::getRegistros($idproceso);
					if(count($versionado)>0){
					
						//print_r($versionado);die;
						$proceso->modificarversion=1;
						
						}
						 $proceso->activado = 0;
            $m = new DatProcessModel();
            $m->Modificar($proceso);
        }
    }

    function processdefinitionAction() {
        try {
            $actionproceso = $this->_request->getPost('actionproceso');
            $this->ProcessConfiguration = $_SESSION['ProcessConfiguration'];
            $tables = $this->ProcessConfiguration->getTables();
            $schemas = $this->ProcessConfiguration->getSchemas();
            if ($actionproceso=="modificar")
                $difschemas = $this->verificarcambiosschemas($schemas);
            if (!$difschemas && $actionproceso=="modificar")
                $this->verificarcambiostables($tables);
            //$schemas = $this->createarraysql($schemas);
            $schemas = '{' . $schemas . '}';
            $tables = '{' . $tables . '}';


            $Nproceso = $this->_request->getPost('Nproceso');
            $fdatos = $this->_request->getPost('fdatos');
            $descripcion = $this->_request->getPost('descripcion');

            $idintancia = $this->_request->getPost('selectedtables');
            $idproceso = $_SESSION['idproceso'];
            $_SESSION['modificar'] = 0;
            $idconexion = $this->ProcessConfiguration->getIdconexion();
            if ($actionproceso == "modificar") {
                $DatProcess = Doctrine::getTable('DatProcess')->find($idproceso);
                $DatProcess->idconexion = $idconexion;
                $DatProcess->fuentedatos = $fdatos;
                $DatProcess->descripcion = $descripcion;
                $DatProcess->nombre = $Nproceso;
                $DatProcess->tablas = $tables;
                $DatProcess->esquemas = $schemas;
                $DatProcess->instancia = $idintancia;
                $DatProcessM = new DatProcessModel();
                $DatProcessM->Modificar($DatProcess);
                echo"{'processadd':1,'actionAddMod':'modificado'}";
                return;
            } else {
                $DatProcess = new DatProcess();
                $DatProcess->idconexion = $idconexion;
                $DatProcess->fuentedatos = $fdatos;
                $DatProcess->descripcion = $descripcion;
                $DatProcess->nombre = $Nproceso;
                $DatProcess->tablas = $tables;
                $DatProcess->esquemas = $schemas;
                $DatProcess->instancia = $idintancia;
                $DatProcessM = new DatProcessModel();
                $DatProcessM->Insertar($DatProcess);
                echo"{'processadd':1,'actionAddMod':'adicionado'}";
                return;
            }
        } catch (Exception $exc) {
            echo"{'processadd':0}";
            return;
        }
    }

    function createarraysql($arrayphp) {
        foreach ($arrayphp as $element) {
            $cadena = $cadena . $element . ' ';
        }
        $cadena = '{' . $cadena . '}';
        return $cadena;
    }

    function getalltablesAction() {
        $this->ProcessConfiguration = $_SESSION['ProcessConfiguration'];
        $alltables = $this->ProcessConfiguration->getTables();
        $alltables= explode(',', $alltables);
        foreach ($alltables as $table){
            $data [] = array("name" => $table);
        }
        $result = array('datos' => $data);
        echo json_encode($result);
    }

    function forcheckerschemas($data) {
        $schemas = DatProcess::getschemas($_SESSION['idproceso']);
        $cont = 0;
        $arrycont;

        $schemas = str_replace('{', '', $schemas[0]['esquemas']);
        $schemas = str_replace('}', '', $schemas);
        $schemas = explode(",", $schemas);
        $arrycont = -1;
        foreach ($data as $process) {
            foreach ($schemas as $schema) {
                if ($process['name'] == $schema) {
                    if ($arrycont == -1)
                        $arrycont = $cont;
                    else
                        $arrycont = $arrycont . ' ' . $cont;
                    break;
                }
            }
            $cont++;
        }
        $_SESSION['indexschemas'] = $arrycont;
    }

    function getselecschemasAction() {
        $indexschemas = $_SESSION['indexschemas'];
        echo"{'indexschemas':'$indexschemas'}";
        return;
    }

    function getschemasAction() {
        $conn = $_SESSION['conexion'];
        $Pgsql = new ZendExt_Db_Role_Pgsql($conn);
        if ($Pgsql->PgsqlVerificarDisponibilidadServidor("pgsql", $conn->get_username(), $conn->get_password(), $conn->get_host(), $conn->get_db(), $conn->get_port()) == "1") {
            $schemas = $Pgsql->getPgsqlSchemas("pgsql", $conn->get_username(), $conn->get_password(), $conn->get_host(), $conn->get_db());
            foreach ($schemas as $schema) {
                $data [] = array("id" => $schema['id'], "name" => $schema['text']);
            }
            if ($_SESSION['modificar']) {
                $this->forcheckerschemas($data);
                $_SESSION['modificar']++;
            }
            $result = array('cantidad_filas' => 2, 'datos' => $data);
            echo json_encode($result);
        } else {
            ZendExt_MessageBox::show ('Error en Conexion.', ZendExt_MessageBox::ERROR);
            return ;
        }
    }

    function setidprocesoAction() {
        
        $idproceso = $this->getRequest()->getPost('idproceso');
        $_SESSION['idproceso'] = $idproceso;
        $this->teststateetevent($idproceso);
    }

    function geteventAction() {
        $start = $this->_request->getPost('start');
        $limit = $this->_request->getPost('limit');
        if($limit==15)
            $limit=25;
        $events = DatEvento::geteventproceso($_SESSION['idproceso'],$start,$limit);
        foreach ($events as $row) {
            $data [] = array("idevent" => $row['idevento'], "ename" => $row['nombre'], "edescripcions" => $row['descripcion']);
        }
        ///echo(count($data));
        $events = array('cantidad_filas' => $_SESSION['cantidad_traza'], 'datos' => $data);
        echo json_encode($events);
    }

    function teststateetevent($idproceso) {
        
        $events = DatEvento::geteventproceso($idproceso,$offset, $limit);
        $_SESSION['cantidad_traza']=count($events);
        if ($_SESSION['cantidad_traza']==0) {
            echo"{'vacio':1}";
            return;
        } else {
            echo"{'vacio':0}";
            return;
        }
    }

    function getprocesosAction() {
        $start = $this->_request->getPost('start');
        $limit = $this->_request->getPost('limit');
        if($limit==15)
            $limit=25;
        $cantidad=count(DatProcess::getproceso($start,$limit));
        $procesos = DatProcess::getproceso($start,$limit);

        //echo '<pre>';
        // echo($procesos[0]['tablas']);die();

        $data = array();
        foreach ($procesos as $row) {
            $data [] = array("idproceso" => $row['idproceso'], "name" => $row['nombre'], "descripcion" => $row['descripcion'], "fdatos" => $row['fuentedatos'], "idconexion" => $row['idconexion'], "instancia" => $row['instancia'],"version" => $row['version']);
        }

        $procesos = array('cantidad_filas' => $cantidad,'datos' => $data);
        //$_SESSION['hasvaluesconexion'] = true;
        echo json_encode($procesos);
    }

    function selectedtablesAction() {
        $selectedtables = $this->_request->getPost('selectedtables');
        //$tables = explode(" ", $selectedtables);
        $this->ProcessConfiguration = $_SESSION['ProcessConfiguration'];
        $this->ProcessConfiguration->setTables($selectedtables);
        $_SESSION['ProcessConfiguration'] = $this->ProcessConfiguration;
        return;
    }

    /* function crearprojectoAction() {
      $Sschemas = $this->_request->getPost('Schemas');
      $schemas = explode(".", $Sschemas);
      $this->findTable($schemas);
      return;
      } */

    function addScheTabl($schema, $tables) {
        $this->proceso->addSchemaTables($schema, $tables);
        $tables = $this->proceso->getTables();
        $_SESSION['proceso'] = $this->proceso;
        return;
    }

    function crearprocesoAction() {
        $tables = $this->_request->getPost('tables');
        //$tables = explode(" ", $tables);
        $Nproceso = $this->_request->getPost('Nproceso');
        $fdatos = $this->_request->getPost('fdatos');
        $descripcion = $this->_request->getPost('descripcion');
        $proceso = $_SESSION['proceso'];
        $proceso->setTablesS($tables);
        $proceso->setName($Nproceso);
        $proceso->setFuentededatos($fdatos);
        $proceso->setDescripcion($descripcion);
        $_SESSION['proceso'] = $proceso;
    }

    function addeventAction() {
 
 /// print_r(count($versionado));die;
        try {
            $idevent = $this->_request->getPost('idevent');
            $actionAddMod = $this->_request->getPost('actionAddMod');
            $descripcion = $this->_request->getPost('descripcion');
            $nombre = $this->_request->getPost('name');
            $idproceso = $_SESSION['idproceso'];
            if ($actionAddMod == "Modificado") {
                $DatEvent = Doctrine::getTable('DatEvento')->find($idevent);
                $DatEvent->idproceso = $idproceso;
                $DatEvent->descripcion = $descripcion;
                $DatEvent->nombre = $nombre;
                $DatEventM = new DatEventoModel();
                $DatEventM->Modificar($DatEvent);
                echo"{'vacio':1,'actionAddMod':'modificado'}";
                return;
            } else {
                ///desactivo y desvalido el proceso y guardo el registro
                $activovalidado = DatProcess::getprocesoactivo($idproceso);
                if ($activovalidado[0]['activado']) {
                    $registro = new DatRegistroProceso();
                    $registro->fecha = date("d-m-y");
                    $registro->accion = "desactivado";
                    $registro->id_proceso = $idproceso;
                    $actM = new DatRegistroProcesoModel();
                    $actM->Insertar($registro);
                }
                $proceso = DatProcess::activarProceso($idproceso);
                if($proceso->validado!=0 || $proceso->activado!=0){
					
                
				$proceso->validado = 0;
				$proceso->activado = 0;
				/////modifico la version
				$versionado=DatRegistroProceso::getRegistros($idproceso);
					if(count($versionado)>0){
					
						$proceso->modificarversion=1;
					
						}
                $m = new DatProcessModel();
                $m->Modificar($proceso);
			}
			else{
				$versionado=DatRegistroProceso::getRegistros($idproceso);
					if(count($versionado)>0){
					
						$proceso->modificarversion=1;
					
						}
                $m = new DatProcessModel();
                $m->Modificar($proceso);
				}


                /////adiciono el proceso

                $DatEvent = new DatEvento();
                $DatEvent->idproceso = $idproceso;
                $DatEvent->descripcion = $descripcion;
                $DatEvent->nombre = $nombre;
                $DatEventM = new DatEventoModel();
                $DatEventM->Insertar($DatEvent);
                $_SESSION['events'] = null;
                echo"{'vacio':1,'actionAddMod':'adicionado'}";
                return;
            }
        } catch (Exception $exc) {
            echo"{'vacio':0}";
            return;
        }
    }

    function getnamePAction() {
        $_SESSION['proceso']->getName();

        echo"{'mensaje': 'Configurar traza del proceso \"" . $_SESSION['proceso']->getName() . "\".'}";
        return;
    }

    function deleventAction() {
        $idevent = $this->getRequest()->getPost('idevent');
        $idproceso=DatEvento::getidproceso($idevent);
        $activo=DatProcess::getprocesoactivo($idproceso[0]['idproceso']);
        if($activo[0]['activado']){
            echo"{'conectado':0}";
        return;
        }
        $DatEvento=Doctrine::getTable('DatEvento')->find($idevent);
        $DatEventoModel=new DatEventoModel();
        $DatEventoModel->Eliminar($DatEvento);
        echo"{'conectado':1}";
        return;
    }

    function delprocesoAction() {
        $idproceso = $this->getRequest()->getPost('idproceso');
        $activo=DatProcess::getprocesoactivo($idproceso);
        if($activo[0]['activado']){
            echo"{'conectado':0}";
        return;
        }
            
        
        $DatProces = Doctrine::getTable('DatProcess')->find($idproceso);
		$DatProcessModel=new DatProcessModel();
		$DatProcessModel->Eliminar($DatProces);
        //eliminar todos los eventos del proceso
        $this->deleventdeproceso($idproceso);
        echo"{'conectado':1}";
        return;
    }

    function deleventdeproceso($idproceso) {
		$DatEvents = DatEvento::geteventproceso($idproceso);
		$DatEventoModel=new DatEventoModel();
               
		foreach($DatEvents as $event){
			$DatEvento=Doctrine::getTable('DatEvento')->find($event['idevento']);
                        $DatEventoModel->Eliminar($DatEvento);
			}
        return;
    }

}

?>
