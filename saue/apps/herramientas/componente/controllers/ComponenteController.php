<?php

/*
 * Componente para gestionar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */

class ComponenteController extends ZendExt_Controller_Secure {

    private $manager;

    function init() {
        parent::init();
        $this->manager = ZendExt_Component_Manager::getInstance();
    }

    function ComponenteAction() {
        $bundles = $this->manager->getBundlesList();
        if ($bundles == null) {
            $this->manager->searchBundles();
            $this->manager->solveBundles();
        }
        $this->render();
    }

    /////////////////Componentes////////////////////////|
    function insertarbundleAction() {
        $bundles = $this->manager->getBundlesList();

        $direccion = $this->_request->getPost('direccion');

        $direccion = $_SERVER['DOCUMENT_ROOT'] . $direccion;
        $direccion = str_replace("web", "apps", $direccion);


        if (!is_file($direccion)) {
            $this->crearArchivoXML($direccion);
        }

        $this->manager->setBundleEnable($direccion);
        $bundle = $this->manager->loadBundleFromFile($direccion);
        if ($bundle == null) {
            echo"{'codMsg':3,'mensaje': 'El directorio no contiene un componente.'}";
            return;
        } else {
            if (!$this->manager->addBundle($bundle)) {
                echo"{'codMsg':3,'mensaje': 'El componente ya fue registrado.'}";
                return;
            }


            $xml = $this->manager->bundlesListToXml();
            $this->manager->saveXML($xml);

            echo"{'codMsg':1,'mensaje': 'El componente ha sido registrado satisfactoriamente.'}";
            return;
        }
    }

    function habilitarComponenteAction() {
        $nombre = $this->_request->getPost('nombre');
        $direccion = $this->_request->getPost('direccion');
        $pos = strpos($direccion, 'apps');
        $direccion = substr($direccion, $pos + 4);
        $direccion = $_SERVER['DOCUMENT_ROOT'] . $direccion;
        $direccion = str_replace("web", "apps", $direccion);
        $direccion = $direccion . DIRECTORY_SEPARATOR . $nombre . ".scl";

        if (file_exists($direccion)) {
            $this->manager->setBundleEnable($direccion);
            $bundle = $this->manager->loadBundleFromFile($direccion);
            if ($bundle == null) {
                echo"{'codMsg':3,'mensaje': 'El directorio no contiene un componente.'}";
                return;
            } else {
                if (!$this->manager->addBundle($bundle)) {
                    echo"{'codMsg':3,'mensaje': 'El componente ya fue habilitado.'}";
                    return;
                }


                $xml = $this->manager->bundlesListToXml();
                $this->manager->saveXML($xml);
                echo"{'codMsg':1,'mensaje': 'El componente ha sido habilitado satisfactoriamente.'}";
                return;
            }
        } else {
            echo"{'codMsg':3,'mensaje': 'No existe el archivo.'}";
            return;
        }
    }

    function modificarbundleAction() {
        $id = $this->_request->getPost('id');
        $nombre = $this->_request->getPost('nombre');
        $version = $this->_request->getPost('version');
        $direccion = $this->_request->getPost('direccion');
        $direccion = $_SERVER['DOCUMENT_ROOT'] . $direccion;
        $direccion = str_replace("web", "apps", $direccion);



        if (!is_dir($direccion)) {
            echo"{'codMsg':3,'mensaje': 'El directorio no existe.'}";
            return;
        } else if (!file_exists($direccion . DIRECTORY_SEPARATOR . $nombre . ".scl")) {
            echo"{'codMsg':3,'mensaje': 'El fichero \"$nombre.scl\" no existe en el directorio especificado.'}";
            return;
        } else {
            $result = $this->manager->setBundleComponent($id, $nombre, $version, $direccion . DIRECTORY_SEPARATOR . $nombre . ".scl");

            if ($result == FALSE) {
                echo"{'codMsg':3,'mensaje': 'El directorio no contiene un componente.'}";
                return;
            } else {
                $this->manager->saveBundlesList();
                echo"{'codMsg':1,'mensaje': 'El componente ha sido modificado satisfactoriamente.'}";
                return;
            }
        }
    }

    function eliminarBundleAction() {

        $direccion = $this->_request->getPost('direccion');
        $nombre = $this->_request->getPost('nombre');

        $pos = strpos($direccion, 'apps');
        $direccion = substr($direccion, $pos + 4);

        $result = $this->manager->unregisterBundle($direccion . DIRECTORY_SEPARATOR . $nombre . ".scl");
        if ($result == FALSE) {
            echo"{'codMsg':3,'mensaje': 'No se ha deshabilitado el componente.'}";
            return;
        } else {

            echo"{'codMsg':1,'mensaje': 'El componente ha sido deshabilitado satisfactoriamente.'}";
            return;
        }
    }

    function selArbolAction() {
        $direccio = $this->_request->getPost('direccion');
        $nombre = $this->_request->getPost('nombre');
        $pos = strpos($direccio, 'apps');
        $dir = substr($direccio, $pos + 4);


        $direccion = $_SERVER['DOCUMENT_ROOT'] . $dir;
        $direccion = str_replace("web", "apps", $direccion);
        $direccion = $direccion . DIRECTORY_SEPARATOR . $nombre . ".scl";

        $bundles = $this->manager->getBundlesList();
        if (is_file($direccion)) {
            foreach ($bundles as $bundle) {
                if ($bundle->getName() == $nombre) {
                    if ($bundle->getState() == 'solved') {
                        $est = "resuelto";
                    } else {
                        $est = "no resuelto";
                    }

                    $servicios = $bundle->getListaService();
                    if (count($servicios) != 0) {
                        foreach ($servicios as $service) {

                            $services[] = $service->getId();
                        }
                        $serv = implode(" , ", $services);
                    } else {
                        $serv = "";
                    }

                    $dependencias = $bundle->getListaDependencias();
                    if (count($dependencias) != 0) {
                        foreach ($dependencias as $dependency) {
                            $dependencies[] = $dependency->getId();
                        }
                        $dep = implode(" , ", $dependencies);
                    } else {
                        $dep = "";
                    }
                    $eventos = $bundle->getListaSourceEvent();
                    if (count($eventos) != 0) {
                        foreach ($eventos as $sourceEvent) {
                            $sources[] = $sourceEvent->getId();
                        }
                        $event = implode(" , ", $sources);
                    } else {
                        $event = "";
                    }


                    $observadores = $bundle->getListaObserver();
                    if (count($observadores) != 0) {
                        foreach ($observadores as $observer) {
                            $observers[] = $observer->getSource();
                        }
                        $obs = implode(" , ", $observers);
                    } else {
                        $obs = "";
                    }

                    $data [] = array("id" => $bundle->getId(),
                        "nombre" => $bundle->getName(),
                        "estado" => $est,
                        "version" => $bundle->getVersion(),
                        "direccion" => $bundle->getPath(),
                        "servicios" => $serv,
                        "dependencias" => $dep,
                        "generados" => $event,
                        "observados" => $obs,
                    );
                }
            }

            //$result = true;
            echo json_encode($data);
            return;
        }
    }

    function crearArchivoXML($direccion) {
        $file = new ZendExt_File($direccion);

        $data = <<<XML
<?xml version='1.0'?>
<bundle>
<data version="1.0" /> 
</bundle>
XML;
        $contenido = new SimpleXMLElement($data);
        $file->mkfile($contenido->asXML());
        chmod($direccion, 0777);
    }

    function cargarCarpetasAppAction() {

        if ($this->_request->getPost('node') == 'root')
            $src = '../../../apps';
        else
            $src = $this->_request->getPost('carpeta');
        $dir = opendir($src);

        $data = array();

        while (false !== ( $file = readdir($dir))) {

            if (( $file != '.' ) && ( $file != '..' )) {

                if (is_dir($src . DIRECTORY_SEPARATOR . $file) && $file != '.svn') {

                    //echo "carpeta ".$src . DIRECTORY_SEPARATOR . $file."<br>";
                    if (file_exists($src . DIRECTORY_SEPARATOR . $file . DIRECTORY_SEPARATOR . $file . '.scl')) {

                        $data[] = array('text' => $file,
                            'dir' => $src . DIRECTORY_SEPARATOR . $file, 'expandable' => true,
                            'icon' => DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'ExtJS' . DIRECTORY_SEPARATOR . 'temas' . DIRECTORY_SEPARATOR . 'resources4' . DIRECTORY_SEPARATOR . 'SauxeIcons' . DIRECTORY_SEPARATOR . 'gris' . DIRECTORY_SEPARATOR . 'productos.png',
                            'leaf' => false,'componente'=>true
                        );
                    } else {
                        $data[] = array('text' => $file,
                            'dir' => $src . DIRECTORY_SEPARATOR . $file, 'expandable' => true,
                            //'icon' => DIRECTORY_SEPARATOR.'herramientas'.DIRECTORY_SEPARATOR.'componente'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'folder.png',
                            'leaf' => false,'componente'=>false
                        );
                    }
                } else {
                    //echo "archivo ".$src . DIRECTORY_SEPARATOR . $file."<br>";
                }
            }
        }


        closedir($dir);

        $result = array('success' => true, 'data' => $data);

        echo json_encode($result);
        return;
    }

    //////////////////Servicios//////////////////////////|
    function cargarServiciosAction() {

        $nombre = $this->_request->getPost('nombre');


        $bundles = $this->manager->getBundlesList();

        $data = array();
        foreach ($bundles as $key => $bundle) {

            if ($bundle->getName() == $nombre) {
                $services = $bundle->getListaService();
                if (count($services) != 0) {
                    foreach ($services as $service) {

                        $data[] = array('id' => $service->getId(), 'interface' => $service->getInterface(), 'impl' => $service->getImpl());
                    }
                }
            }
        }


        $result = array('success' => true, 'data' => $data);


        echo json_encode($result);
        return;
    }

    function insertarServicioAction() {
        $direccion = $this->_request->getPost('direccion');
        $nombre = $this->_request->getPost('nombre');

        $nombreserv = $this->_request->getPost('nombreserv');

        $cantoperaciones = $this->_request->getPost('cantoperaciones');

        $cantparams = $this->_request->getPost('cantparams');
        $cantparams = explode(",", $cantparams);

        $nombresoper = $this->_request->getPost('nombresoper');
        $nombresoper = explode(",", $nombresoper);

        $retornos = $this->_request->getPost('retornos');
        $retornos = explode(",", $retornos);

        $descriopers = $this->_request->getPost('descripoper');
        $descriopers = explode(",", $descriopers);

        $Parametros = $this->_request->getPost('parametros');
        $Parametros = explode("|", $Parametros);

        array_pop($Parametros);


        $ListaOperaciones = array();
        for ($index = 0; $index < $cantoperaciones; $index++) {
            $params = array_slice($Parametros, 0, $cantparams[$index]);
            $Parametros = array_splice($Parametros, $cantparams[$index]);
            $ListaOperaciones[] = array($nombresoper[$index], $retornos[$index], $descriopers[$index], $params);
        }


        $dir = $direccion . DIRECTORY_SEPARATOR . $nombre . '.scl';


        $operaciones = $this->insertarOperacionesenInterfaz($ListaOperaciones);

        $res = $this->CrearInterfaz($nombreserv, $nombre, $direccion, $operaciones);

        if ($res) {
            $xml = simplexml_load_file($dir);
            $id = $nombreserv;
            $aut = new SimpleXMLElement($xml->asXml());
            $nombre = ucfirst($nombre);
            $nombreserv = ucfirst($nombreserv);
            $impl = "services" . DIRECTORY_SEPARATOR . $nombre . $nombreserv . "Impl.php";
            $operacionimpl = $this->insertarOperacionesenClase($ListaOperaciones);
            $otro = $this->crearimplementacion($direccion, $nombre, $nombreserv, $operacionimpl);

            if ($otro) {

                $interface = "services" . DIRECTORY_SEPARATOR . $nombre . $nombreserv . "Interface.php";
                $a=$aut->children();
                foreach ($a as $key => $value) {
                          if((String)$key=="services"){
                             $encontrado=true;
                          }
                }
                
                
                if($encontrado){
                   
                $services = $aut->services->addChild('service');
                $services->addAttribute('id', $id);
                $services->addAttribute('interface', $interface);
                $services->addAttribute('impl', $impl);
                $aut->saveXML($dir);

                $this->manager->searchBundles();
                $this->manager->solveBundles();




                echo"{'codMsg':1,'mensaje': 'Se ha insertado el servicio satisfactoriamente.'}";
                return;
                }else{
                    $services = $aut->addChild('services');
                $services = $aut->services->addChild('service');
                $services->addAttribute('id', $id);
                $services->addAttribute('interface', $interface);
                $services->addAttribute('impl', $impl);
                $aut->saveXML($dir);

                $this->manager->searchBundles();
                $this->manager->solveBundles();




                echo"{'codMsg':1,'mensaje': 'Se ha insertado el servicio satisfactoriamente.'}";
                return;
                }
                
            }
        } else {
            echo"{'codMsg':3,'mensaje': 'No se ha podido insertar el servicio.'}";
            return;
        }
    }

    function insertarOperacionesenInterfaz($ListaOperaciones) {

        $nombreParams = array();
        $var = array();
        $oper = array();

        for ($index = 0; $index < count($ListaOperaciones); $index++) {
            $nombreParams[] = $ListaOperaciones[$index][3];
            $param = array();
            $descrip = array();
            $des = array();
            for ($index1 = 0; $index1 < count($nombreParams[$index]); $index1++) {
                $var = explode(",", $nombreParams[$index][$index1]);

                if ($var[0] == "") {

                    $param[] = '$' . $var[1];
                    $par = "$" . $var[1];
                    $tipo = $var[2];
                    $descrip = $var[3];
                } else {

                    $param[] = "$" . $var[0];
                    $par = "$" . $var[0];
                    $tipo = $var[1];
                    $descrip = $var[2];
                }
                $des[] = " * @param " . $tipo . " " . $par . "-" . $descrip;
            }

            $parametros = implode(", ", $param);
            $descripparam = implode("\n", $des);
            $oper[] = '/**' . "\n"
                    . ' * ' . $ListaOperaciones[$index][2] . "\n" .
                    ' * ' . '@retorno: ' . $ListaOperaciones[$index][1] . "\n"
                    . $descripparam . "\n"
                    . ' */' . "\n" . '  public function ' . $ListaOperaciones[$index][0] . '('
                    . $parametros .
                    ');';
        }
        $Operaciones = implode("\n", $oper);

        return $Operaciones;
    }

    function insertarOperacionesenClase($ListaOperaciones) {
        $nombreParams = array();
        $var = array();
        $oper = array();

        for ($index = 0; $index < count($ListaOperaciones); $index++) {
            $nombreParams[] = $ListaOperaciones[$index][3];
            $param = array();
            $descrip = array();
            $des = array();
            for ($index1 = 0; $index1 < count($nombreParams[$index]); $index1++) {
                $var = explode(",", $nombreParams[$index][$index1]);

                if ($var[0] == "") {

                    $param[] = '$' . $var[1];
                    $par = "$" . $var[1];
                    $tipo = $var[2];
                    $descrip = $var[3];
                } else {

                    $param[] = "$" . $var[0];
                    $par = "$" . $var[0];
                    $tipo = $var[1];
                    $descrip = $var[2];
                }
                $des[] = " * @param " . $tipo . " " . $par . "-" . $descrip;
            }

            $parametros = implode(", ", $param);
            $descripparam = implode("\n", $des);
            $oper[] = '/**' . "\n"
                    . ' * ' . $ListaOperaciones[$index][2] . "\n" .
                    ' * ' . '@retorno: ' . $ListaOperaciones[$index][1] . "\n"
                    . $descripparam . "\n" .
                    ' */' . "\n" . '  public function ' . $ListaOperaciones[$index][0] . '('
                    . $parametros .
                    '){ ' . "\n" . 'return;'
                    . "\n" . '}';
        }
        $Operaciones = implode("\n", $oper);
        return $Operaciones;
    }

    function crearimplementacion($Direccion, $nombre, $nombreserv, $operaciones) {
        $nombreinter = ucfirst($nombre);
        $nombreservinter = ucfirst($nombreserv);
        $nombrearch = $nombreservinter;
        $direccion = $Direccion . DIRECTORY_SEPARATOR . "services" . DIRECTORY_SEPARATOR . $nombreinter . $nombrearch . "Impl.php";
        if (!file_exists($direccion)) {
            $file = new ZendExt_File($direccion);
            $nombrearch = ucfirst($nombrearch);
            $data = '<?php
/**
 *
 * 
 */
class ' . $nombrearch . 'Services' . ' implements ' . $nombreinter . $nombreservinter . 'Interface{
    
' . $operaciones . '
  
}

?>
';
            $file->mkfile($data);
            chmod($direccion, 0777);
            return true;
        } else {
            echo"{'codMsg':3,'mensaje': 'Ya existe una clase de implementación para este servicio.'}";
            return;
        }
    }

    function CrearInterfaz($nombreserv, $nombre, $Direccion, $operaciones) {
        $nombre = ucfirst($nombre);
        $nombreserv = ucfirst($nombreserv);
        $dir = $Direccion . DIRECTORY_SEPARATOR . "services";
        $direccion = $Direccion . DIRECTORY_SEPARATOR . "services" . DIRECTORY_SEPARATOR . $nombre . $nombreserv . "Interface.php";
        if (!is_dir($dir)) {
            mkdir($Direccion . DIRECTORY_SEPARATOR . "services");

            chmod($dir, 0777);

            if (!file_exists($direccion)) {
                $file = new ZendExt_File($direccion);
                $data = '<?php
/**
 *
 * 
 */
interface ' . $nombre . $nombreserv . 'Interface {
' . $operaciones . '
    
    }

?>
';
                $file->mkfile($data);
                chmod($direccion, 0777);
                return true;
            } else {

                echo"{'codMsg':3,'mensaje': 'Ya existe una interfaz para este servicio.'}";
                return;
            }
        } else {
            if (!file_exists($direccion)) {
                $direccion = $Direccion . DIRECTORY_SEPARATOR . "services" . DIRECTORY_SEPARATOR . $nombre . $nombreserv . "Interface.php";

                $file = new ZendExt_File($direccion);
                $data = '<?php
/**
 *
 * @author Cesar
 */
interface ' . $nombre . $nombreserv . 'Interface {
' . $operaciones . '
    
    }

?>
';
                $file->mkfile($data);
                chmod($direccion, 0777);
                return true;
                //return true;    
            } else {
                echo"{'codMsg':3,'mensaje': 'Ya existe una interfaz para este servicio.'}";
                return;
            }
        }
    }

    function eliminarServicioAction() {
        $direc = $this->_request->getPost('direccion');
        $nombre = $this->_request->getPost('nombre');
        $ids = $this->_request->getPost('ids');
        $ids = explode(",", $ids);
        $nombreserv = $this->_request->getPost('nombreserv');
        $dir = $direc . DIRECTORY_SEPARATOR . $nombre . ".scl";
        $pos = strpos($dir, 'apps');
        $dir = substr($dir, $pos + 4);

        $direccion = $_SERVER['DOCUMENT_ROOT'] . $dir;
        $direccion = str_replace("web", "apps", $direccion);
        $bundles = $this->manager->getBundlesList();
        $interface = array();
        $impl = array();

        foreach ($bundles as $bundle) {
            if ($bundle->getName() == $nombre) {
                $services = $bundle->getListaService();
                foreach ($services as $service) {
                    for ($index2 = 0; $index2 < count($ids); $index2++) {
                        if ($service->getId() == $ids[$index2]) {
                            $interface[] = $service->getInterface();
                            $impl[] = $service->getImpl();
                        }
                    }
                }
            }
        }



        $xml = $this->leer_xml($direccion);

        for ($index = 0; $index < count($ids); $index++) {
            foreach ($xml->services->service as $nodo) {

                if ((string) $nodo['id'] == $ids[$index]) {

                    $dom = dom_import_simplexml($nodo);
                    $dom->parentNode->removeChild($dom);
                    $guardar = 1;
                }
            }
        }

        if ($guardar) {
            $xml->asXML($direccion);
            for ($index1 = 0; $index1 < count($ids); $index1++) {
                $pos = strpos($direc, 'apps');
                $direlim = substr($direc, $pos + 4);
                $direlimInt = $direlim . DIRECTORY_SEPARATOR . $interface[$index1];
                $direlimImp = $direlim . DIRECTORY_SEPARATOR . $impl[$index1];
                $direlimInt = $_SERVER['DOCUMENT_ROOT'] . $direlimInt;
                $direlimInt = str_replace("web", "apps", $direlimInt);
                $direlimImp = $_SERVER['DOCUMENT_ROOT'] . $direlimImp;
                $direlimImp = str_replace("web", "apps", $direlimImp);


                unlink($direlimImp);
                unlink($direlimInt);
            }


            $this->manager->searchBundles();
            $this->manager->solveBundles();

            echo"{'codMsg':1,'mensaje': 'Se ha(n) eliminado el(los) servicio(s) correctamente.'}";
            return;
        } else {
            echo"{'codMsg':3,'mensaje': 'No se ha podido eliminar el(los) servicio(s).'}";
            return;
        }
    }

    function cargarOperacionesAction() {
        $nombre = $this->_request->getPost('nombre');
        $id = $this->_request->getPost('id');
        $interface = $this->_request->getPost('interface');
        $impl = $this->_request->getPost('impl');
        $direccion = $this->_request->getPost('direccion');
        $direccion1 = $direccion . DIRECTORY_SEPARATOR . $interface;

        $data = array();

        if (file_exists($direccion1) && file_exists($direccion1)) {
            $contenido = file_get_contents($direccion1);
            if ($contenido != '') {

                $class = substr($contenido, strpos($contenido, "{"), strrpos($contenido, "}"));
                $nueva = substr($class, 1, -1);
                $lineas = explode("\n", $nueva);
                foreach ($lineas as $linea) {
                    if (strstr($linea, ";", true)) {
                        $a = strstr($linea, ";", true);
                        $b = substr($a, strpos($a, "n "), strpos($a, "("));
                        $c = substr($b, 2);
                        $d = explode("(", $c);

                        $nombreoper[] = $d[0];
                    }
                }
                $operaciones = explode(";", $nueva);
                array_pop($operaciones);
                foreach ($operaciones as $operation) {
                    $var = substr($operation, strpos($operation, "/*"), strpos($operation, "*/"));

                    $lineas = explode("\n", $var);
                    array_pop($lineas);
                    array_shift($lineas);
                    $descrip = array_shift($lineas);
                    $retorno = array_shift($lineas);
                    $descrips[] = substr($descrip, 3);
                    $retornos[] = substr($retorno, 13);
                    $param = array();
                    for ($index = 0; $index < count($lineas); $index++) {
                        $par = substr($lineas[$index], 10);
                        $par = explode("$", $par);
                        $tipo = trim($par[0]);
                        $otro = explode("-", $par[1]);
                        $nompar = $otro[0];
                        $des = $otro[1];
                        if ($index == 0) {
                            $param[] = $nompar . ',' . $tipo . ',' . $des . '|';
                        } else {
                            $param[] = $nompar . ',' . $tipo . ',' . $des . '|';
                        }
                    }

                    $cantparam[] = count($param);
                    $parametros[] = $param;
                }

                for ($index1 = 0; $index1 < count($nombreoper); $index1++) {
                    $data[] = array('nombre' => $nombreoper[$index1], 'retorno' => $retornos[$index1], 'descripoper' => $descrips[$index1], 'parametros' => $parametros[$index1], 'cantparm' => $cantparam[$index1]);
                }

                $result = array('success' => true, 'data' => $data);
                echo json_encode($result);
                return;
            } else {
                echo"{'codMsg':3,'mensaje': 'El archivo de la interfaz está vacio.'}";
                return;
            }
        } else {
            echo"{'codMsg':3,'mensaje': 'No existe la interfaz del servicio.'}";
            return;
        }
    }

    function modificarServicioAction() {
        $direc = $this->_request->getPost('direccion');
        $nombre = $this->_request->getPost('nombre');
        $id = $this->_request->getPost('id');
        $nombreserv = $this->_request->getPost('nombreserv');
        $Parametros = $this->_request->getPost('parametros');
        $Parametros = explode("|", $Parametros);
        $cantoperaciones = $this->_request->getPost('cantoperaciones');
        $nombresoper = $this->_request->getPost('nombresoper');
        $nombresoper = explode(",", $nombresoper);
        $retornos = $this->_request->getPost('retornos');
        $retornos = explode(",", $retornos);
        $descriopers = $this->_request->getPost('descripoper');
        $descriopers = explode(",", $descriopers);
        $cantparams = $this->_request->getPost('cantparams');
        $cantparams = explode(",", $cantparams);
        $dir = $direc . DIRECTORY_SEPARATOR . $nombre . ".scl";
        $pos = strpos($dir, 'apps');
        $dir = substr($dir, $pos + 4);

        $direccion = $_SERVER['DOCUMENT_ROOT'] . $dir;
        $direccion = str_replace("web", "apps", $direccion);
        $bundles = $this->manager->getBundlesList();
        foreach ($bundles as $bundle) {
            if ($bundle->getName() == $nombre) {
                $services = $bundle->getListaService();
                foreach ($services as $service) {
                    if ($service->getId() == $id) {
                        $interface = $service->getInterface();
                        $impl = $service->getImpl();
                    }
                }
            }
        }

        $xml = $this->leer_xml($direccion);


        foreach ($xml->services->service as $nodo) {

            if ((string) $nodo['id'] == $id) {

                $dom = dom_import_simplexml($nodo);
                $dom->parentNode->removeChild($dom);
                $guardar = 1;
            }
        }
        if ($guardar) {
            $xml->asXML($direccion);
            $pos = strpos($direc, 'apps');
            $direlim = substr($direc, $pos + 4);
            $direlimInt = $direlim . DIRECTORY_SEPARATOR . $interface;
            $direlimImp = $direlim . DIRECTORY_SEPARATOR . $impl;
            $direlimInt = $_SERVER['DOCUMENT_ROOT'] . $direlimInt;
            $direlimInt = str_replace("web", "apps", $direlimInt);
            $direlimImp = $_SERVER['DOCUMENT_ROOT'] . $direlimImp;
            $direlimImp = str_replace("web", "apps", $direlimImp);


            unlink($direlimImp);
            unlink($direlimInt);

            $this->manager->searchBundles();
            $this->manager->solveBundles();

            $this->ModServ($id, $direc, $nombre, $nombreserv, $Parametros, $cantoperaciones, $nombresoper, $retornos, $descriopers, $cantparams);
        } else {
            echo"{'codMsg':3,'mensaje': 'No se ha podido modificar el servicio.'}";
            return;
        }
    }

    function ModServ($id, $direccion, $nombre, $nombreserv, $Parametros, $cantoperaciones, $nombresoper, $retornos, $descriopers, $cantparams) {
        array_pop($Parametros);

        $ListaOperaciones = array();
        for ($index = 0; $index < $cantoperaciones; $index++) {
            $params = array_slice($Parametros, 0, $cantparams[$index]);
            $Parametros = array_splice($Parametros, $cantparams[$index]);
            $ListaOperaciones[] = array($nombresoper[$index], $retornos[$index], $descriopers[$index], $params);
        }


        $dir = $direccion . DIRECTORY_SEPARATOR . $nombre . '.scl';


        $operaciones = $this->insertarOperacionesenInterfaz($ListaOperaciones);

        $res = $this->CrearInterfaz($nombreserv, $nombre, $direccion, $operaciones);

        if ($res) {
            $xml = simplexml_load_file($dir);
            $id = $nombreserv;
            $aut = new SimpleXMLElement($xml->asXml());
            $nombre = ucfirst($nombre);
            $nombreserv = ucfirst($nombreserv);
            $impl = "services" . DIRECTORY_SEPARATOR . $nombre . $nombreserv . "Impl.php";
            $operacionimpl = $this->insertarOperacionesenClase($ListaOperaciones);
            $otro = $this->crearimplementacion($direccion, $nombre, $nombreserv, $operacionimpl);

            if ($otro) {

                $interface = "services" . DIRECTORY_SEPARATOR . $nombre . $nombreserv . "Interface.php";

                $services = $aut->services->addChild('service');
                $services->addAttribute('id', $id);
                $services->addAttribute('interface', $interface);
                $services->addAttribute('impl', $impl);
                $aut->saveXML($dir);

                $this->manager->searchBundles();
                $this->manager->solveBundles();



                echo"{'codMsg':1,'mensaje': 'Se ha modificado el servicio satisfactoriamente.'}";
                return;
            }
        } else {
            echo"{'codMsg':3,'mensaje': 'No se ha podido modificar el servicio.'}";
            return;
        }
    }

    //////////////////Dependencias////////////////////////|
    function cargarDependenciasAction() {

        $nombre = $this->_request->getPost('nombre');
        $bundles = $this->manager->getBundlesList();

        $data = array();
        foreach ($bundles as $key => $bundle) {

            if ($bundle->getName() == $nombre) {
                $componentes = array();
                $dependencias = $bundle->getListaDependencias();
                if (count($dependencias) != 0) {
                    foreach ($dependencias as $dependency) {
                        $componentes = $dependency->getBundleResolver();

                        $data[] = array('id' => $dependency->getId(), 'interface' => $dependency->getInterface(),
                            'use' => $dependency->getUseComponent() . "-" . $dependency->getUseVersion(), 'optional' => $dependency->isOptional());
                    }
                }
            }
        }


        $result = array('success' => true, 'data' => $data);

        echo json_encode($result);
        return;
    }

    function cargarAllServiciosAction() {
        $bundles = $this->manager->getBundlesList();
        $data = array();
        if ($this->_request->getPost('node') == 'root') {
            foreach ($bundles as $key => $value) {

                $data[] = array('text' => $value->getName(),
                    'expandable' => true,
                    'icon' => DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'ExtJS' . DIRECTORY_SEPARATOR . 'temas' . DIRECTORY_SEPARATOR . 'resources4' . DIRECTORY_SEPARATOR . 'SauxeIcons' . DIRECTORY_SEPARATOR . 'gris' . DIRECTORY_SEPARATOR . 'productos.png',
                    'leaf' => false,
                    'version' => $value->getVersion());
            }
        } else {
            $nombre = $this->_request->getPost('text');
            foreach ($bundles as $key => $value) {
                if ($value->getName() == $nombre) {
                    foreach ($value->getListaService() as $service) {

                        $data[] = array('text' => $service->getId(), 'expandable' => false,
                            'icon' => DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'ExtJS' . DIRECTORY_SEPARATOR . 'temas' . DIRECTORY_SEPARATOR . 'resources4' . DIRECTORY_SEPARATOR . 'SauxeIcons' . DIRECTORY_SEPARATOR . 'gris' . DIRECTORY_SEPARATOR . 'servicios.png',
                            'leaf' => true, 'interface' => $service->getInterface());
                    }
                }
            }
        }
        $result = array('success' => true, 'data' => $data);

        echo json_encode($result);
        return;
    }

    function insertarDependenciaAction() {
        $direc = $this->_request->getPost('direccion');
        $nombre = $this->_request->getPost('nombre');
        $nombredep = $this->_request->getPost('nombredep');

        $opcional = $this->_request->getPost('opcional');
        $interfaz = $this->_request->getPost('interfacedep');
        $use = $this->_request->getPost('use');
        $direccion = $direc . DIRECTORY_SEPARATOR . $nombre . '.scl';


        $xml = $this->leer_xml($direccion);
        $aut = new SimpleXMLElement($xml->asXml());
        
        
        $a=$aut->children();
        $encontrado=false;
                foreach ($a as $key => $value) {
                          if((String)$key=="dependencies"){
                             $encontrado=true;
                          }
                          
                }
               
               
                if($encontrado){
                    if ($xml != -1) {
            $encontrad = false;
            foreach ($xml->dependencies->dependency as $nodo) {

                if ((string) $nodo['id'] == $nombredep) {

                    $encontrad = true;
                }
            }
            

            if ($encontrad == false) {
                
                $dependencies = $aut->dependencies->addChild('dependency');
                $dependencies->addAttribute('id', $nombredep);
                $dependencies->addAttribute('interface', $interfaz);
                $dependencies->addAttribute('use', $use);
                $dependencies->addAttribute('optional', $opcional);
                $aut->saveXML($direccion);

                $clasedep = $this->crearClaseDependencia($direc, $interfaz, $nombre);

                if ($clasedep == true) {
                    
                    $this->manager->searchBundles();
                  
                    $this->manager->solveBundles();
                     
                    echo"{'codMsg':1,'mensaje': 'Se ha insertado la dependencia correctamente.'}";
                    return;
                } else {
                    echo"{'codMsg':3,'mensaje': 'Ya existe una interfaz para esta dependencia.'}";
                    return;
                }
            } else {
                echo"{'codMsg':3,'mensaje': 'Ya existe la dependencia.'}";
                return;
            }
        } else {
            echo"{'codMsg':3,'mensaje': 'No se pudo insertar la dependencia.'}";
            return;
        }
                }else{
                    
                    if ($xml != -1) {          
                    

                $dependencies=$aut->addChild('dependencies');
                $dependencies = $aut->dependencies->addChild('dependency');
                $dependencies->addAttribute('id', $nombredep);
                $dependencies->addAttribute('interface', $interfaz);
                $dependencies->addAttribute('use', $use);
                $dependencies->addAttribute('optional', $opcional);
                $aut->saveXML($direccion);
                  
                $clasedep = $this->crearClaseDependencia($direc, $interfaz, $nombre);
               
                if ($clasedep == true) {
                    $this->manager->searchBundles();
                    $this->manager->solveBundles();

                    echo"{'codMsg':1,'mensaje': 'Se ha insertado la dependencia correctamente.'}";
                    return;
                } else {
                    echo"{'codMsg':3,'mensaje': 'Ya existe una interfaz para esta dependencia.'}";
                    return;
                }
           
        } else {
            echo"{'codMsg':3,'mensaje': 'No se pudo insertar la dependencia.'}";
            return;
        }
                }
        
    }

    function modificarDependenciaAction() {
        $dir = $this->_request->getPost('direccion');
        $nombre = $this->_request->getPost('nombre');
        $nombredep = $this->_request->getPost('nombredep');
        $nomold = $this->_request->getPost('nomold');

        $opcional = $this->_request->getPost('opcional');
        $interfaz = $this->_request->getPost('interfacedep');
        $use = $this->_request->getPost('use');
        $direccion = $dir . DIRECTORY_SEPARATOR . $nombre . '.scl';
       
        
        $xml = simplexml_load_file($direccion);
        $aut = new SimpleXMLElement($xml->asXml());
        $dependencies =  (array)$aut->dependencies;
        
        if (count($dependencies) != 0) {
            if(count($dependencies['dependency'])==0){
                foreach ($dependencies as $key => $value){
                  if ((string) $value['id'] == $nomold) {
                    $Intdel=(string)$value['interface'];
                     
                    (string) $value['id'] = $nombredep;
                    (string) $value['interface'] = $interfaz;
                     $pos = strpos($dir, 'apps');
                    $direlim = substr($dir, $pos + 4);
                    
                $direlim = $direlim . DIRECTORY_SEPARATOR . $Intdel;
                
                $direlim = $_SERVER['DOCUMENT_ROOT'] . $direlim;
               
                $direlim = str_replace("web", "apps", $direlim);
                  
                 unlink($direlim);
                    
                    $this->crearClaseDependencia($dir, $interfaz, $nombre);
                   
                    (string) $value['use'] = $use;
                    (string) $value['optional'] = $opcional;
                     
                    $aut->saveXML($direccion);
                    $this->manager->searchBundles();
                    
                    $this->manager->solveBundles();
                  
                    echo"{'codMsg':1,'mensaje': 'Se ha modificado la dependencia correctamente.'}";
                    return;
                    
                }  
                } 
            }else{
                if(count($dependencies['dependency'])>=2){
                    foreach ($dependencies['dependency'] as $key => $value){
                  if ((string) $value['id'] == $nomold) {
                    $Intdel=(string)$value['interface'];
                     
                    (string) $value['id'] = $nombredep;
                    (string) $value['interface'] = $interfaz;
                     $pos = strpos($dir, 'apps');
                    $direlim = substr($dir, $pos + 4);
                    
                $direlim = $direlim . DIRECTORY_SEPARATOR . $Intdel;
                
                $direlim = $_SERVER['DOCUMENT_ROOT'] . $direlim;
               
                $direlim = str_replace("web", "apps", $direlim);
                  
                 unlink($direlim);
                    $this->crearClaseDependencia($dir, $interfaz, $nombre);
                    (string) $value['use'] = $use;
                    (string) $value['optional'] = $opcional;
                    $aut->saveXML($direccion);
                    $this->manager->searchBundles();
                    $this->manager->solveBundles();

                    echo"{'codMsg':1,'mensaje': 'Se ha modificado la dependencia correctamente.'}";
                    return;
                } 
               } 
                }
                
            }
             
           
        }else{
           echo"{'codMsg':3,'mensaje': 'No se ha  podido modificar la dependencia.'}";
        return; 
        }




        
    }

    function eliminarDependenciaAction() {
        $ids = $this->_request->getPost('ids');
        $ids = explode(",", $ids);
        $direc = $this->_request->getPost('direccion');
        $nombre = $this->_request->getPost('nombre');
        $dir = $direc . DIRECTORY_SEPARATOR . $nombre . ".scl";
        $pos = strpos($dir, 'apps');


        $dir = substr($dir, $pos + 4);

        $direccion = $_SERVER['DOCUMENT_ROOT'] . $dir;
        $direccion = str_replace("web", "apps", $direccion);

        $xml = $this->leer_xml($direccion);
        $dir = Array();
        for ($index = 0; $index < count($ids); $index++) {
            foreach ($xml->dependencies->dependency as $nodo) {

                if ((string) $nodo['id'] == $ids[$index]) {

                    $dir[$index] = (string) $nodo['interface'];

                    $dom = dom_import_simplexml($nodo);
                    $dom->parentNode->removeChild($dom);
                    $guardar = 1;
                }
            }
        }

        if ($guardar == 1) {
            $xml->asXML($direccion);


            for ($index1 = 0; $index1 < count($ids); $index1++) {
                $pos = strpos($direc, 'apps');
                $direlim = substr($direc, $pos + 4);
                $direlim = $direlim . DIRECTORY_SEPARATOR . $dir[$index1];
                $direlim = $_SERVER['DOCUMENT_ROOT'] . $direlim;
                $direlim = str_replace("web", "apps", $direlim);
                unlink($direlim);
            }

            $this->manager->searchBundles();
            $this->manager->solveBundles();

            echo"{'codMsg':1,'mensaje': 'Se ha(n) eliminado la(s) dependencia(s) correctamente.'}";
            return;
        } else {
            echo"{'codMsg':3,'mensaje': 'No se ha podido eliminar la(s) dependencia(s).'}";
            return;
        }
    }

    function crearClaseDependencia($direccion, $interfaz, $nombre) {
        $dir = $direccion;

        
        $nomb = ucfirst($nombre);
        
        $arr = explode(DIRECTORY_SEPARATOR . $nomb, $interfaz);
        $serv = explode(".", lcfirst($arr[1]));
        
        $bundles = $this->manager->getBundlesList();
        foreach ($bundles as $bundle) {
            $servicios = $bundle->getListaService();
            if (count($servicios) != 0) {
                foreach ($servicios as $service) {

                    if ($service->getId() == $serv[0]) {
                        $dircop = $bundle->getPath() . DIRECTORY_SEPARATOR . $service->getInterface();
                    }
                }
            }
        }
        $pos = strpos($dir, 'apps');
        $dir = substr($dir, $pos + 4);
        $dir = $_SERVER['DOCUMENT_ROOT'] . $dir;
        $dir = str_replace("web", "apps", $dir);


        if (!is_dir($dir . DIRECTORY_SEPARATOR . "services")) {

            mkdir($dir . DIRECTORY_SEPARATOR . "services");
            chmod($dir . DIRECTORY_SEPARATOR . "services", 0777);

            if (!file_exists($dir . DIRECTORY_SEPARATOR . $interfaz)) {

                $file = fopen($dir . DIRECTORY_SEPARATOR . $interfaz, 'X+');

                $direcc = $_SERVER['DOCUMENT_ROOT'] . $dircop;
                $direcc = str_replace("web", "apps", $direcc);              
                $c=basename($direcc);
                $a=  explode('.', $c);
                $NombreDell=$a[0];
                $Nclase=ucfirst($serv[0]);
                $NombreInterfaz=$nomb.$Nclase;
               
                $contenido = file_get_contents($direcc);
                
               $nuevocontenido=str_replace($NombreDell, $NombreInterfaz, $contenido);
                

                file_put_contents($dir . DIRECTORY_SEPARATOR . $interfaz, $nuevocontenido);
                fclose($file);
                chmod($dir . DIRECTORY_SEPARATOR . $interfaz, 0777);

                return true;
            } else {

                return false;
            }
        } else {
            if (!file_exists($dir . DIRECTORY_SEPARATOR . $interfaz)) {

                $file = fopen($dir . DIRECTORY_SEPARATOR . $interfaz, 'X+');

                $direcc = $_SERVER['DOCUMENT_ROOT'] . $dircop;
                $direcc = str_replace("web", "apps", $direcc);              
                $c=basename($direcc);
                $a=  explode('.', $c);
                $NombreDell=$a[0];
                $Nclase=ucfirst($serv[0]);
                $NombreInterfaz=$nomb.$Nclase;
               
                $contenido = file_get_contents($direcc);
                
               $nuevocontenido=str_replace($NombreDell, $NombreInterfaz, $contenido);
                

                file_put_contents($dir . DIRECTORY_SEPARATOR . $interfaz, $nuevocontenido);
                

                fclose($file);
                chmod($dir . DIRECTORY_SEPARATOR . $interfaz, 0777);

                return true;
            } else {

                return false;
            }
        }
    }

    /////////////////Eventos Gen///////////////////////////|
    function cargarEventosGenAction() {

        $nombre = $this->_request->getPost('nombre');
        $bundles = $this->manager->getBundlesList();

        $data = array();
        foreach ($bundles as $key => $bundle) {

            if ($bundle->getName() == $nombre) {
                $sources = $bundle->getListaSourceEvent();
                if (count($sources) != 0) {
                    
                    foreach ((array)$sources as $sourceEvent) {
                        
                        $pos = strpos((string)$sourceEvent->getClass(), 'apps' . DIRECTORY_SEPARATOR . $nombre);
                        $var = 6 + strlen($nombre);
                        $class = substr((string)$sourceEvent->getClass(), $pos + $var);
                        
                        if ($class == "false") {
                            $data[] = array('id' => $sourceEvent->getId(), 'class' => "falta");
                        } else {
                            $data[] = array('id' => $sourceEvent->getId(), 'class' => $class);
                        }
                    }
                }
            }
        }


        $result = array('success' => true, 'data' => $data);

        echo json_encode($result);
        return;
    }

    function cargarAllCompServAction() {
        $bundles = $this->manager->getBundlesList();
        $data = array();
        if ($this->_request->getPost('node') == 'root') {
            foreach ($bundles as $key => $value) {

                $data[] = array('text' => $value->getName(),
                    'expandable' => false,
                    'icon' => '/images/icons/componente.png',
                    'leaf' => true,
                    'version' => $value->getVersion());
            }
        }
        $result = array('success' => true, 'data' => $data);

        echo json_encode($result);
        return;
    }

    function insertarEventGAction() {
        $nombreventg = $this->_request->getPost('nombreventg');
        $clase = $this->_request->getPost('nombreclase');

        $direccion = $this->_request->getPost('direccion');
        $nombre = $this->_request->getPost('nombre');
        $direccion = $direccion . DIRECTORY_SEPARATOR . $nombre . '.scl';
        $xml = simplexml_load_file($direccion);
        $aut = new SimpleXMLElement($xml->asXml());
            $a=$aut->children();
                foreach ($a as $key => $value) {
                          if((String)$key=="sources"){
                             $encontrado=true;
                          }
                }
                
                if($encontrado){
                    $eventos = (array) $aut->sources;
        foreach ($eventos as $key => $value) {

            if ((string) $value['id'] == $nombreventg) {
                echo"{'codMsg':3,'mensaje': 'Ya existe este evento.'}";
                return;
            }
        }

        
        
        $sources = $aut->sources->addChild('source');
        $sources->addAttribute('id', $nombreventg);
        if ($clase != false) {
            $sources->addAttribute('class', $clase);
        } else {
            $sources->addAttribute('class', false);
        }

        $aut->saveXML($direccion);
        $this->manager->searchBundles();
        $this->manager->solveBundles();
        echo"{'codMsg':1,'mensaje': 'Se ha insertado el evento correctamente.'}";
        return;
                }else{
            
        $sources= $aut->addChild('sources');
        $sources = $aut->sources->addChild('source');
        $sources->addAttribute('id', $nombreventg);
        if ($clase != false) {
            $sources->addAttribute('class', $clase);
        } else {
            $sources->addAttribute('class', false);
        }

        $aut->saveXML($direccion);
        $this->manager->searchBundles();
        $this->manager->solveBundles();
        echo"{'codMsg':1,'mensaje': 'Se ha insertado el evento correctamente.'}";
        return;
                }
        
    }

    function modificarEventGAction() {
        $direccion = $this->_request->getPost('direccion');
        $nombre = $this->_request->getPost('nombre');
        $nombreve = $this->_request->getPost('nombreventg');
        $nomold = $this->_request->getPost('nomold');
        $clase = $this->_request->getPost('nombreclase');
        $class = $this->_request->getPost('clase');
        $direccion = $direccion . DIRECTORY_SEPARATOR . $nombre . '.scl';

        $xml = simplexml_load_file($direccion);

        $aut = new SimpleXMLElement($xml->asXml());
        $sources = (array) $aut->sources;

        if (count($sources) != 0) {
if(count($sources['sources'])==0){
    foreach ($sources as $key => $value){
        if ((string) $value['id'] == $nomold) {
            $Intdel=(string)$value['class'];
                    $value['id'] = $nombreve;
                    if ($class != "on") {
                        $value['class'] = false;
                    } else {
                        $value['class'] = $clase;
                        $pos = strpos($dir, 'apps');
                    $direlim = substr($dir, $pos + 4);
                    
                $direlim = $direlim . DIRECTORY_SEPARATOR . $Intdel;
                
                $direlim = $_SERVER['DOCUMENT_ROOT'] . $direlim;
               
                $direlim = str_replace("web", "apps", $direlim);
                  
                 unlink($direlim);
                        
                    }


                    $aut->saveXML($direccion);
                    $this->manager->searchBundles();
                    $this->manager->solveBundles();

                    echo"{'codMsg':1,'mensaje': 'Se ha modificado el evento correctamente.'}";
                    return;
                } 
    }
}else{
    if(count($sources['sources'])>=2){
        foreach ($sources['source'] as $key => $value){
            if ((string) $value['id'] == $nomold) {
                    $value['id'] = $nombreve;
                    if ($class != "on") {
                        $value['class'] = false;
                    } else {
                        $value['class'] = $clase;
                    }


                    $aut->saveXML($direccion);
                    $this->manager->searchBundles();
                    $this->manager->solveBundles();

                    echo"{'codMsg':1,'mensaje': 'Se ha modificado el evento correctamente.'}";
                    return;
                } 
        }
    }
}

        }

        echo"{'codMsg':3,'mensaje': 'No se ha  podido modificar el evento.'}";
        return;
    }

    function eliminarEventGenAction() {
        $ids = $this->_request->getPost('ids');
        $ids = explode(",", $ids);
        $direccion = $this->_request->getPost('direccion');
        $nombre = $this->_request->getPost('nombre');
        $dir = $direccion . DIRECTORY_SEPARATOR . $nombre . ".scl";
        $pos = strpos($dir, 'apps');
        $dir = substr($dir, $pos + 4);
        $direccion = $_SERVER['DOCUMENT_ROOT'] . $dir;
        $direccion = str_replace("web", "apps", $direccion);
        $xml = $this->leer_xml($direccion);

        $sources = (array) $aut->sources;
        for ($index = 0; $index < count($ids); $index++) {
            foreach ($xml->sources->source as $nodo) {

                if ((string) $nodo['id'] == $ids[$index]) {

                    $dom = dom_import_simplexml($nodo);
                    $dom->parentNode->removeChild($dom);
                    $guardar = 1;
                }
            }
        }
        if ($guardar) {
            $xml->asXML($direccion);
            $this->manager->searchBundles();
            $this->manager->solveBundles();

            echo"{'codMsg':1,'mensaje': 'Se ha(n) eliminado el(los) evento(s) correctamente.'}";
            return;
        } else {
            echo"{'codMsg':3,'mensaje': 'No se ha podido eliminar el(los) evento(s).'}";
            return;
        }
    }

    ////////////////Eventos Obs////////////////////////////|
    function cargarEventosObsAction() {
        $nombre = $this->_request->getPost('nombre');
        $bundles = $this->manager->getBundlesList();

        $data = array();
        foreach ($bundles as $key => $bundle) {

            if ($bundle->getName() == $nombre) {
                $observers = $bundle->getListaObserver();
                if (count($observers) != 0) {
                    foreach ($observers as $Observer) {

                        $data[] = array('source' => $Observer->getSource(), 'impl' => $Observer->getImpls()
                        );
                    }
                }
            }
        }


        $result = array('success' => true, 'data' => $data);

        echo json_encode($result);
        return;
    }

    function cargarAllEventAction() {
        $bundles = $this->manager->getBundlesList();
        $data = array();
        if ($this->_request->getPost('node') == 'root') {
            foreach ($bundles as $key => $value) {

                $data[] = array('text' => $value->getName(),
                    'expandable' => true,
                    'icon' => DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'ExtJS' . DIRECTORY_SEPARATOR . 'temas' . DIRECTORY_SEPARATOR . 'resources4' . DIRECTORY_SEPARATOR . 'SauxeIcons' . DIRECTORY_SEPARATOR . 'gris' . DIRECTORY_SEPARATOR . 'productos.png',
                    'leaf' => false
                );
            }
        } else {
            $nombre = $this->_request->getPost('text');
            foreach ($bundles as $key => $value) {
                if ($value->getName() == $nombre) {
                    $sources = $value->getListaSourceEvent();
                    if (count($sources) != 0) {
                        foreach ($sources as $source) {
                            $pos = strpos($source->getClass(), 'apps' . DIRECTORY_SEPARATOR . $nombre);
                            $var = 9 + strlen($nombre);
                            $class = substr($source->getClass(), $pos + $var);
                            if ($class == "se") {
                                $data[] = array('text' => $source->getId(), 'expandable' => false, 'leaf' => true,
                                    'icon' => DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'ExtJS' . DIRECTORY_SEPARATOR . 'temas' . DIRECTORY_SEPARATOR . 'resources4' . DIRECTORY_SEPARATOR . 'SauxeIcons' . DIRECTORY_SEPARATOR . 'gris' . DIRECTORY_SEPARATOR . 'entidad.png'
                                );
                            } else {
                                $data[] = array('text' => $source->getId(), 'expandable' => false, 'leaf' => true,
                                    'icon' => DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'ExtJS' . DIRECTORY_SEPARATOR . 'temas' . DIRECTORY_SEPARATOR . 'resources4' . DIRECTORY_SEPARATOR . 'SauxeIcons' . DIRECTORY_SEPARATOR . 'gris' . DIRECTORY_SEPARATOR . 'entidad.png'
                                );
                            }
                        }
                    }
                }
            }
        }
        $result = array('success' => true, 'data' => $data);

        echo json_encode($result);
        return;
    }

    function insertarEventObsAction() {
        $nombrevento = $this->_request->getPost('nombrevento');
        $impl = $this->_request->getPost('impl');
        $dir = $this->_request->getPost('direccion');
        $nombre = $this->_request->getPost('nombre');
        $direccion = $dir . DIRECTORY_SEPARATOR . $nombre . '.scl';
        $xml = simplexml_load_file($direccion);
        $aut = new SimpleXMLElement($xml->asXml());
        $a=$aut->children();
                foreach ($a as $key => $value) {
                          if((String)$key=="observers"){
                             $encontrado=true;
                          }
                }
                
            if($encontrado){
                $observadores = (array) $aut->observers;
        foreach ($observadores as $key => $value) {

            if ((string) $value['id'] == $nombrevento) {
                echo"{'codMsg':3,'mensaje': 'Ya existe este observador.'}";
                return;
            }
        }

        
        $observers = $aut->observers->addChild('observer');
        $observers->addAttribute('source', $nombrevento);
        if ($impl != "false") {
            $observers->addAttribute('impl', $impl);
            $clase = $this->crearClaseObserver($dir, $impl, $nombre . $nombrevento);
            if ($clase == true) {
                
            } else {
                
            }
        } else {
            $observers->addAttribute('impl', false);
        }

        $aut->saveXML($direccion);
        $this->manager->searchBundles();
        $this->manager->solveBundles();

        echo"{'codMsg':1,'mensaje': 'Se ha insertado el observador correctamente.'}";
        return;
            }else{
               

        $observers = $aut->addChild('observers');
        $observers = $aut->observers->addChild('observer');
        $observers->addAttribute('source', $nombrevento);
        if ($impl != "false") {
            $observers->addAttribute('impl', $impl);
            $clase = $this->crearClaseObserver($dir, $impl, $nombre . $nombrevento);
            if ($clase == true) {
                
            } else {
                
            }
        } else {
            $observers->addAttribute('impl', false);
        }

        $aut->saveXML($direccion);
        $this->manager->searchBundles();
        $this->manager->solveBundles();

        echo"{'codMsg':1,'mensaje': 'Se ha insertado el observador correctamente.'}";
        return;
            }

        
    }

    function modificarEventObsAction() {
        $direccion = $this->_request->getPost('direccion');
        $nombre = $this->_request->getPost('nombre');
        $nombreve = $this->_request->getPost('nombrevento');
        $nomold = $this->_request->getPost('nomold');
        $impl = $this->_request->getPost('impl');
        $direccion = $direccion . DIRECTORY_SEPARATOR . $nombre . '.scl';

        $xml = simplexml_load_file($direccion);
        $aut = new SimpleXMLElement($xml->asXml());
        $observers =(array)$aut->observers;
        
       if(count($observers['observer'])==0){
         foreach ($observers as $key => $value) {
                
            if ((string) $value['source'] == $nomold) {
                $value['source'] = $nombreve;
                if ((string)$impl == "false") {
                    $value['impl'] = "false";
                } else {
                    $value['impl'] = $impl;
                }


                $aut->saveXML($direccion);
                $this->manager->searchBundles();
                $this->manager->solveBundles();

                echo"{'codMsg':1,'mensaje': 'Se ha modificado el observador correctamente.'}";
                return;
            }
        }  
       }else{
           if(count($observers['observer'])>=2){
             foreach ($observers['observer'] as $key => $value) {
                
            if ((string) $value['source'] == $nomold) {
                $value['source'] = $nombreve;
                if ((string)$impl == "false") {
                    $value['impl'] = "false";
                } else {
                    $value['impl'] = $impl;
                }


                $aut->saveXML($direccion);
                $this->manager->searchBundles();
                $this->manager->solveBundles();

                echo"{'codMsg':1,'mensaje': 'Se ha modificado el observador correctamente.'}";
                return;
            } 
       }  
           }
          
        
        }
        echo"{'codMsg':3,'mensaje': 'No se ha  podido modificar el observador.'}";
        return;
    }

    function eliminarEventObsAction() {
        $sources = $this->_request->getPost('sources');
        $sources = explode(",", $sources);
        $direc = $this->_request->getPost('direccion');
        $nombre = $this->_request->getPost('nombre');
        $direccion = $direc . DIRECTORY_SEPARATOR . $nombre . ".scl";
        $pos = strpos($direccion, 'apps');
        $direccion = substr($direccion, $pos + 4);
        $direccion = $_SERVER['DOCUMENT_ROOT'] . $direccion;
        $direccion = str_replace("web", "apps", $direccion);
        $xml = $this->leer_xml($direccion);
        $dir = array();
        for ($index = 0; $index < count($sources); $index++) {
            foreach ($xml->observers->observer as $nodo) {

                if ((string) $nodo['source'] == $sources[$index]) {
                    $dir[] = (string) $nodo['impl'];
                    $dom = dom_import_simplexml($nodo);
                    $dom->parentNode->removeChild($dom);
                    $guardar = 1;
                }
            }
        }
        if ($guardar) {
            $xml->asXML($direccion);
            for ($index1 = 0; $index1 < count($sources); $index1++) {
                $direlim = substr($direc, $pos + 4);
                $direlim = $direlim . DIRECTORY_SEPARATOR . "services" . DIRECTORY_SEPARATOR . $dir[$index1];
                $direlim = $_SERVER['DOCUMENT_ROOT'] . $direlim;
                $direlim = str_replace("web", "apps", $direlim);
                unlink($direlim);
            }

            $this->manager->searchBundles();
            $this->manager->solveBundles();

            echo"{'codMsg':1,'mensaje': 'Se ha(n) eliminado el(los) observador(es) correctamente.'}";
            return;
        } else {
            echo"{'codMsg':3,'mensaje': 'No se ha podido eliminar el(los) observador(es).'}";
            return;
        }
    }

    function crearClaseObserver($direccion, $impl, $nombre) {
        $dir = $direccion;

        $pos = strpos($dir, 'apps');
        $dir = substr($dir, $pos + 4);
        $dir = $_SERVER['DOCUMENT_ROOT'] . $dir;
        $dir = str_replace("web", "apps", $dir);

        if (!is_dir($dir . DIRECTORY_SEPARATOR . "services")) {

            mkdir($dir . DIRECTORY_SEPARATOR . "services");
            chmod($dir . DIRECTORY_SEPARATOR . "services", 0777);

            if (!file_exists($dir . DIRECTORY_SEPARATOR . "services" . DIRECTORY_SEPARATOR . $impl)) {

                $file = fopen($dir . DIRECTORY_SEPARATOR . "services" . DIRECTORY_SEPARATOR . $impl, 'X+');

                $contenido = "<?php
/**
 *
 *
 */
class " . $nombre . " extends ZendExt_Component_Observer{
 
   public function update(){
      //instrucciones
  }
}

?>";

                file_put_contents($dir . DIRECTORY_SEPARATOR . "services" . DIRECTORY_SEPARATOR . $impl, $contenido);

                fclose($file);
                chmod($dir . DIRECTORY_SEPARATOR . "services" . DIRECTORY_SEPARATOR . $impl, 0777);

                return true;
            } else {

                return false;
            }
        } else {
            if (!file_exists($dir . DIRECTORY_SEPARATOR . "services" . DIRECTORY_SEPARATOR . $impl)) {

                $file = fopen($dir . DIRECTORY_SEPARATOR . "services" . DIRECTORY_SEPARATOR . $impl, 'X+');

                $contenido = "<?php
/**
 *
 * 
 */
class " . $nombre . " extends ZendExt_Component_Observer{
 
   public function update(){
      //instrucciones
  }
}

?>";

                file_put_contents($dir . DIRECTORY_SEPARATOR . "services" . DIRECTORY_SEPARATOR . $impl, $contenido);

                fclose($file);
                chmod($dir . DIRECTORY_SEPARATOR . "services" . DIRECTORY_SEPARATOR . $impl, 0777);

                return true;
            } else {

                return false;
            }
        }
    }

    //////////////////Generales////////////////////////////|
    /* leer_xml:le pasas la dirección de un fichero que contiene la estructura xml. */
    function leer_xml($fichero) {
//Comprobamos que existe el fichero
        if (file_exists($fichero)) {
//Lo cargamos como objeto con simplexml
            $dom = simplexml_load_file($fichero);
        } else {
            echo 'Fallo al abrir ' . $fichero . '<br/>';
            $dom = -1;
        }

        if (!$dom)
            return -1;
        return $dom;
    }

    /* insertar_nodo:le pasas la etiqueta padre del nodo, una estructura con datos, el ficheor a leer, si puede haber nodos duplicados y un vector con la información de las etiquetas a mirar para ver si es duplicado.
      La estructura de datos será:
      $datos[0]['tag'] = 'etiqueta1';
      $datos[0]['dato'] = $valor;
      $datos[1]['tag'] = 'etiqueta2';
      $datos[1]['dato'] = $valor2;
      La estructura de valores duplicados es igual, es decir, para cada etiqueta a mirar creas $duplicados ['tag'] = 'etiqueta a mirar a la hora de ver si es duplicado', $duplicados ['dato'] = 'valor a comparar'; */

    function insertar_nodo($nodo, $datos, $fichero, $duplicar, $duplicados) {
//Leemos fichero      
        if (( $xml = leer_xml($fichero)) == - 1)
            return - 1;

//Comprobamos si puede haber       nodos duplicados o no y si los hay      
        if ($duplicar == 0)
            $stop = $this->buscar_nodo($nodo, $duplicados, $fichero);
        else
            $stop = 0;

//Si no hay nodos duplicados o       si puede haberlos, seguimos      
        if ($stop == 0) {
//Guardamos todos los datos en       un nuevo nodo      
            $tag = $xml->addChild($nodo);
            foreach ($datos as $valor)
                $tag->addChild($valor ['tag'], $valor ['dato']);
//Guardamos el fichero      
            $xml->asXML($fichero);
        }

        return 0;
    }

    /* buscar_nodo:le pasas la etiqueta padre, la estructura de datos a buscar 
     * (idéntica a las estructuras de insertar_nodo), el fichero donde está el xml y si es case sensitive,
     *  es decir, si debe tener en cuenta o no las mayúsculas). 
     * Este último parámetro ha de ser 0 para que no tenga en cuenta si son mayúsculas o minúsculas y debe ser 1 
     * si se quiere que diferencie mayúsculas de minúsculas. 
     * El valor es opcional. Si no se le pasa vale 0. */

    function buscar_nodo($nodo, $datos, $fichero, $case_sensitive) {

        $encontrado = 0;
//Leemos el fichero      
        if (( $xml = $this->leer_xml($fichero)) == - 1)
            return - 1;

//Comprobamos si es case       sensitive      
        if (!$case_sensitive)
            $case_sensitive = 0;
        switch ($case_sensitive) {
            case 0 :
//Recorremos los nodos      
                foreach ($xml->$nodo as $tag)
                    foreach ($datos as $valor)
//Comparamos valor del nodo con       el nuestro      
                        if (strtolower($tag->$valor ['tag']) == strtolower($valor ['dato']))
                            $encontrado = 1;
                break;
            case 1 :
                foreach ($xml->$nodo as $tag)
                    foreach ($datos as $valor)
                        if ($tag->$valor ['tag'] == $valor ['dato'])
                            $encontrado = 1;
                break;
        }

        return $encontrado;
    }

    /* modificar_nodo:le pasas la etiqueta padre, la sub_etiqueta a cambiar(igual que en funciones anteriores), 
     * el valor viejo que debe buscar para cambiarlo, el valor nuevo que debe poner y el fichero a leer. */

    function modificar_nodo($tag, $dato, $viejo, $nuevo, $fichero) {
//Leemos el fichero      
        if (( $xml = leer_xml($fichero)) == - 1)
            return - 1;
//Recorremos los nodos       
        foreach ($xml->$tag as $nodo) {
//Si encontramos nuestro nodo      
            if ($nodo->$dato == $viejo) {
//Lo modificamos      
                $nodo->$dato = $nuevo;
                $guardar = 1;
            }
        }
//Si hay cambios guardamos el       fichero       
        if ($guardar)
            $xml->asXML($fichero);

        return 0;
    }

    /* borrar_nodo:le pasas la etiqueta padre, la sub_etiqueta a mirar para ver si es el nodo a borrar, 
     * el dato que debe contener esta etiqueta,
     *  el fichero y por último el número máximo de nodos a borrar en caso de que exista más de uno que cumpla el criterio. */

    function borrar_nodo($tag, $dato, $valor, $fichero, $num_nodos) {
        if (!$num_nodos)
            $num_nodos = 1;

//Creamos un bucle con una       iteración para cada nodo a borrar      
        for ($i = 0; $i < $num_nodos; $i ++) {
//Leemos el fichero      
            if (( $xml = $this->leer_xml($fichero)) == - 1)
                return - 1;
//Recorreoms los nodos      
            foreach ($xml->$tag as $nodo) {
//Si el valor del       nodo->etiqueta = valor pasado      
                if ($nodo->$dato == $valor) {
//Borramos el nodo      
                    $dom = dom_import_simplexml($nodo);
                    $dom->parentNode->removeChild($dom);
                    $guardar = 1;
                }
            }
//Si hay cambios guardamos el       fichero      
            if ($guardar)
                $xml->asXML($fichero);
            return 1;
        }
    }

    /* Se nota que están recien sacadas del horno ya que las primeras reciben el primer parámetro como $nodo y las últimas como $tag... 
     * funcionar funcionan bien, lo que puede que les falte son nombres más coherentes a las variables. 
     * Lo dicho, se aceptan correcciones. */

    function borrar_nodo1($tag, $dato, $valor, $fichero, $num_nodos, $case_sensitive) {
        if (!$num_nodos)
            $num_nodos = 1;
        if (!$case_sensitive)
            $case_sensitive = 0;

        switch ($case_sensitive) {
            case 0 :
//Creamos un bucle con una       iteración para cada nodo a borrar      
                for ($i = 0; $i < $num_nodos; $i ++) {
//Leemos el fichero      
                    if (( $xml = leer_xml($fichero)) == - 1)
                        return - 1;
//Recorreoms los nodos      
                    foreach ($xml->$tag as $nodo) {
//Si el valor del       nodo->etiqueta = valor pasado      
                        if (strtolower($nodo->$dato) == strtolower($valor)) {
//Borramos el nodo      
                            $dom = dom_import_simplexml($nodo);
                            $dom->parentNode->removeChild($dom);
                            $guardar = 1;
                        }
                    }
//Si hay cambios guardamos el       fichero      
                    if ($guardar)
                        $xml->asXML($fichero);
                }
                break;
            case 1:
//Creamos un bucle con una       iteración para cada nodo a borrar      
                for ($i = 0; $i < $num_nodos; $i ++) {
//Leemos el fichero      
                    if (( $xml = leer_xml($fichero)) == - 1)
                        return - 1;
//Recorreoms los nodos      
                    foreach ($xml->$tag as $nodo) {
//Si el valor del       nodo->etiqueta = valor pasado      
                        if ($nodo->$dato == $valor) {
//Borramos el nodo      
                            $dom = dom_import_simplexml($nodo);
                            $dom->parentNode->removeChild($dom);
                            $guardar = 1;
                        }
                    }
//Si hay cambios guardamos el       fichero      
                    if ($guardar)
                        $xml->asXML($fichero);
                }
                break;
        }
    }

    function leer_xml_var($xml) {
//Comprobamos que existe la       variable      
        if ($xml) {
//Lo cargamos como objeto con       simplexml      
            $dom = simplexml_load_string($xml);
        } else {
            echo 'Fallo al leer el       código<br/>';
            $dom = - 1;
        }

        if (!$dom)
            return - 1;
        return $dom;
    }

}

?>
