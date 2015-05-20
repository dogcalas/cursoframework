<?php

class ZendExt_Component_BundleSeeker {

    private $bundlesList;
    private $includePath;
    private $idSequecence;

    function __construct() {
        $this->idSequecence = 0;
    }

    function seek($dir) {
        if ($opendir = opendir($dir)) { //Abro directorio
            while (($file = readdir($opendir)) !== false) {

                $path = $dir . DIRECTORY_SEPARATOR . $file;
                if (is_file($path) && substr($file, -4) == ".scl") {
                    $this->load_Bundle_XML($path); //el path va hasta el fichero .scl
                }
                if (is_dir($path) && $file != "." && $file != "..") {
                    $this->seek($path);
                }
            }
        } else {
            echo "<hr>Error: $dir<br>";
        }
    }

    function searchDir($dir) {

        if ($gd = opendir($dir)) { //Abro directorio
            while (($ar = readdir($gd)) !== false) {

                $path = $dir . DIRECTORY_SEPARATOR . $ar;
                if (is_dir($path) && $ar != "." && $ar != ".." && $ar != '.svn') {
                    $this->IncludePath($path);
                    $this->searchDir($path);
                }
            }
        } else {
            echo "<hr>Error: $dir<br>";
        }
    }

    function IncludePath($path) {
        $path = str_replace("/web/../", "/", $path);
        $path = str_replace("//", "/", $path);
        $this->includePath.=PATH_SEPARATOR . $path;
    }

    function getIncludePath() {
        return $this->includePath;
    }

    public function setBundlesList() {
        $this->bundlesList = array();
    }

    function getFileName($path) {
        $parts = explode(DIRECTORY_SEPARATOR, $path);
        $fullname = explode(".", $parts[count($parts) - 1]);
        return $fullname[0];
    }

    function getDir($path) {
        $length = strrpos($path, DIRECTORY_SEPARATOR);
        $dir = substr($path, 0, $length);
        $dir = str_replace(str_replace("web", "apps", $_SERVER['DOCUMENT_ROOT']), "", $dir);
        $dir = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $dir);
        return $dir;
    }

    function load_Bundle_XML($dir) {
        $cargar_bundle_xml = simplexml_load_file($dir);
        if (file_exists($dir))
            if ($cargar_bundle_xml->data["state"] != "disable") {
                $services = $cargar_bundle_xml->services;
                $dependencies = $cargar_bundle_xml->dependencies;
                $eventSources = $cargar_bundle_xml->sources;
                $observers = $cargar_bundle_xml->observers;
                $name = $this->getFileName($dir);
                $path = $this->getDir($dir);
                $Bundle = new ZendExt_Component_Bundle($this->idSequecence++, (string) $cargar_bundle_xml->data["state"], $path);
                $Bundle->setName($name);
                $Bundle->setVersion((string) $cargar_bundle_xml->data["version"]);
                foreach ($services->service as $tag) {
                    $service = new ZendExt_Component_Service((string) $tag["id"], (string) $tag["impl"], (string) $tag["interface"]);
                    $Bundle->ADDlService($service);
                }
                foreach ($dependencies->dependency as $tag) {
                    $optional = isset($tag["optional"]) ? (bool) $tag["optional"] : false;
                    if (isset($tag["use"])) {
                        $dependencie = new ZendExt_Component_ServiceDependent((string) $tag["id"], null, (string) $tag["interface"], $optional, (string) $tag["use"]);
                    } else {
                        $dependencie = new ZendExt_Component_ServiceDependent((string) $tag["id"], null, (string) $tag["interface"], $optional);
                    }
                    $Bundle->ADDLDependencias($dependencie);
                }
                foreach ($eventSources->source as $tag) {
                    if (isset($tag["class"])) {
                        $add = substr($dir, 0, strrpos($dir, DIRECTORY_SEPARATOR) + 1);
                        $eventSource = new ZendExt_Component_SourceEvent((string) $tag["id"], $add . (string) $tag["class"]);
                    } else {
                        $eventSource = new ZendExt_Component_SourceEvent((string) $tag["id"]);
                    }
                    $Bundle->ADDLSourceEvent($eventSource);
                }
                foreach ($observers->observer as $tag) {
                    $observer = new ZendExt_Component_ObserverEvent((string) $tag["impl"], (string) $tag["source"]);
                    $Bundle->ADDLObserver($observer);
                }
                $this->bundlesList[] = $Bundle;
                return $Bundle;
            }
    }

    public function getListaBundles() {
        return $this->bundlesList;
    }

}

?>
