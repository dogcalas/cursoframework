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

class ProcessadministrationController extends ZendExt_Controller_Secure {

    protected $conn;
    protected $proceso;

    function init() {
        parent::init();
        $this->proceso = ZendExt_ConfProceso_ProcessConfiguration::getInstance();
        $this->_history = new ZendExt_History_ADTHistory ( );
    }

    public function getProceso() {
        return $this->proceso;
    }

    public function setProceso($proceso) {
        $this->proceso = $proceso;
    }

    

  

    function ProcessadministrationAction() {
        $this->render();
    }

   

    function getprocessAction() {
            $start = $this->_request->getPost("start");
	         $limit = $this->_request->getPost("limit");
//  $result = DatConexion::getconexions();
        $result = DatProcess::getproceso($start,$limit);
//print_r($result);die;
        $data = array();
        foreach ($result as $row) {
            $data [] = array("id" => $row['idproceso'], "name" => $row['nombre'], "fuentedatos" => $row['fuentedatos'], "descripcion" => $row['descripcion'], "instancia" => $row['instancia'], "validado" => $row['validado'],"version" => $row['version'], "activado" => $row['activado']);
        }
$cantidada=DatProcess::obtenercantprocesos();
       // $cantidada=40;
        $result = array('cantidad_filas' => $cantidada, 'datos' => $data);
//   print_r($result);die;
// $_SESSION['hasvaluesconexion'] = true;
        echo json_encode($result);
        return;
    }

    function actTrazaDatos() {
        $trace = 'Datos';
        $enabled = (int) (false == true);
//  print_r("asdassda");die;
        $xml = ZendExt_FastResponse :: getXML('traceconfig');
        foreach ($xml->containers->children() as $container) {
            if ((string) $container ['alias'] == $trace) {
                $container['enabled'] = (int) !$enabled;

                $path = Zend_Registry :: getInstance()->config->xml->traceconfig;

                file_put_contents($path, $xml->asXml());
                break;
            }
        }
    }

    function actprocessAction() {
        $id = $this->getRequest()->getPost('id');
		
				$proceso = DatProcess::activarProceso($id);
						if($proceso->modificarversion==1){
						$proceso->version=($proceso->version+1);
						$proceso->modificarversion=0;
						$m = new DatProcessModel();
						$m->Modificar($proceso);
							}
						
						
						 
						
		
        $validado = DatProcess::getvalidado($id);
        $activado = DatProcess::getactivado($id);
        $activado = $activado[0]["activado"];
        $validado = $validado[0]["validado"];

        if ($validado == 1 && $activado == 0) {

            $this->actTrazaDatos();
            $tablasProceso = DatProcess::gettables($id);
            $tablasProceso = $tablasProceso[0]['tablas'];
            $tablasHistorial = $this->_history->gethistorial();
            $TProcesoH = array();
            $tablasProceso = str_replace('{', '', $tablasProceso);
            $tablasProceso = str_replace('}', '', $tablasProceso);
            $tablasProceso = split(',', $tablasProceso);
            $cont = 0;
            $aCrear = array();
            foreach ($tablasHistorial as $value) {
                $TProcesoH[$cont] = $value['esquema'] . "." . $value['tabla'];
                $cont++;
            }

            $diferencia = array_diff($tablasProceso, $TProcesoH);
            
            $cont = 0;
            foreach ($diferencia as $value => $key) {
                $aCrear[$cont] = split('\.', $key);
                $cont++;
            } $cont = 0;

            $Parahistorial = array();
            foreach ($aCrear as $key) {
                $Parahistorial[$cont]->table_name = $key[1];
                $Parahistorial[$cont]->table_schema = $key[0];
                $cont++;
            }

            $user = $this->global->Perfil->usuario;
            $this->_history->CreateHistorial($Parahistorial, $user);
            $this->activarRegistroProceso($id);
            echo json_encode(0);
        } else {
            if ($validado == 0)
                echo json_encode(1);
            else if ($activado == 1)
                echo json_encode(2);
        }
    }

    function deacprocessAction() {
        $id = $this->getRequest()->getPost('id');
        $activado = DatProcess::getactivado($id);
        $activado = $activado[0]["activado"];
// print_r($activado);die;

        if ($activado == 1) {
            $proceso = DatProcess::activarProceso($id);
            $proceso->activado = 0;
            $m = new DatProcessModel();
            $m->Modificar($proceso);

            $registro = new DatRegistroProceso();
            $registro->fecha = date("d-m-y H:i:s");
            $registro->accion = "desactivado";
            $registro->id_proceso = $id;
            $actM = new DatRegistroProcesoModel();
            $actM->Insertar($registro);
            echo json_encode(0);
        } else {

            echo json_encode(1);
        }
    }

//busca y ajusta las tablas usadas:::: falta enlaces y condiciones
    function valprocessAction() {
//  print_r(time());die;
        $id = $this->getRequest()->getPost('id');
        $proceso = DatProcess::setproceso($id);
        if ($proceso[0]->validado == 0) {
//tablas usadas por los eventos del proceso
            $tablasUsadas = $this->tablasUsadas($id);
//print_r($tablasUsadas);die;
//verifica que no hayan quedado campos en blanco en la configuración
            $verificarVacios = $this->verificarVacios($id);
// print_r($verificarVacios);die;
            if ($verificarVacios == 1) {
                echo json_encode(2);
                return;
            }
            $verificarAtributosXES = $this->verificarAtributosXES($id);
            if ($verificarAtributosXES == 1) {
                echo json_encode(8);
                return;
            }
            
            $verificarTDatos = $this->verificarTDatos($id);
            $verificarCantCondiciones=$this->verificarCantCondiciones($id);
//  print_r("fasdfafas".$verificarTDatos);die;
            if ($verificarTDatos == 1) {
                echo json_encode(3);
                return;
            }
             if ($verificarCantCondiciones == 7) {
                echo json_encode(7);
                return;
            }




            $tablasseleccionadas = (string) $proceso[0]->tablas;
//print_r($tablasseleccionadas);die;
            $tablasseleccionadas = split(',', $tablasseleccionadas);
            $tablasselok = array();
            foreach ($tablasseleccionadas as $value) {

                $b = str_replace('{', "", $value);
                $tablasselok[] = str_replace('}', "", $b);
//($value);die;
            }
            $tablasseleccionadas = $tablasselok;
            
                $instanc=$proceso[0]->instancia;
                $tablasUsadas[]=$instanc;
               
                $tablasUsadas=  array_unique($tablasUsadas);
               
                 $numer=  array_search("", $tablasUsadas);
         
                 if($numer>0){
                     unset($tablasUsadas[$numer]);
                 }
              //print_r($tablasUsadas);die;
            $diferencia = array();
            $diferencia = array_diff($tablasUsadas, $tablasseleccionadas);
            $diferencia=  array_unique($diferencia);
//print_r($tablasseleccionadas);die;
            if (count($diferencia) > 1) {
                echo json_encode(1);
                return;
            }
            
            if (count($tablasUsadas) < count($tablasseleccionadas)) {
                $string = "{";
                $cont = 0;
// print_r($tablasUsadas);die;

                foreach ($tablasUsadas as $value) {
                    if ($cont == 0) {
                        $string = $string . $value;
                        $cont++;
                    }
                    else{
                    $string = $string . "," . $value;
                    }
                }
                $string = $string . "}";
 //print_r($string);die;
                $proceso[0]->tablas = $string;
                $proceM = new DatProcessModel();
                $proceM->Modificar($proceso);
                $procesox = DatProcess::activarProceso($id);
                $procesox->validado = 1;

                $m = new DatProcessModel();
                $m->Modificar($procesox);

                echo json_encode(0);
                return;
            }
           else if ((count($tablasUsadas) - 1) == count($tablasseleccionadas)) {
                $string = "{";
                $cont = 0;
// print_r($tablasUsadas);die;
                foreach ($tablasUsadas as $value) {
                    if ($cont == 0) {
                        $string = $string . $value;
                        $cont++;
                    }
                    else            {
                        $string = $string . "," . $value;
                    }
                }
                $string = $string . "}";
                $proceso[0]->tablas = $string;
                $proceM = new DatProcessModel();
                $proceM->Modificar($proceso);
            }
            $procesox = DatProcess::activarProceso($id);
            $procesox->validado = 1;

            $m = new DatProcessModel();
            $m->Modificar($procesox);
            echo json_encode(0);
            return;
        } else {
            echo json_encode(4);
            return;
///ibas a empatar los arreglos con comas para actualizar lo de las tablas de los procesos
//  $proceso[0]->tablas=
        }


//  echo json_encode(0);
    }

   

    function activarRegistroProceso($idProceso) {
        $activador = new DatRegistroProceso();
        $fecha = date("d-m-y H:i:s");
        $accion = "activado";
        $activador->fecha = $fecha;
        $activador->id_proceso = (int) $idProceso;
        $activador->accion = (string) $accion;
        $actM = new DatRegistroProcesoModel();
        $actM->Insertar($activador);



        $proceso = DatProcess::activarProceso($idProceso);
        $proceso->activado = 1;
        $m = new DatProcessModel();
        $m->Modificar($proceso);
//print_r(  $activador);die;
//DatRegistroProceso::activarProceso($id);
    }

   

//devuelve las tablas que realmente fueron usadas por el proceso
    public function tablasUsadas($id) {
        $events = DatEvento::geteventprocesoall($id);
      //  print_r($events);die;
//  print_r($events);die;
        $arr = array();
        foreach ($events as $value) {
            if ($value["piid"] != "") {
                $ax = $value["piid"];
                $num = strripos($ax, ".");
                $arr[] = substr($ax, 0, $num);
            }
            if ($value["conceptname"] != "") {
                if ($value["accconceptname"] == "no action") {
                    $arr[] = "mod_traza.dat_evento";
                } else {
                    $ax = $value["conceptname"];
                    $num = strripos($ax, ".");
                    $arr[] = substr($ax, 0, $num);
                }
            }
            if ($value["conceptinstance"] != "") {
                if ($value["accconceptinstance"] == "no action") {
                    $arr[] = "mod_traza.dat_evento";
                } else {
                    $ax = $value["conceptinstance"];
                    $num = strripos($ax, ".");
                    $arr[] = substr($ax, 0, $num);
                }
            }
            if ($value["orgresource"] != "") {
                if ($value["accorgresource"] == "no action") {
                    $arr[] = "mod_traza.dat_evento";
                } else {
                    $ax = $value["orgresource"];
                    $num = strripos($ax, ".");
                    $arr[] = substr($ax, 0, $num);
                }
            }
            if ($value["timestamp"] != "") {
                if ($value["acctimestamp"] == "no action") {
                    $arr[] = "mod_traza.dat_evento";
                } else {
                    $ax = $value["timestamp"];
                    $num = strripos($ax, ".");
                    $arr[] = substr($ax, 0, $num);
                }
            }
            if ($value["orgrole"] != "") {
                if ($value["accorgrole"] == "no action") {
                    $arr[] = "mod_traza.dat_evento";
                } else {
                    $ax = $value["orgrole"];
                    $num = strripos($ax, ".");
                    $arr[] = substr($ax, 0, $num);
                }
            }
            if ($value["orggroup"] != "") {
                if ($value["accorggroup"] == "no action") {
                    $arr[] = "mod_traza.dat_evento";
                } else {
                    $ax = $value["orggroup"];
                    $num = strripos($ax, ".");
                    $arr[] = substr($ax, 0, $num);
                }
            }
            if ($value["condiciones"] != "") {
                $aux = $value["condiciones"];
                $aux = split(",", $aux);
                for ($index = 0; $index < count($aux); $index++) {


//   print_r($as);die;
                    if (count(split("\.", $aux[$index])) > 1) {


                        $ax = str_replace('{', "", $aux[$index]);
                        $ax = str_replace('}', "", $ax);

                        $num = strripos($ax, ".");

                        $arr[] = substr($ax, 0, $num);
// print_r(str_replace('{',"", $aux[$index]));die;
                    }
                }
// print_r($value["condiciones"]);
            }
            
            if ($value["pl"] != "" && $value["pl"] != "{}") {
                $aux = $value["pl"];
             //  print_r($aux);die;
                        $aux = str_replace('}', "", $aux);
                        $aux = str_replace('{', "", $aux);
                         $aux = str_replace('"', "", $aux);
                $aux = split(",", $aux);
              // print_r($aux);//die;
                for ($index = 0; $index < count($aux); $index+=3) {
                     if ($aux[$index+2]=="no action") {
                     }else{
                        $num = strripos($aux[$index+1], ".");
                       // print_r(substr($aux[$index+1], 0, $num));die;
                        $arr[] = substr($aux[$index+1], 0, $num);
                     }
                }
// print_r($value["condiciones"]);
            }

            $tablasok = array();
        }

        $tablasok = array_unique($arr);

 //print_r($tablasok);die;
        return $tablasok;
    }
    
        public function tablasUsadassincondiciones($id) {
        $events = DatEvento::getevent($id);

//  print_r($events);die;
        $arr = array();
        foreach ($events as $value) {
            if ($value["piid"] != "") {
                $ax = $value["piid"];
                $num = strripos($ax, ".");
                $arr[] = substr($ax, 0, $num);
            }
            if ($value["conceptname"] != "") {
                if ($value["accconceptname"] == "no action") {
                    $arr[] = "mod_traza.dat_evento";
                } else {
                    $ax = $value["conceptname"];
                    $num = strripos($ax, ".");
                    $arr[] = substr($ax, 0, $num);
                }
            }
            if ($value["conceptinstance"] != "") {
                if ($value["accconceptinstance"] == "no action") {
                    $arr[] = "mod_traza.dat_evento";
                } else {
                    $ax = $value["conceptinstance"];
                    $num = strripos($ax, ".");
                    $arr[] = substr($ax, 0, $num);
                }
            }
            if ($value["orgresource"] != "") {
                if ($value["accorgresource"] == "no action") {
                    $arr[] = "mod_traza.dat_evento";
                } else {
                    $ax = $value["orgresource"];
                    $num = strripos($ax, ".");
                    $arr[] = substr($ax, 0, $num);
                }
            }
            if ($value["timestamp"] != "") {
                if ($value["acctimestamp"] == "no action") {
                    $arr[] = "mod_traza.dat_evento";
                } else {
                    $ax = $value["timestamp"];
                    $num = strripos($ax, ".");
                    $arr[] = substr($ax, 0, $num);
                }
            }
            if ($value["orgrole"] != "") {
                if ($value["accorgrole"] == "no action") {
                    $arr[] = "mod_traza.dat_evento";
                } else {
                    $ax = $value["orgrole"];
                    $num = strripos($ax, ".");
                    $arr[] = substr($ax, 0, $num);
                }
            }
            if ($value["orggroup"] != "") {
                if ($value["accorggroup"] == "no action") {
                    $arr[] = "mod_traza.dat_evento";
                } else {
                    $ax = $value["orggroup"];
                    $num = strripos($ax, ".");
                    $arr[] = substr($ax, 0, $num);
                }
            }
             if ($value["pl"] != "" && $value["pl"] != "{}") {
                $aux = $value["pl"];
              
                        $aux = str_replace('}', "", $aux);
                        $aux = str_replace('{', "", $aux);
                         $aux = str_replace('"', "", $aux);
                $aux = split(",", $aux);
               // print_r($aux);die;
                for ($index = 0; $index < count($aux); $index+=3) {
                     if ($aux[$index+2]=="no action") {
                     }else{
                        $num = strripos($aux[$index+1], ".");

                        $arr[] = substr($aux[$index+1], 0, $num);
                     }
                }
// print_r($value["condiciones"]);
            }
          
            

            $tablasok = array();
        }

        $tablasok = array_unique($arr);

 //print_r($tablasok);die;
        return $tablasok;
    }

    public function verificarVacios($id) {

        $events = DatEvento::geteventprocesoall($id);
        

//   print_r($events);die;
// $arr=array();
        $cont = 0;
        foreach ($events as $value) {
            if ($value["piid"] == "") {
                $cont++;
            }
            /* if ($value["conceptname"] == "") {
              $cont++;
              }
              if ($value["accconceptname"] == "") {
              $cont++;
              } */
            if ($value["conceptinstance"] == "") {
                $cont++;
            }
            if ($value["accconceptinstance"] == "") {
                $cont++;
            }
            /* if ($value["orgresource"] == "") {
              $cont++;
              }
              if ($value["accorgresource"] == "") {
              $cont++;
              } */
            /*if ($value["timestamp"] == "") {
                $cont++;
            }
            if ($value["acctimestamp"] == "") {
                $cont++;
            }*/
            /* if ($value["orgrole"] == "") {
              $cont++;
              }
              if ($value["accorgrole"] == "") {
              $cont++;
              } */
         /*   if ($value["orggroup"] == "") {
                $cont++;
            }
            if ($value["accorggroup"] == "") {
                $cont++;
            }*/


            /*if ($value["condiciones"] == "") {
                $cont++;
            }*/
        }

        if ($cont > 0) {
//existen campos vacios
            return 1;
        } else {
            return 0;
        }
    }

    public function verificarTDatos($id) {

        
        
        
       
        $events = DatEvento::geteventprocesoall($id);
        
        $prueba = 0;
        $arr = array();
        $verifCondicionesEnlazadas = array();
        $inc = 0;
        foreach ($events as $value) {
            if ($value["condiciones"] != "{}") {
                $b = str_replace('{', "", $value["condiciones"]);
                $arr[] = split(",", str_replace('}', "", $b));

            }
        }

        foreach ($arr as $value) {
            for ($index = 0; $index < (count($value) + 1); $index++) {
//  print("as");

                if (count(split("'", $value[$index])) > 1 || count(split("'", $value[$index + 2])) > 1)
                    $validaenla = false;
                else {
                    $validaenla = true;
                }
                if ($index == 0) {
                    if ($validaenla) {
                        $verifCondicionesEnlazadas[$inc][] = $value[$index];
                        $verifCondicionesEnlazadas[$inc++][] = $value[$index + 2];
                    }
                    $izq = $value[$index];
                    $simbol = $value[$index + 1];
                    $der = $value[$index + 2];
                    $prueba+= (int) $this->probar($izq, $simbol, $der);
// print_r($izq." ".$simbol."   ".$der);//die("ad");
// print_r($prueba."OKa");
                    $index = $index + 2;
                } else {
                    if ($index + 2 < count($value)) {
                        if ($validaenla) {
                            $verifCondicionesEnlazadas[$inc][] = $value[$index];
                            $verifCondicionesEnlazadas[$inc++][] = $value[$index + 2];
                        }
                        $izq = $value[$index];
                        $simbol = $value[$index + 1];
                        $der = $value[$index + 2];
                        $prueba+= (int) $this->probar($izq, $simbol, $der);
// $prueba+= (int) $this->probar($izq,$simbol,$der);
//  print_r($prueba."OKb");
                        $index = $index + 3;
                    }
                }
                   if($prueba>=1){
                  return 1;
                  } 
            }
        }/*
        $tablasUsadas = $this->tablasUsadassincondiciones($id);
       // $verifCondicionesEnlazadas;
         $instanciaproceso = DatProcess::setproceso($id);
         $instanciaproceso=$instanciaproceso[0]["instancia"];
	  // print_r($tablasUsadas);die;
         $tablasUsadas[]=$instanciaproceso;
         $tablasUsadas=  array_unique($tablasUsadas);
        $contador = 0;
        foreach ($verifCondicionesEnlazadas as $value) {
            $varr = split("\.", $value[0]);
            $var1 = $varr[0] . "." . $varr[1];
            $verifCondicionesEnlazadas[$contador][0] = $var1;
            $varr = split("\.", $value[1]);
            $var2 = $varr[0] . "." . $varr[1];
            $verifCondicionesEnlazadas[$contador++][1] = $var2;
        }
        $numer=  array_search("", $tablasUsadas);
       
       unset($tablasUsadas[$numer]);
        //print_r($tablasUsadas);die;
		if(count($tablasUsadas)>=2){
        foreach ($tablasUsadas as $tabla) {     
            if (!$this->buscartablaenenlace($tabla, $verifCondicionesEnlazadas)){
               
                return 7;
            }
        }
		}



        

        if ($prueba > 0)
            return 1;

        return 0;
//   print_r($arr);die;
         
         */
        
    }

    function buscartablaenenlace($tabla, $enlaces) {
        
        foreach ($enlaces as $enlace) {
            
            if ($enlace[0] != $enlace[1]){
                if ($enlace[0] == $tabla || $enlace[1] == $tabla) {
                    return true;
                }
               
            }
        }
        
        return false;
    }

    public function probar($izq, $simbol, $der) {

        if (count(split("'", $izq)) > 1) {
            $from = strrpos($der, '.');
            $from = substr($der, 0, $from);
            $sql = "select " . $der . " from " . $from . " where " . $izq . $simbol . $der . ";";
        } else if (count(split("'", $der)) > 1) {
            $from = strrpos($izq, '.');
            $from = substr($izq, 0, $from);
            $sql = "select " . $izq . " from " . $from . " where " . $izq . $simbol . $der . ";";
        } else {
            $from = strrpos($izq, '.');
            $from2 = strrpos($der, '.');
            $from = substr($izq, 0, $from);
            $from2 = substr($der, 0, $from2);
            $sql = "select " . $izq . "," . $der . " from " . $from . "," . $from2 . " where " . $izq . $simbol . $der . ";";
        }
//print_r($sql);die;
        /* $from = substr($izq, 0, $from);

          $sql = "select " . $izq . " from " . $from . " where " . $izq . $simbol . $der . ";";
          //print_r($sql);die; */
        $con = new ZendExt_History_Db_Concrete();

        $result = $con->query($sql);
// print($result);
        if ($result == "") {
            return 1;
        }
        if ($result != "") {
            return 0;
        }
// return $result;
    }

    public function generartrazasprocesoAction() {
        $idp = $this->_request->getPost('idp');
        $registro=DatRegistroProceso::getRegistros($idp);
       // print_r(count);die;
       if(count($registro)>0) {
        $ProcessConfiguration = ZendExt_ConfProceso_ProcessConfiguration::getInstance();
        $events = DatEvento::getidevent($idp);
        foreach ($events as $event){
            if(!($ProcessConfiguration->precreatesql($event['idevento'], $idp))){
				echo"{'estado':0}";
				return;
			}
				
		}
		if(!$ProcessConfiguration->vaciarregistroproceso($idp)){
			echo"{'estado':0}";
				return;
			}
			$cantidad=$ProcessConfiguration->getCantidadtrazas();
			if(!$cantidad)
				$cantidad=0;
			
		echo"{'cantidad':".$cantidad.",'estado':1}";
		return;
    }
    else{
        echo json_encode(4);
		return;
        
    }
}

    public function verificarCantCondiciones($idproceso) {
     $events=DatEvento::geteventprocesoall($idproceso);   
   
foreach ($events as $value) {
     // print_r($value);die;
      $tablasUsadas = $this->tablasUsadassincondiciones($value['idevento']);
       $instanciaproceso = DatProcess::setproceso($idproceso);
       $instanciaproceso=$instanciaproceso[0]["instancia"];
       $tablasUsadas[]=$instanciaproceso;
       
       $numer=  array_search("", $tablasUsadas);
       if($numer>0){
       unset($tablasUsadas[$numer]);
       }
      $tablasUsadas=  array_unique($tablasUsadas);
       $condicionesEntreTablasdelEvento=  $this->condicionesEntreTablasEvento($value);
      // print_r($tablasUsadas);die;
       if(count($tablasUsadas)>=2){
           if(count($condicionesEntreTablasdelEvento)==0){
               return 7;
               
           }
       foreach ($tablasUsadas as $tabla) {  
           //$nombretabla=split(",",$tabla);
          
            if (!$this->buscartablaenenlace($tabla, $condicionesEntreTablasdelEvento)){
               
       return 7;}
       
            }
}
}
        
        
        
    }

    public function condicionesEntreTablasEvento($evento) {
        $condicionesAChequear=array();
        $contadora=0;
       $condiciones=$evento['condiciones'];
        if($condiciones!="" && $condiciones!="{}"){
          $condiciones = str_replace('{', "",$condiciones);
          $condiciones = split(",", str_replace('}', "", $condiciones));
           for ($index = 0; $index < (count($value) + 1); $index++) {
               
               if (count(split("'", $condiciones[$index])) > 1 || count(split("'", $condiciones[$index + 2])) > 1)
                    $validaenla = false;
                else {
                    $validaenla = true;
                }
                if($index==0){
                 if ($validaenla) {
                        $posi= strripos($condiciones[$index], ".");
                        $nombretabla=  substr($condiciones[$index], 0, $posi);
                        $condicionesAChequear[$contadora][] = $nombretabla;
                        
                        $posi= strripos($condiciones[$index+2], ".");
                        $nombretabla=  substr($condiciones[$index+2], 0, $posi);
                        $condicionesAChequear[$contadora++][] = $nombretabla;
                    }
                }else{
                if ($index + 2 < count($value)) {
                        if ($validaenla) {
                             $posi= strripos($condiciones[$index], ".");
                        $nombretabla=  substr($condiciones[$index], 0, $posi);
                        $condicionesAChequear[$contadora][] = $nombretabla;
                        
                        $posi= strripos($condiciones[$index+2], ".");
                        $nombretabla=  substr($condiciones[$index+2], 0, $posi);
                        $condicionesAChequear[$contadora++][] = $nombretabla;
                        }
                 }
                
                        }   }
                        return $condicionesAChequear;
                       
          // print_r($condicionesAChequear);die;
        
       }
    
    
    
        }

    public function verificarAtributosXES($idproceso) {
       $arr=array();
        $events=DatEvento::geteventprocesoall($idproceso);   
        foreach ($events as $value) {
            if ($value["conceptname"] != "") {
                if ($value["accconceptname"] == "no action") {
                   
                } else {
                    $arr[] = $value["conceptname"];
                   
                }
            }
            if ($value["conceptinstance"] != "") {
                if ($value["accconceptinstance"] == "no action") {
                    
                } else {
                    $arr[] = $value["conceptinstance"];
                          }
            }
            if ($value["orgresource"] != "") {
                if ($value["accorgresource"] == "no action") {
                    
                } else {
                    $arr[] = $value["orgresource"];
                         }
            }
            if ($value["timestamp"] != "") {
                if ($value["acctimestamp"] == "no action") {
                    
                } else {
                    $arr[] = $value["timestamp"];
                            }
            }
            if ($value["orgrole"] != "") {
                if ($value["accorgrole"] == "no action") {
                    
                } else {
                    $arr[] = $value["orgrole"];
                    
                }
            }
            if ($value["orggroup"] != "") {
                if ($value["accorggroup"] == "no action") {
                   
                } else {
                    $arr[] = $value["orggroup"];
                     }
            }
             if ($value["pl"] != "" && $value["pl"] != "{}") {
                $aux = $value["pl"];
              
                        $aux = str_replace('}', "", $aux);
                        $aux = str_replace('{', "", $aux);
                         $aux = str_replace('"', "", $aux);
                $aux = split(",", $aux);
               // print_r($aux);die;
                for ($index = 0; $index < count($aux); $index+=3) {
                     if ($aux[$index+2]=="no action") {
                     }else{
                           $arr[] = $aux[$index+1];
                     }
                }

            }
           
            

            
        }
       // 
        foreach ($arr as $value) {
        $pos=  strripos($value, ".");
         $tabla=  substr($value, 0,$pos);   
          $sql = "select " . $value . " from " . $tabla . ";";
            $con = new ZendExt_History_Db_Concrete();

        $result = $con->query($sql);

        if ($result == "") {
            return 1;
        }
        if ($result != "") {
            $flag= 0;
        } 
        }
        return $flag;
        
        
        
        
    }
    
    
}

?>
