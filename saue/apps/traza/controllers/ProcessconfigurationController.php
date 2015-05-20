<?php

class ProcessconfigurationController extends ZendExt_Controller_Secure {

    protected $ProcessConfiguration;

    function init() {
        parent :: init();
    }

    function ProcessconfigurationAction() {
        $this->render();
    }

    function treegridJsonAction() {
        $id = $this->getRequest()->getPost('id');
        $procesos = DatProcess::getproceso($offset, $limit);
        $nodes = array();
        if ($id == '0')
            foreach ($procesos as $row) {
                $nodes [] = array("id" => $row['idproceso'], "text" => $row['nombre'], "cls" => 'folder', "iconCls" => 'process-folder');
            } else {
            $events = DatEvento::geteventproceso($id, $offset, $limit);
            foreach ($events as $row) {
                $nodes [] = array("id" => $id . ' ' . $row['idevento'], "text" => $row['nombre'], "cls" => 'file', "leaf" => true, "iconCls" => 'event');
            }
        }
        echo json_encode($nodes);
    }

    function getcorrelacionAction() {

        $event = DatEvento::getcorrelacion($_SESSION['idE']);
        $data [] = array("id" => '1', "name" => "Concept:instance", "tablev" => $event[0]['conceptinstance'], "action" => $event[0]['accconceptinstance']);
        $data [] = array("id" => '2', "name" => "Concept:name", "tablev" => $event[0]['conceptname'], "action" => $event[0]['accconceptname']);
        $data [] = array("id" => '3', "name" => "Org:group", "tablev" => $event[0]['orggroup'], "action" => $event[0]['accorggroup']);
        $data [] = array("id" => '4', "name" => "Org:resource", "tablev" => $event[0]['orgresource'], "action" => $event[0]['accorgresource']);
        $data [] = array("id" => '5', "name" => "Org:role", "tablev" => $event[0]['orgrole'], "action" => $event[0]['accorgrole']);
        $data [] = array("id" => '6', "name" => "Time:timestamp", "tablev" => $event[0]['timestamp'], "action" => $event[0]['acctimestamp']);
        if ($event[0]['semanticmodelreference']) {
            $data [] = array("id" => '7', "name" => "Semantic:modelReference", "tablev" => $event[0]['semanticmodelreference'], "action" => "no action");
        }
        else
            $data [] = array("id" => '7', "name" => "Semantic:modelReference", "tablev" => "", "action" => "");

        $result = array('cantidad_filas' => 2, 'datos' => $data);
        echo json_encode($data);
    }

    function getplAction() {
        $pls = DatEvento::getpl($_SESSION['idE']);


        $pls = str_replace('{', '', $pls[0]['pl']);
        $pls = str_replace('}', '', $pls);
        $pls = explode(",", $pls);
        $total = count($pls);
        for ($index = 0; $index < $total; $index++) {
            $data [] = array("id" => $index, "name" => $pls[$index], "tablev" => $pls[$index + 1], "action" => str_replace("\"", '', $pls[$index + 2]));
            $index+=2;
        }
        $result = array('cantidad_filas' => 2, 'datos' => $data);
        echo json_encode($data);
    }

    function getalltableAction() {
        if (!is_object($_SESSION['ProcessConfiguration'])) {
            $this->ProcessConfiguration = ZendExt_ConfProceso_ProcessConfiguration::getInstance();
            $_SESSION['ProcessConfiguration'] = $this->ProcessConfiguration;
        } else {
            $this->ProcessConfiguration = $_SESSION['ProcessConfiguration'];
        }


        $tables = DatProcess::gettables($_SESSION['idP']);
        $tables = str_replace('{', '', $tables[0]['tablas']);
        $tables = str_replace('}', '', $tables);
        $tables = explode(",", $tables);
        $coluns;
        foreach ($tables as $table) {
            $schematable = explode('.', $table);
            $rcoluns = $this->ProcessConfiguration->FieldTable($schematable[0], $schematable[1]);
            foreach ($rcoluns as $colun) {
                $data [] = array("name" => $table . '.' . $colun['column_name']);
            }
        }
        $result = array('cantidad_filas' => 2, 'datos' => $data);

        echo json_encode($result);
    }

    function getcondicionesAction() {

        $event = DatEvento::getcondiciones($_SESSION['idE']);
        $event = str_replace('{', '', $event[0]['condiciones']);
        $event = str_replace('}', '', $event);
        $event = explode(",", $event);

        for ($i = 0; $i < count($event); $i+=4) {
            if ($i == 0)
                $data [] = array("columna" => $event[$i], "comparador" => $event[$i + 1], "valorocolumna" => $event[$i + 2], "operador" => "");
            else
                $data [] = array("columna" => $event[$i - 1], "comparador" => $event[$i], "valorocolumna" => $event[$i + 1], "operador" => $event[$i + 2]);
        }
        $result = array('cantidad_filas' => 2, 'datos' => $data);
        echo json_encode($result);
    }

    function getpropiedadesAction() {
        $event = DatEvento::getorderbygroupby($_SESSION['idE']);

        $data [] = array("id" => "1", "orderb" => $event[0]['orderby'], "groupb" => $event[0]['piid']);
        $result = array('cantidad_filas' => 2, 'datos' => $data);

        echo json_encode($result);
    }

    function setidPEAction() {
        $_SESSION['idP'] = $this->getRequest()->getPost('idP');
        $_SESSION['idE'] = $this->getRequest()->getPost('idE');
        echo"{'state':1}";
        return;
    }

    function saveAction() {
        $activovalidado = DatProcess::getprocesoactivo($_SESSION['idP']);
        $pls = $this->getRequest()->getPost('pls');
        $resultatribute = $this->getRequest()->getPost('resultatribute');
        $resulcondiciones = $this->getRequest()->getPost('resulcondiciones');
        $TablasEnUso = array();
        //print_r($resultatribute);
        $arr = split(",", $resultatribute);
        $arr = array_unique($arr);
        foreach ($arr as $value) {
            //print_r(split("\.",$value."a"));die;
            if (count(split("\.", $value . "a")) >= 2) {
                $pos = strripos($value, ".");
                $TablasEnUso[] = substr($value, 0, $pos);
            }
        }
        $TablasEnUso = array_unique($TablasEnUso);
        $cantTablas = count($TablasEnUso);
        $cantCondiciones = split(",", $resulcondiciones);
        $salvacondiciones = $cantCondiciones;
        $cantCondiciones = (count($cantCondiciones) + 1) / 4;
        //verificar condiciones repetidas

        $condicionesizq[] = $salvacondiciones[0];
        $simbolo[] = $salvacondiciones[1];
        $condicionesder[] = $salvacondiciones[2];
        for ($i = 3; $i < count($salvacondiciones); $i++) {
            $condicionesizq[] = $salvacondiciones[$i];
            $simbolo[] = $salvacondiciones[$i + 1];
            $condicionesder[] = $salvacondiciones[$i + 2];
            $i+=3;
        }

        for ($i = 0; $i < (count($condicionesizq) - 1); $i++) {
            for ($j = $i + 1; $j < count($condicionesizq); $j++) {
                if ($condicionesizq[$i] == $condicionesizq[$j]) {
                    if ($simbolo[$i] == $simbolo[$j])
                        if ($condicionesder[$i] == $condicionesder[$j]) {
                            ZendExt_MessageBox::show('Existen condiciones repetidas.', ZendExt_MessageBox::ERROR);
                            return;
                        }
                }
            }
        }
        if ($cantCondiciones >= $cantTablas - 1) {
            if ($activovalidado[0]['activado']) {
                $registro = new DatRegistroProceso();
                $registro->fecha = date("d-m-y H:i:s");
                $registro->accion = "desactivado";
                $registro->id_proceso = $_SESSION['idP'];
                $actM = new DatRegistroProcesoModel();
                $actM->Insertar($registro);
            }

            $proceso = DatProcess::activarProceso($_SESSION['idP']);

            $proceso->validado = 0;
            $versionado = DatRegistroProceso::getRegistros($_SESSION['idP']);
            //print_r($versionado);die;
            if (count($versionado) > 0) {
                $proceso->modificarversion = 1;
            }
            $proceso->activado = 0;
            $m = new DatProcessModel();
            $m->Modificar($proceso);





            $resultatribute = $this->getRequest()->getPost('resultatribute');
            $resultatribute = explode(',', $resultatribute);
            $resulcondiciones = $this->getRequest()->getPost('resulcondiciones');
            $resulcondiciones = explode(',', $resulcondiciones);
            $groupby = $this->getRequest()->getPost('groupby');
            /////guardar atributos
            $DatEvent = Doctrine::getTable('DatEvento')->find($_SESSION['idE']);

            if ($resultatribute[0] != "null") {
                $DatEvent->conceptinstance = $resultatribute[0];
                $DatEvent->accconceptinstance = $resultatribute[1];
            } else {
                $DatEvent->conceptinstance = "";
                $DatEvent->accconceptinstance = "";
            }
            if ($resultatribute[2] != "null") {
                $DatEvent->conceptname = $resultatribute[2];
                $DatEvent->accconceptname = $resultatribute[3];
            } else {
                $DatEvent->conceptname = "";
                $DatEvent->accconceptname = "";
            }
            if ($resultatribute[4] != "null") {
                $DatEvent->orggroup = $resultatribute[4];
                $DatEvent->accorggroup = $resultatribute[5];
            } else {
                $DatEvent->orggroup = "";
                $DatEvent->accorggroup = "";
            }
            if ($resultatribute[6] != "null") {
                $DatEvent->orgresource = $resultatribute[6];
                $DatEvent->accorgresource = $resultatribute[7];
            } else {
                $DatEvent->orgresource = "";
                $DatEvent->accorgresource = "";
            }
            if ($resultatribute[8] != "null") {
                $DatEvent->orgrole = $resultatribute[8];
                $DatEvent->accorgrole = $resultatribute[9];
            } else {
                $DatEvent->orgrole = "";
                $DatEvent->accorgrole = "";
            }
            if ($resultatribute[10] != "null") {
                $DatEvent->timestamp = $resultatribute[10];
                $DatEvent->acctimestamp = $resultatribute[11];
            } else {
                $DatEvent->timestamp = "";
                $DatEvent->acctimestamp = "";
            }
            if ($resultatribute[12] != "null") {
                $DatEvent->semanticmodelreference = $resultatribute[12];
            }
            else
                $DatEvent->semanticmodelreference = "";
            //$DatEvent->save();
            //guardando las condiciones
            $condicionesaguardar = '{';
            foreach ($resulcondiciones as $condici) {
                if ($condicionesaguardar == '{')
                    $condicionesaguardar.= $condici;
                else
                    $condicionesaguardar.=',' . $condici;
            }
            $condicionesaguardar.='}';
            $DatEvent->condiciones = $condicionesaguardar;
            $DatEvent->piid = $groupby;
            $DatEvent->pl = $pls;
            $DatEvent->save();



            echo"{'state':1}";
            return;
        }
        else {
            echo json_encode(7);
            return;
        }
    }

}

?>
