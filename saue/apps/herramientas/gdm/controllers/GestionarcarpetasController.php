<?php

/**
 * Componente para gestinar los sistemas.
 *
 * @package Sauxe_v2.3
 * @Copyright UCI
 * @Author Abraham Calas
 * @Author René R. Bauta Camejo
 * @Version 3.0-1
 */

class GestionarcarpetasController extends ZendExt_Controller_Secure {

    public function init() {
        parent::init();
    }

    public function gestionarcarpetasAction() {
        $this->render();
    }

    public function autoregistarModulosAction() {
        $this->recursiveRegistration();
        $this->showMessage('Se han registrado los módulos satisfactoriamente.');
    }

    //autoregistrar componentes
    public function recursiveRegistration($idpadre = 0, $dir = "") {
        $files = new InstrospectorModel();
        $sistemas = $this->obtenerSistemas();
        $allfiles = $files->getFilesByPath($this->pathReal($dir), true);
        foreach ($allfiles as $file) {
            if ($file->text != "comun") {
                $dominio = $this->global->Perfil->iddominio;
                if ($idpadre == 0) {
                    $path = $this->pathReal();
                    $register = Zend_Registry::getInstance();
                    $dirModulesConfig = $register->config->xml->components;
                    $xml = new SimpleXMLElement($dirModulesConfig, null, true);
                    $componente = ($this->checkDirectory($file) == "paquete") ? false : true;
                    $check = true;
                    $nombre = str_replace("_", " ", $file->text);
                    $idnodopadre = 0;
                    $abrev = $file->text;
                    //comprobar si existe el subsitema en seguridad con esa abreviatura
                    $funcionalidades = $this->obtenerFuncionalidades($idpadre);
                    $subs = $this->verificaSistema(array("idpadre" => 0, "denominacion" => $nombre, "abreviatura" => $abrev, "dir" => DIRECTORY_SEPARATOR . $abrev . DIRECTORY_SEPARATOR), $funcionalidades, $sistemas);
                    if ($subs) {
                        //si existe, tomo la denominacion y el id y el
                        $id = $subs["id"];
                        $nombre = $subs["denominacion"];
                        $abrev = $subs["abreviatura"];
                    } else {
                        $result = $this->searchSistema($sistemas, $nombre, $abrev);
                        if ($result) {
                            $id = $result["id"];
                            $nombre = $result["denominacion"];
                            $abrev = $result["abreviatura"];
                        }
                        else
                            $id = $this->integrator->seguridad->insertarsistema($idnodopadre, $nombre, $abrev, $dominio);
                        //Si tiene controladores, insertarlos como funcionalidad, esto lo va hacer otra gente.
                    }

                    $dirConfig = $register->config->module->$nombre;
                    if (!$dirConfig && $componente)
                        file_put_contents($this->pathReal() . DIRECTORY_SEPARATOR . "config.php", "\n        " . '$config[\'module\'][\'' . $nombre . '\']=$dir_abs_mt . \'' . "apps" . DIRECTORY_SEPARATOR . $file->text . '/\';', FILE_APPEND);

                    if (!$this->existeAlMismoNivel($xml, "cmp" . $id)) {

                        $xmld_child = $xml->addChild('cmp' . $id);
                        $xmld_child->addAttribute("id", $id);
                        $xmld_child->addAttribute("text", $nombre);
                        $xmld_child->addAttribute("leaf", $componente);
                        $xmld_child->addAttribute("path", DIRECTORY_SEPARATOR . $file->text);
                        $xmld_child->addAttribute("component", $componente);
                        $xmld_child->addAttribute("abrev", $abrev);
                        file_put_contents($dirModulesConfig, $xml->asXML());
                    }
                    $this->CreaCarpetas($id, $path . $file->text, $componente, $nombre, DIRECTORY_SEPARATOR . $file->text);
                } else {
                    $path = $file->path;
                    $register = Zend_Registry::getInstance();
                    $xml_complete = simplexml_load_file($this->pathReal($dir) . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "components.xml");
                    $nodopadre = 'cmp' . $idpadre;
                    $componente = ($this->checkDirectory($file) == "paquete") ? false : true;
                    $check = true;
                    $nombre = str_replace("_", " ", $file->text);
                    $abrev = $file->text;

                    $subs = $this->verificaSistema(array("idpadre" => $idpadre, "denominacion" => $nombre, "abreviatura" => $abrev, "dir" => $dir . DIRECTORY_SEPARATOR . $abrev . DIRECTORY_SEPARATOR), $funcionalidades, $sistemas);
                    if ($subs) {
                        if ($subs["id"] == $idpadre) {
                            $result = $this->searchSistema($sistemas, $nombre, $abrev, $idpadre);
                            if ($result) {

                                $id = $result["id"];
                                $nombre = $result["denominacion"];
                                $abrev = $result["abreviatura"];
                            }
                            else
                                $id = $this->integrator->seguridad->insertarsistema($idpadre, $nombre, $abrev, $dominio);
                        } else {
                            $id = $subs["id"];
                            $nombre = $subs["denominacion"];
                            $abrev = $subs["abreviatura"];
                        }
                    } else {
                        $result = $this->searchSistema($sistemas, $nombre, $abrev, $idpadre);
                        if ($result) {

                            $id = $result["id"];
                            $nombre = $result["denominacion"];
                            $abrev = $result["abreviatura"];
                        }
                        else
                            $id = $this->integrator->seguridad->insertarsistema($idpadre, $nombre, $abrev, $dominio);
                        //Si tiene controladores, insertarlos como funcionalidad, esto lo va hacer otra gente.
                    }

                    $dirConfig = $register->config->module->$nombre;
                    if (!$dirConfig && $componente)
                        file_put_contents($this->pathReal() . DIRECTORY_SEPARATOR . "config.php", "\n        " . '$config[\'module\'][\'' . $nombre . '\']=$dir_abs_mt . \'' . "apps" . DIRECTORY_SEPARATOR . substr($dir, 1) . DIRECTORY_SEPARATOR . $abrev . '/\';', FILE_APPEND);
                    if (!$this->existeAlMismoNivel($xml_complete->$nodopadre, "cmp" . $id)) {
                        $xmld_child = $xml_complete->$nodopadre->addChild('cmp' . $id);
                        $xmld_child->addAttribute("id", $id);
                        $xmld_child->addAttribute("text", $nombre);
                        $xmld_child->addAttribute("leaf", $componente);
                        $xmld_child->addAttribute("path", $dir . DIRECTORY_SEPARATOR . $file->text);
                        $xmld_child->addAttribute("component", $componente);
                        $xmld_child->addAttribute("abrev", $abrev);
                        file_put_contents($this->pathReal($dir) . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "components.xml", $xml_complete->asXML());
                    }
                    $this->CreaCarpetas($id, $path, $componente, $nombre, $dir . DIRECTORY_SEPARATOR . $file->text);
                }
                //arreglar que si es un paquete, ver que hacer para insertarlo
                if ($this->checkDirectory($file) == "paquete")
                    $this->recursiveRegistration($id, $dir . DIRECTORY_SEPARATOR . $file->text);
            }
        }
    }

    private function obtenerFuncionalidades($idpadre) {
        try {
            $dominio = $this->global->Perfil->iddominio;
            $func = $this->integrator->seguridad->loadAllFuncionalidad($dominio, $idpadre);
            return $func;
        } catch (Exception $ee) {
            $this->showMessage('Error al cargar las funcionalidades.');
            return;
        }
    }

    private function obtenerSistemas() {
        try {
            $dominio = $this->global->Perfil->iddominio;
            $func = $this->integrator->seguridad->loadAllSistemas($dominio);
            return $func;
        } catch (Exception $ee) {
            $this->showMessage('Error al cargar los sistemas.');
            return;
        }
    }

    private function searchSistema($sistemas, $denom, $abrev, $idpadre = 0) {
        $flag = true;
        foreach ($sistemas as $sistema) {
            $idp = $idpadre == 0 ? $sistema["idpadre"] : $idpadre;
            if ($idp == $sistema["idpadre"] && (strtolower($sistema["denominacion"]) == $denom || strtolower($sistema["abreviatura"]) == $abrev || $sistema["denominacion"] == $denom || $sistema["abreviatura"] == $abrev)) {
                $flag = false;
                $id = $sistema["idsistema"];
                $denom = $sistema["denominacion"];
                $abrev = $sistema["abreviatura"];
                break;
            }
        }
        if (!$flag) {
            return array("denominacion" => $denom, "abreviatura" => $abrev, "id" => $id);
        }
        return;
    }

    public function searchSistemaPadre() {

    }

    //verificar que la direccion no este como funcionalidad, si lo está entonces
    //devolver el id del sistema, la abrev y la denom
    //Si no está como funcionalidad  hay que verificar
    // que la denom y abrev del sistema no esta registrado, si lo está
    // devolver un nuevo nombre o denom
    //en datos va $idpadre, $denom, $abrev, $dir
    private function verificaSistema($datos, $funcionalidades, $sistemas) {

        if (array_key_exists($datos["dir"], $funcionalidades)) {
            $result["id"] = $funcionalidades[$datos["dir"]]["idsistema"];
            $result["denominacion"] = $funcionalidades[$datos["dir"]]["DatSistema"]["denominacion"];
            $result["abreviatura"] = $funcionalidades[$datos["dir"]]["DatSistema"]["abreviatura"];
            return $result;
        }
        return;
    }

    public function checkDirectory($file) {
        $files = new InstrospectorModel();
        $allfiles = $files->getFilesByPath($file->path, true);
        foreach ($allfiles as $file) {
            if ($file->text == "controllers") {
                $flag = true;
                break;
            }
        }
        if ($flag == true)
            return "modulo";
        else
            return "paquete";
    }

    //construye la direccion real de cualquier fichero dado su dir relativa
    function pathReal($path = "") {
        $dirweb = $_SERVER[DOCUMENT_ROOT];
        $dirapps = str_replace('web', 'apps', $dirweb);
        $final = ($path == "") ? DIRECTORY_SEPARATOR : "";
        return $dirapps . $path . $final;
    }

//permite eliminar directorio
    function deleteAll($directory, $empty = false) {
        if (substr($directory, -1) == DIRECTORY_SEPARATOR) {
            $directory = substr($directory, 0, -1);
        }
        if (!file_exists($directory) || !is_dir($directory)) {
            return false;
        } elseif (!is_readable($directory)) {
            return false;
        } else {
            $directoryHandle = opendir($directory);
            while ($contents = readdir($directoryHandle)) {
                if ($contents != '.' && $contents != '..') {
                    $path = $directory . DIRECTORY_SEPARATOR . $contents;
                    if (is_dir($path)) {
                        $this->deleteAll($path);
                    } else {
                        unlink($path);
                    }
                }
            }
            closedir($directoryHandle);

            if ($empty == false) {
                if (!rmdir($directory)) {
                    return false;
                }
            }
            return true;
        }
    }

//permite eliminar nodo  del XML
    function removeNode($xml, $path, $multi = 'one') {
        $result = $xml->xpath($path);

        if (!isset($result[0]))
            return false;

        switch ($multi) {
            case 'all':
                $errlevel = error_reporting(E_ALL & ~E_WARNING);
                foreach ($result as $r) {
                    unset($r[0]);
                }
                error_reporting($errlevel);
                return true;
            case 'child':
                unset($result[0][0]);
                return true;

            case 'one':
                if (count($result[0]->children()) == 0 && count($result) == 1) {
                    unset($result[0][0]);
                    return true;
                }
            default:
                return false;
        }
    }

//permite comprobar si existe un nodo con la misma etiqueta
    function existeAlMismoNivel($xml, $path) {
        $result = $xml->xpath($path);
        if ($result[0])
            return true;
        return false;
    }

    function xmlPath() {
        return DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "components.xml";
    }

//permite eliminar cmp o paquete
    function eliminarCmp($idnodopadre, $path, $nombre_padre, $arreglo_padre, $check) {

        if ($idnodopadre != 0) {
            $path_real = $this->pathReal($path) . $this->xmlPath();
            if (isset($arreglo_padre[1])) {
                $temp = split(DIRECTORY_SEPARATOR, $path);
                $path_parent = str_replace($temp[count($temp) - 1] . DIRECTORY_SEPARATOR, '', $path_real);
                $xml_parent = simplexml_load_file($path_parent);


                $childs = $xml_parent->children();
                $remove_in_parent = $this->removeNode($childs, 'cmp' . $idnodopadre/* $arreglo_padre[1].DIRECTORY_SEPARATOR.$nombre_padre */, 'all');
            }
        }
        $register = Zend_Registry::getInstance();
        $dirModulesConfig = $register->config->xml->components;
        $xml_complete = new SimpleXMLElement($dirModulesConfig, null, true);
        $remove_nodo_xml_comp = $this->removeNode($xml_complete, 'cmp' . $idnodopadre, 'all');

        if (isset($remove_nodo_xml_comp) || isset($remove_in_parent)) {

            if ($remove_nodo_xml_comp) {
                file_put_contents($dirModulesConfig, $xml_complete->asXML());
            }
            if ($remove_in_parent) {
                file_put_contents($path_parent, $xml_parent->asXML());
            }
            if ($check) {
                $this->removeSearchedLine($this->pathReal() . DIRECTORY_SEPARATOR . "config.php", '$config[\'module\'][\'' . $nombre_padre . '\']=');
            } else {
                $this->removeSearchedLine($this->pathReal() . DIRECTORY_SEPARATOR . "config.php", substr($path, 1) . DIRECTORY_SEPARATOR);
            }
            $dir_web = str_replace('apps', 'web', $this->pathReal($path));

            if ($this->deleteAll($this->pathReal($path)) && $this->deleteAll($dir_web)) {
                return true;
            }
        }
        else
            false;
    }

    function removeSearchedLine($file, $busqueda) {
        if (file_exists($file)) {
            foreach (file($file) as $line) {
                if (!stristr($line, $busqueda)) {
                    $lines[] = $line;
                } else {
                    $deleted[] = $line;
                }
            }
            file_put_contents($file, $lines);
            return $deleted;
        }
    }

//permite eliminar cmp o paquete  (desde IU)
    function eliminarAction() { //revisar
        $path = $this->_request->getPost('path');
        $nombre_padre = $this->_request->getPost('nombre');
        $idnodopadre = $this->_request->getPost('node');
        $check = $this->_request->getPost('component');
        $arreglo_padre = json_decode(stripslashes($this->_request->getPost('patharray')));

        $delxml = $this->eliminarCmp($idnodopadre, $path, $nombre_padre, $arreglo_padre, $check);
        $del = $this->integrator->seguridad->eliminarsistema($idnodopadre); //$idfuncionalidad,$den,$abrevP,$abrevH

        if ($delxml == 1 && $del == 1) {
            if ($check)
                $this->showMessage('El componente se ha eliminado satisfactoriamente.');
            else
                $this->showMessage('El paquete de componente se ha eliminado satisfactoriamente.');
        }
        else
            $this->showMessage('No se puede realizar la operaci & oacute;
            n.');
    }

// permite contar la cantidad de hijos de un nodo
    public function count($children) {
        $count = 0;
        foreach ($children as $datos) {
            if (!empty($datos))
                $count++;
        }
        return $count;
    }

//permite copiar hijos y crear la carpetas respectivas
    function copiarHijos($nodo_nuevo, $nodo_viejo, $arreglo_padre) {

        if (count($nodo_viejo->children()) == 0)
            return $nodo_nuevo;
        else {
            foreach ($nodo_viejo->children() as $hijo) {
                $nodo_hijo = $nodo_nuevo->addChild($hijo->attributes()->text);
                $nodo_hijo->addAttribute("id", $hijo->attributes()->id);
                $nodo_hijo->addAttribute("text", $hijo->attributes()->text);
                $nodo_hijo->addAttribute("leaf", $hijo->attributes()->leaf);

                $path_new = str_replace($nodo_viejo->attributes()->path, $nodo_nuevo->attributes()->path, (string) $hijo->attributes()->path);
                $nodo_hijo->addAttribute("path", $path_new);
                $nodo_hijo->addAttribute("component", $hijo->attributes()->component);
                if (!$hijo->attributes()->component) {
                    $xml_complete = simplexml_load_file((string) $hijo->attributes()->path . $this->xmlPath());
                    $xml_nodo = $this->buscarNodo($xml_complete, $hijo->attributes()->text);
                    $this->copiarHijos($nodo_hijo, $xml_nodo, $arreglo_padre);
                }
            }
            return $nodo_nuevo;
        }
    }

//modificar cmp.....si terminar
    function copiamodificarComponentePaqueteAction() { //si terminar
        $idnodo = $this->_request->getPost('node');
        $path = $this->_request->getPost('path');
        $nombre_new = $this->_request->getPost('text');
        $nombre_actual = $this->_request->getPost('nombre');
        $arreglo_padre = json_decode(stripslashes($this->_request->getPost('patharray')));
        $componente = $this->_request->getPost('component');
        //se construye el path nuevo
        $path_new = str_replace($nombre_actual, $nombre_new, $path);
        //se  modifica el text del nodo en xml base dado el id si no es un cmp
        if (!$componente) {
            $xml_complete = simplexml_load_file($path . $this->xmlPath());
            $xml_nodo = $this->buscarNodo($xml_complete, 'cmp' . $idnodo);
            print_r($xml_complete->asXML());
            die('ggg');
            $xml_nodo->attributes()->text = $nombre_new;
            $xml_nodo->attributes()->path = $path_new;
            file_put_contents($path . $this->xmlPath(), $xml_complete->asXML());
        }

        //se determina el xml padre para realizar la modificacion
        if (isset($arreglo_padre[1])) {
            $path_parent = str_replace($nombre_actual . DIRECTORY_SEPARATOR, '', $path);
            $xml_parent = simplexml_load_file($path_parent . $this->xmlPath());
        } else {
            $register = Zend_Registry::getInstance();
            $dirModulesConfig = $register->config->xml->components;
            $xml_parent = new SimpleXMLElement($dirModulesConfig, null, true);
        }
        //if(!$this->existeAlMismoNivel($xml_parent->$nombre_actual,$nombre_new))	{
        //se adiciona un nodo nuevo con el text a modificar en xml padre
        $xml_nodo = $this->buscarNodo($xml_parent, 'cmp' . $idnodo);
        $xml_nodo->attributes()->text = $nombre_new;
        $xml_nodo->attributes()->path = $path_new;

        //se escribe en los XML padre afectado
        if (empty($path_parent))
            file_put_contents($dirModulesConfig, $xml_parent->asXML());
        else
            file_put_contents($path_parent . $this->xmlPath(), $xml_parent->asXML());

        //se renombran las carpetas del nodo en apps y web
        if (!dir_exists(dirname($path_new))) {
            mkdir(dirname($path_new), 0777, true);
        }
        rename($path, $path_new); //se cambia nombre de directorios en apps
        $path_web = str_replace('apps', 'web', $path);
        if (!dir_exists(dirname($path_web))) {
            mkdir(dirname($path_web), 0777, true);
        }
        rename($path, $path_web); //se cambia nombre de directorios en web
        //se copian los hijos en el xml del nodo ha modificar y crea sus carpetas
        $this->copiarHijos($nodo_nuevo, $xml_nodo, $arreglo_padre);

        //se eliminan nodo viejo del xml padre y del xml propio
        $this->eliminarCmp($idnodo, $path, $nombre_actual, $arreglo_padre);

        if ($componente)
            $this->showMessage('El componente se ha modificado satisfactoriamente.');
        else
            $this->showMessage('El paquete de componente se ha modificado satisfactoriamente.');
        //}else echo "{'codMsg':3,'mensaje': 'Verifique, existe a este nivel un elemento con el mismo nombre.'}";
    }

    function replacePath($old, $new, $path) {
        $temp = split(DIRECTORY_SEPARATOR, $path);
        $cont = 0;
        foreach ($temp as $cambio) {
            if ($cambio == $old) {
                $temp[$cont] = $new;
            }
            $cont++;
        }
        $newPath = "";
        foreach ($temp as $cambio) {
            if ($cambio != "") {
                $newPath .= DIRECTORY_SEPARATOR . $cambio;
            }
        }
        return $newPath;
    }

    function getWebPath($path = "") {
        $dirweb = $_SERVER[DOCUMENT_ROOT];
        $final = ($path == "") ? DIRECTORY_SEPARATOR : "";
        return $dirweb . $path . $final;
    }

    function modificarAllChildren($path_child, $id, $abrev_actual, $abrev) {
        $xml_child = simplexml_load_file($this->pathReal($path_child) . $this->xmlPath());

        $firstChild = $this->buscarNodo($xml_child, 'cmp' . $id);
        $firstChild->attributes()->path = $path_child;

        foreach ($firstChild->children() as $child) {
            $path_child2 = $this->replacePath($abrev_actual, $abrev, (string) $child->attributes()->path);
            $child->attributes()->path = $path_child2;
            $this->modificarAllChildren($path_child2, $child->attributes()->id, $abrev_actual, $abrev);
        }
        file_put_contents($this->pathReal($path_child) . $this->xmlPath(), $xml_child->asXML());
    }

//modificar cmp.....si terminar
    function modificarComponentePaqueteAction() { //si terminar
        $idnodo = $this->_request->getPost('node');
        $path = $this->_request->getPost('path');
        $nombre_actual = $this->_request->getPost('text_actual');
        $nombre_new = $this->_request->getPost('text');
        $abrev_actual = $this->_request->getPost('abrev_actual');
        $arreglo_padre = json_decode(stripslashes($this->_request->getPost('patharray')));
        $componente = $this->_request->getPost('component');
        $abrev = $this->_request->getPost('abrev');

        //se construye el path nuevo
        $path_new = $this->replacePath($abrev_actual, $abrev, $path);

        //se obtiene nodo a modificar
        //if(!$componente){
        $xml_complete = simplexml_load_file($this->pathReal($path) . $this->xmlPath());

        $xml_nodo = $this->buscarNodo($xml_complete, 'cmp' . $idnodo);
        $xml_nodo->attributes()->text = $nombre_new;
        $xml_nodo->attributes()->abrev = $abrev;
        $xml_nodo->attributes()->path = $path_new;
        //  }
        //se determina el xml padre para realizar la modificacion
        if (isset($arreglo_padre[1])) {

            $path_parent = $this->replacePath($abrev_actual, '', $this->pathReal($path));
            $xml_parent = simplexml_load_file($path_parent . $this->xmlPath());
        } else {
            $register = Zend_Registry::getInstance();
            $dirModulesConfig = $register->config->xml->components;
            $xml_parent = new SimpleXMLElement($dirModulesConfig, null, true);
        }
        if (!$this->existeAlMismoNivel($xml_parent->$abrev_actual, $abrev)) {
            $xml_nodo_parent = $this->buscarNodo($xml_parent, 'cmp' . $idnodo);
            $xml_nodo_parent->attributes()->text = $nombre_new;
            $xml_nodo_parent->attributes()->abrev = $abrev;
            $xml_nodo_parent->attributes()->path = $path_new;

            //se renombran las carpetas del nodo en apps /
            if (is_dir($this->pathReal($path))) {
                chmod($this->pathReal($path), 0777);
                rename($this->pathReal($path), $this->pathReal($path_new));
                //chmod($path_new, 777);
            }

            //se cambia nombre de directorios en web
            $path_web = $this->getWebPath($path);
            if (is_dir($path_web)) {
                $path_web_new = $this->replacePath('apps', 'web', $this->pathReal($path_new));
                chmod($path_web, 0777);
                rename($path_web, $path_web_new);
                //chmod($path_web_new, 777);
            }

            foreach ($xml_nodo->children() as $child) {
                //print_r((string)$child->attributes()->path);die;
                $path_child = $this->replacePath($abrev_actual, $abrev, (string) $child->attributes()->path);
                $child->attributes()->path = $path_child;
                $this->modificarAllChildren($path_child, $child->attributes()->id, $abrev_actual, $abrev);
            }

            $str_XML = $xml_complete->asXML();
            $str_XML_parent = $xml_parent->asXML();

            //se escribe en los XMLs afectados
            file_put_contents($this->pathReal($path_new) . $this->xmlPath(), $str_XML);

            //escribiendo en el xml
            if (!($path_parent)) {
                file_put_contents($dirModulesConfig, $str_XML_parent);
            } else {
                file_put_contents($path_parent . $this->xmlPath(), $str_XML_parent);
            }

            //modificando el config
            if ($componente) {
                $this->removeSearchedLine($this->pathReal() . DIRECTORY_SEPARATOR . "config.php", '$config[\'module\'][\'' . $nombre_actual . '\']=');
                file_put_contents($this->pathReal() . DIRECTORY_SEPARATOR . "config.php", "        " . '$config[\'module\'][\'' . $nombre_new . '\']=$dir_abs_mt . \'' . "apps" . $path_new . DIRECTORY_SEPARATOR . '\';', FILE_APPEND);
            } else {
                $lines = $this->removeSearchedLine($this->pathReal() . DIRECTORY_SEPARATOR . "config.php", substr($path, 1) . DIRECTORY_SEPARATOR);
                foreach ($lines as $line) {
                    $dir = split("\.", $line);
                    $dir[1] = str_replace("'", "", $dir[1]);
                    $dir[1] = str_replace(";", "", $dir[1]);
                    $dir[1] = str_replace(" ", "", $dir[1]);
                    $dir[1] = eregi_replace("[\n|\r|\n\r]", "", $dir[1]);
                    $dir[1] = $this->replacePath($abrev_actual, $abrev, $dir[1]);
                    $dir[1] = substr($dir[1], 1);
                    $newConf = $dir[0] . ". '" . $dir[1] . DIRECTORY_SEPARATOR . "';\n";
                    file_put_contents($this->pathReal() . DIRECTORY_SEPARATOR . "config.php", $newConf, FILE_APPEND);
                }
            }

            //            |
            //            |
            //            |
            //            |
            //            |
            //            |
            //            |
            //            V
            /////////////////////// ESTO HAY QUE VER PK NO FUNCIONA
            $this->integrator->seguridad->modificarsistema($idnodo, $nombre_new, $abrev);

            if ($componente)
                $this->showMessage('El componente se ha modificado satisfactoriamente.');
            else
                $this->showMessage('El paquete de componente se ha modificado satisfactoriamente.');
        }
        else
            echo "{'codMsg':3,'mensaje': 'Verifique, existe a este nivel un elemento con el mismo nombre.'}";
    }

    function cargarsistemaAction() {
        $nodo = $this->_request->getPost('node');
        $path = $this->_request->getPost('path');
        $nombre = $this->_request->getPost('text');
        $abrev = $this->_request->getPost('abrev');
        $arreglo_auxiliar = array();
        if ($nodo == 0) {
            $register = Zend_Registry::getInstance();
            $dirModulesConfig = $register->config->xml->components;

            $xml = new SimpleXMLElement($dirModulesConfig, null, true);
        } else {
            $real_path = $this->pathReal($path);
            $xml_complete = simplexml_load_file($real_path . $this->xmlPath());
            $xml = $this->buscarNodo($xml_complete, 'cmp' . $nodo);
        }
        $cont = 0;
        $array_return = array();

        foreach ($xml->children() as $child) {
            $arreglo_auxiliar['id'] = (string) $child['id'];
            $arreglo_auxiliar['text'] = (string) $child['text'];
            if ((string) $child['component'] == "1")
                $arreglo_auxiliar['leaf'] = true;
            else
                $arreglo_auxiliar['leaf'] = false;
            $arreglo_auxiliar['path'] = (string) $child['path'];
            $arreglo_auxiliar['component'] = (string) $child['component'];
            $arreglo_auxiliar['abrev'] = (string) $child['abrev'];
            $array_return[$cont] = $arreglo_auxiliar;
            $cont++;
        }
        echo json_encode($array_return);
        return;
    }

//permite cargar los requisitos dado el componente
    function cargarRQFNAction() {
        $path = $this->_request->getPost('path');
        $nombre = $this->_request->getPost('den');
        $isrq = $this->_request->getPost('isrq');
        $idnodo = $this->_request->getPost('idnodo');
        $file = ($isrq) ? 'requisitos' : 'funcionalidades';
        $dominio = $this->global->Perfil->iddominio;
        $funcionalidades = $this->integrator->seguridad->loadAllFuncionalidad($dominio, $idnodo);

        //$xml_complete = simplexml_load_file($this->pathReal($path) . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . $file . ".xml");
        //$xml = $this->buscarNodo($xml_complete, 'cmp' . $idnodo);

        $cont = 0;
        $array_return = array();
        //print_r($funcionalidades);die('rene');
        foreach ($funcionalidades as $child) {
			//print_r($child);die('lolo');
            $arreglo_auxiliar['id'] = $child['idfuncionalidad'];
            $arreglo_auxiliar['den'] = $child['den'];
            $arreglo_auxiliar['desc'] = $child['descripcion'];
            $var = $child['referencia'];
            $abr = explode('/', $var);
            $arreglo_auxiliar['abrev'] = $abr[count($abr) - 1];
            //$xml_nodo = $this->buscarNodo($xml, 'rq' . $child['idfuncionalidad']);
            //$arreglo_auxiliar['fecha'] = ((string)$xml_nodo->attributes()->fecha);
            $array_return[$cont] = $arreglo_auxiliar;
            $cont++;
        }
        echo json_encode(array('datos' => $array_return));
        //print_r($array_return);die('ff');
        return;
    }

//permite adicionar requisitos a un componente
    public function adicionarRQFNAction() {
        //$nombre_cmp = $this->_request->getPost('nombre');
        $idnodopadre = $this->_request->getPost('node');
        $path = $this->_request->getPost('path');

        $den = $this->_request->getPost('den');
        $desc = $this->_request->getPost('desc');
        $fecha = $this->_request->getPost('fecha');
        $isrq = $this->_request->getPost('isrq');
        $abrev = $this->_request->getPost('abrev');
        $file = ($isrq) ? 'requisitos' : 'funcionalidades';
        $dominio = $this->global->Perfil->iddominio;

        $xml_complete = simplexml_load_file($this->pathReal($path) . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . $file . ".xml");
        $xml_nodo = $this->buscarNodo($xml_complete, 'cmp' . $idnodopadre);

        if (!$this->existeRQ($xml_nodo, $den)) {
            $id_nodo = 'rq' . rand(0, 1000000000000000);
            if (!$isrq) {
                $this->Crearfuncionalidad($this->pathReal($path), $abrev);
                $id = $this->integrator->seguridad->insertarfuncionalidad($idnodopadre, $den, $path, $abrev, $dominio, $desc);
                $id_nodo = 'rq' . $id;
            }
            $nodo = 'cmp' . $idnodopadre;
            $xmld_child = $xml_complete->$nodo->addChild($id_nodo);
            $xmld_child->addAttribute("den", $den);
            $xmld_child->addAttribute("desc", $desc);
            $xmld_child->addAttribute("parent", $idnodopadre);
            $xmld_child->addAttribute("fecha", $fecha);
            $xmld_child->addAttribute("abrev", $abrev);
            file_put_contents($this->pathReal($path) . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . $file . ".xml", $xml_complete->asXML());
            $text = ($isrq) ? 'El requisito se ha adicionado satisfactoriamente.' : ' La funcionalidad se ha adicionado satisfactoriamente.';
            $this->showMessage($text);
        }
        else
            echo "{'codMsg':3,'mensaje': 'Verifique, existe un requisito con la misma denominaci&oacute;n.'}";
    }

//permite modificar requisitos a un componente
    public function modificarRQFNAction() {
        $nombre_cmp = $this->_request->getPost('nombre');
        $idnodopadre = $this->_request->getPost('node');
        $path = $this->_request->getPost('path');
        $den = $this->_request->getPost('den');
        $desc = $this->_request->getPost('desc');
        $fecha = $this->_request->getPost('fecha');
        $id_req = $this->_request->getPost('idRQ');
        $isrq = $this->_request->getPost('isrq');
        $file = ($isrq) ? 'requisitos' : 'funcionalidades';
        $abrev = $this->_request->getPost('abrev');
///Modificar esto de abajo
        $xml_complete = simplexml_load_file($this->pathReal($path) . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . $file . ".xml");
        $xml = $this->buscarNodo($xml_complete, 'cmp' . $idnodopadre);
        $xml_nodo = $this->buscarNodo($xml, 'rq' . $id_req);

        if (!empty($xml_nodo)) {
            $xml_nodo->attributes()->den = $den;
            $xml_nodo->attributes()->desc = $desc;
            $xml_nodo->attributes()->fecha = $fecha;
            $xml_nodo->attributes()->abrev = $abrev;

            file_put_contents($this->pathReal($path) . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . $file . ".xml", $xml_complete->asXML());
            if (!$isrq) {
                $this->eliminarfuncionalidad($this->pathReal($path), $abrev);
                $this->Crearfuncionalidad($this->pathReal($path), $abrev);
                $this->integrator->seguridad->modificarfuncionalidad(str_replace('rq', '', $id_req), $den, $nombre_cmp, $abrev); //$idfuncionalidad,$den,$abrevP,$abrevH
            }
            $text = ($isrq) ? 'El requisito se ha modificado satisfactoriamente.' : ' La funcionalidad se ha modificado satisfactoriamente.';
            $this->showMessage($text);
        }
        else
            echo "{'codMsg':3,'mensaje': 'El componente que desea modificar no se encuentra en los registros.'}";
    }

//permite eliminar un requisito
    public function eliminarRQFNAction() {
        $idnodopadre = $this->_request->getPost('node');
        $path = $this->_request->getPost('path');
        $array_idRQ = json_decode(stripslashes($this->_request->getPost('array_idRQ')));
        $isrq = $this->_request->getPost('isrq');
        $file = ($isrq) ? 'requisitos' : 'funcionalidades';
///Modificar esto de abajo
        $xml_complete = simplexml_load_file($this->pathReal($path) . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . $file . ".xml");
        $xml = $this->buscarNodo($xml_complete, 'cmp' . $idnodopadre);
        $cont = 0;
        foreach ($array_idRQ as $id_req) {
            $nodo = $this->buscarNodo($xml, 'rq' . $id_req);
            $den = $nodo->attributes()->den;
            $remove_in_parent = $this->removeNode($xml_complete, 'cmp' . $idnodopadre . DIRECTORY_SEPARATOR . $id_req, 'all');

            if (!$isrq) {
                $this->eliminarfuncionalidad($path, (string) $den);
                $this->integrator->seguridad->eliminarfuncionalidad(str_replace('rq', '', $id_req));
            }
            if ($remove_in_parent)
                $cont++;
        }
        file_put_contents($this->pathReal($path) . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . $file . ".xml", $xml_complete->asXML());
        if ($cont == count($array_idRQ)) {
            if (count($array_idRQ) > 1) {
                $this->showMessage('Los elementos seleccionados se han modificado satisfactoriamente.');
            } else {
                $text = ($isrq) ? 'El requisito se ha eliminado satisfactoriamente.' : ' La funcionalidad se ha eliminado satisfactoriamente.';
                $this->showMessage($text);
            }
        } else {
            if ($cont > 1)
                echo "{'codMsg':3,'mensaje': 'El elemento que desea eliminar no se encuentra en los registros.'}";
            else
                echo "{'codMsg':3,'mensaje': 'Algunos de los elementos que desea eliminar no se encuentran en los registros.'}";
        }
    }

//permite saber si existe un requisito con la misma denominacion
    public function existeRQ($xml, $den) {
        foreach ($xml->children() as $child) {
            if ($child->attributes()->den == $den)
                return true;
        }
        return false;
    }

    public function buscarNodo($xml, $nodo) {
        if ((string) $xml->getName() == $nodo)
            return $xml;
        else {
            foreach ($xml->children() as $hijo) {
                $xml_aux = $this->buscarNodo($hijo, $nodo);
                if ($xml_aux)
                    return $xml_aux;
            }
            return false;
        }
    }

    public function adicionarComponentePaqueteAction() {
        $nombre = $this->_request->getPost('text');
        $check = $this->_request->getPost('componente');
        $abrev = $this->_request->getPost('abrev');
        if ($check)
            $componente = true;
        else
            $componente = false;
        $idnodopadre = $this->_request->getPost('node');
        $crear = true;

        if ($idnodopadre != 0) {
            $path = $this->_request->getPost('path');
            $this->CheckDir($this->pathReal($path));
        } else {
            $path = $this->pathReal();
        }
        $dominio = $this->global->Perfil->iddominio;
        if ($idnodopadre == 0) {
            $register = Zend_Registry::getInstance();
            $dirModulesConfig = $register->config->xml->components;
            $dirConfig = $register->config->module->$nombre;
            $xml = new SimpleXMLElement($dirModulesConfig, null, true);
            if (!$this->existeAlMismoNivel($xml, $nombre) && !$dirConfig) {

                $id = $this->integrator->seguridad->insertarsistema($idnodopadre, $nombre, $abrev, $dominio);
                if ($componente) {
                    $leaf = true;
                    //$idfunc = $this->integrator->seguridad->insertarfuncionalidad($id, $nombre,$nombre,,$dominio);
                    //agregando la dir al config.php
                    file_put_contents($this->pathReal() . DIRECTORY_SEPARATOR . "config.php", "\n        " . '$config[\'module\'][\'' . $nombre . '\']=$dir_abs_mt . \'' . "apps" . DIRECTORY_SEPARATOR . $abrev . '/\';', FILE_APPEND);
                } else {
                    $leaf = false;
                    // $id = $this->integrator->seguridad->insertarsistema($idnodopadre, $nombre, $nombre, $dominio);
                }

                $xmld_child = $xml->addChild('cmp' . $id);
                $xmld_child->addAttribute("id", $id);
                $xmld_child->addAttribute("text", $nombre);
                $xmld_child->addAttribute("leaf", true);
                $xmld_child->addAttribute("path", DIRECTORY_SEPARATOR . $abrev);
                $xmld_child->addAttribute("component", $componente);
                $xmld_child->addAttribute("abrev", $abrev);
                //print_r($dirModulesConfig);die;
                file_put_contents($dirModulesConfig, $xml->asXML());
                $this->CreaCarpetas($id, $path . $abrev, $componente, $nombre, DIRECTORY_SEPARATOR . $abrev);
            }
            else
                $crear = false;
        } else {
            $dir = $path . DIRECTORY_SEPARATOR . $abrev;
            $xml_complete = simplexml_load_file($this->pathReal($path) . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "components.xml");
            $nodopadre = 'cmp' . $idnodopadre;
            $register = Zend_Registry::getInstance();
            $dirConfig = $register->config->module->$nombre;

            $id = $this->integrator->seguridad->insertarsistema($idnodopadre, $nombre, $abrev, $dominio);
            if (!$this->existeAlMismoNivel($xml_complete->$nodopadre, $nombre) && !$dirConfig) {
                if ($componente) {
                    $leaf = true;
                    //agregando la dir al config.php
                    //$idfunc = $this->integrator->seguridad->insertarfuncionalidad($id, $nombre, substr($path, 1) . DIRECTORY_SEPARATOR . $abrev ,$dominio);
                    file_put_contents($this->pathReal() . DIRECTORY_SEPARATOR . "config.php", "\n        " . '$config[\'module\'][\'' . $nombre . '\']=$dir_abs_mt . \'' . "apps" . $path . DIRECTORY_SEPARATOR . $abrev . '/\';', FILE_APPEND);
                } else {
                    $leaf = false;
                }

                $xmld_child = $xml_complete->$nodopadre->addChild('cmp' . $id);
                $xmld_child->addAttribute("id", $id);
                $xmld_child->addAttribute("text", $nombre);
                $xmld_child->addAttribute("leaf", true);
                $xmld_child->addAttribute("path", $dir);
                $xmld_child->addAttribute("component", $componente);
                $xmld_child->addAttribute("abrev", $abrev);
                file_put_contents($this->pathReal($path) . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "components.xml", $xml_complete->asXML());
                $this->CreaCarpetas($id, $this->pathReal() . $dir, $componente, $nombre, $dir);
            }
            else
                $crear = false;
        }

        if ($crear) {
            if (empty($id))
                echo "{'codMsg':3,'mensaje': 'No se ha podido realizar la operaci&oacute;n.'}";
            else {
                $msg = ($check) ? 'El componente se ha adicionado satisfactoriamente.' : 'El paquete de componente se ha adicionado satisfactoriamente.';
                $this->showMessage($msg);
            }
        }
        else
            echo "{'codMsg':3,'mensaje': 'Verifique, existe un elemento con el mismo nombre.'}";
    }

    private function CheckDir($path) {
//print_r($_SERVER);die;
        $string_path = "";
        $array_path = explode(DIRECTORY_SEPARATOR, $path);
        if ($array_path[0] == '')
            foreach ($array_path as $value) {
                if ((string) $value != '')
                    if (!file_exists($path . DIRECTORY_SEPARATOR . (string) $value))
                        return false;
            }
    }

    private function CreaCarpetas($idnodopadre, $path, $componente, $nombre, $abrevPath) {
        // print_r($idnodopadre.$path.$componente.$nombre.$arreglo_padre);
        if ($componente) {
            $this->CreaCarpetasWeb($idnodopadre, $path, $nombre, $componente);
            $this->CreaCarpetasApp($idnodopadre, $path, $nombre, $componente, $abrevPath);
        } else {
            $dirweb = str_replace('apps', 'web', $path);
            if (!file_exists($dirweb))
                mkdir($dirweb, 0777);
            if (!file_exists($path))
                mkdir($path, 0777);
            if (!file_exists($path . DIRECTORY_SEPARATOR . "comun"))
                mkdir($path . DIRECTORY_SEPARATOR . "comun", 0777);
            if (!file_exists($path . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos"))
                mkdir($path . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos", 0777);
            if (!file_exists($path . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "json"))
                mkdir($path . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "json", 0777);
            if (!file_exists($path . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml")) {
                mkdir($path . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml", 0777);
            }
            $this->CreaXmlComponents($idnodopadre, $abrevPath, $nombre, $path . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml", $componente);
        }
    }

    private function CreaCarpetasWeb($idnodopadre, $path, $nombre, $componente) {
        $dir = str_replace('apps', 'web', $path);
        if (!file_exists($dir)) {
            mkdir($dir, 0777);
            if (!file_exists($dir . DIRECTORY_SEPARATOR . "index"))
                $this->createindex($dir . DIRECTORY_SEPARATOR);
        }
        if (!file_exists($dir . DIRECTORY_SEPARATOR . "views"))
            mkdir($dir . DIRECTORY_SEPARATOR . "views", 0777);
        if (!file_exists($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "css"))
            mkdir($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "css", 0777);
        if (!file_exists($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "images"))
            mkdir($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "images", 0777);
        if (!file_exists($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "js"))
            mkdir($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "js", 0777);
//        if (!file_exists($dir . "/views/css/" . $nombre)) {
//            mkdir($dir . "/views/css/" . $nombre, 0777);
//            $fp = fopen($dir . "/views/css/" . $nombre . DIRECTORY_SEPARATOR . $nombre . ".css", 'w');
//            fclose($fp);
//        }
//        if (!file_exists($dir . "/views/js/" . $nombre)) {
//            mkdir($dir . "/views/js/" . $nombre, 0777);
//            $this->createjs($dir, $nombre);
//        }
    }

    function createindex($path) {

        $fp = fopen($path . "index.php", 'w');
        $peso = "$";
        $codigo = "<?php

	//Direccion de la servidora
	" . $peso . "dir_index = __FILE__;

	//Direccion del fichero de configuracion
	" . $peso . "config_file = substr(" . $peso . "dir_index, 0, strrpos(" . $peso . "dir_index, 'web')) . 'apps" . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "config.php';

	if (!file_exists(" . $peso . "config_file)) //Si no existe el fichero de configuracion
	{
		//Se dispara una excepcion
		throw new Exception('El fichero de configuracion no existe');
	}
	elseif (!is_readable(" . $peso . "config_file)) //Si no se puede leer
	{
		//Se dispara una excepcion
		throw new Exception('No se pudo leer el fichero de configuracion. Acceso denegado.');
	}
	else //Si existe el fichero y se puede leer
	{
		//Se inicializa la variable de configuraci?n
		" . $peso . "config = array();

		//Se incluye el fichero
		include_once (" . $peso . "config_file);

		if (!isset(" . $peso . "config['include_path']))
			throw new Exception('El framework no esta configurado correctamente.');

		//Se inicializa el include path de php a partir de la variable de configuracion
		set_include_path(" . $peso . "config['include_path']);
		require_once(" . $peso . "config['SAML']);
		//Se inicia la carga automatica de clases y ficheros
		" . $peso . "loader_file = 'Zend" . DIRECTORY_SEPARATOR . "Loader" . DIRECTORY_SEPARATOR . "Autoloader.php';
		if (!@include_once(" . $peso . "loader_file))
			throw new Exception('El framework no esta configurado correctamente.');
		" . $peso . "autoloader = Zend_Loader_Autoloader::getInstance();
		" . $peso . "autoloader->setFallbackAutoloader(true);
		//Se inicia la aplicacion
		" . $peso . "as = new SimpleSAML_Auth_Simple('ecotec');
		" . $peso . "as->requireAuth();
                " . $peso . "app = new ZendExt_App();
                " . $peso . "app->init(" . $peso . "config," . $peso . "as);
	}
";
        fwrite($fp, $codigo);
        fclose($fp);
    }

    function createjs($dir1, $nombre) {
        $fp = fopen($dir1 . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "js" . DIRECTORY_SEPARATOR . $nombre . DIRECTORY_SEPARATOR . $nombre . ".js", 'w');
        $comilla = "'";
        $codigo =
                "var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas(" . $comilla . $nombre . $comilla . ", function(){cargarInterfaz();});

////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();

function cargarInterfaz(){


var panel = new Ext.Panel({
		title:" . $comilla . $nombre . $comilla . ",
			id:'pepe',
			renderTo:'panel'
	});




var vpGestSistema = new Ext.Viewport({
			layout:'fit',
			items:panel
		});


}";
        fwrite($fp, $codigo);
        fclose($fp);
    }

    private function eliminarfuncionalidad($path, $nombre) {

        $this->deleteAll($path . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR . $nombre);
        $this->deleteAll($path . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "idioma" . DIRECTORY_SEPARATOR . "es" . DIRECTORY_SEPARATOR . $nombre);
        $this->deleteAll($path . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "idioma" . DIRECTORY_SEPARATOR . "en" . DIRECTORY_SEPARATOR . $nombre);
        $this->deleteAll($path . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "scripts" . DIRECTORY_SEPARATOR . $nombre);
        $path = str_replace('apps', 'web', $path);

        $this->deleteAll($path . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "css" . DIRECTORY_SEPARATOR . $nombre);
        $this->deleteAll($path . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "js" . DIRECTORY_SEPARATOR . $nombre);
    }

    /*
      private function Crearfuncionalidad($dir,$nombre){
      //creando ficheros app
      if(!file_exists($dir))
      mkdir($dir,0777);
      if(file_exists($dir."/controllers"))
      {
      $this->creacontroller($dir.DIRECTORY_SEPARATOR,$nombre);
      }
      if(file_exists($dir."/views/idioma/es"))
      {
      $fp = fopen($dir."/views/idioma/es/".$nombre.".json", 'w');
      fclose($fp);
      }
      if(file_exists($dir."/views/idioma/en"))
      {
      $fp0 = fopen($dir."/views/idioma/en/".$nombre.".json", 'w');
      fclose($fp0);
      }
      if(file_exists($dir."/views/idioma/es/".$nombre))
      {
      mkdir($dir."/views/idioma/es/".$nombre);
      $this->createidiomaes($dir.DIRECTORY_SEPARATOR,$nombre);
      }
      if(file_exists($dir."/views/idioma/en/".$nombre))
      {
      $this->createidiomaen($dir.DIRECTORY_SEPARATOR,$nombre);
      }
      if(file_exists($dir."/views/scripts/".$nombre))
      {	mkdir($dir."/views/scripts/".$nombre,0777);
      $this->crearphtml($dir.DIRECTORY_SEPARATOR,$nombre);
      }
      //creando ficheros web
      $dir = str_replace('apps','web', $dir);

      if(file_exists($dir."/views/css/"))
      {
      mkdir($dir."/views/css/".$nombre,0777);
      $fp = fopen($dir."/views/css/".$nombre."/".$nombre.".css", 'w');
      fclose($fp);
      }
      if(file_exists($dir."/views/js/"))
      {
      mkdir($dir."/views/js/".$nombre,0777);
      $this->createjs($dir,$nombre);
      }
      }
     */

    private function Crearfuncionalidad($dir, $nombre) {
        //creando ficheros app
        if (!file_exists($dir))
            mkdir($dir, 0777);
        if (file_exists($dir . "/controllers")) {
            $this->creacontroller($dir . DIRECTORY_SEPARATOR, $nombre);
        }
        /* if(file_exists($dir."/views/idioma/es"))
          {
          $fp = fopen($dir."/views/idioma/es".$nombre.".json", 'w');
          fclose($fp);
          }
          if(file_exists($dir."/views/idioma/en"))
          {
          $fp0 = fopen($dir."/views/idioma/en".$nombre.".json", 'w');
          fclose($fp0);
          } */
        if (file_exists($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "idioma" . DIRECTORY_SEPARATOR . "es" . DIRECTORY_SEPARATOR)) {
            mkdir($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "idioma" . DIRECTORY_SEPARATOR . "es" . DIRECTORY_SEPARATOR . $nombre, 0777);
            $this->createidiomaes($dir . DIRECTORY_SEPARATOR, $nombre);
        }
        if (file_exists($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "idioma" . DIRECTORY_SEPARATOR . "en" . DIRECTORY_SEPARATOR)) {
            mkdir($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "idioma" . DIRECTORY_SEPARATOR . "en" . DIRECTORY_SEPARATOR . $nombre, 0777);
            $this->createidiomaen($dir . DIRECTORY_SEPARATOR, $nombre);
        }
        //die($dir."/views/scripts/".$nombre."/");
        if (file_exists($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "scripts" . DIRECTORY_SEPARATOR)) {
            mkdir($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "scripts" . DIRECTORY_SEPARATOR . $nombre, 0777);
            $this->crearphtml($dir . DIRECTORY_SEPARATOR, $nombre);
        }
        //creando ficheros web
        $dir = str_replace('apps', 'web', $dir);

        if (file_exists($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "css" . DIRECTORY_SEPARATOR)) {
            mkdir($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "css" . DIRECTORY_SEPARATOR . $nombre, 0777);
            $fp = fopen($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "css" . DIRECTORY_SEPARATOR . $nombre . DIRECTORY_SEPARATOR . $nombre . ".css", 'w');
            fclose($fp);
        }
        if (file_exists($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "js" . DIRECTORY_SEPARATOR)) {
            mkdir($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "js" . DIRECTORY_SEPARATOR . $nombre, 0777);
            $this->createjs($dir, $nombre);
        }
    }

    private function CreaCarpetasApp($idnodopadre, $path, $nombre, $componente, $abrevPath) {
        $dir = $path;
        if (!file_exists($dir))
            mkdir($dir, 0777);
        if (!file_exists($path . DIRECTORY_SEPARATOR . 'services'))
            mkdir($path . DIRECTORY_SEPARATOR . 'services', 0777);
        if (!file_exists($dir . DIRECTORY_SEPARATOR . "controllers")) {
            mkdir($dir . DIRECTORY_SEPARATOR . "controllers", 0777);
            // $this->creacontroller($dir . DIRECTORY_SEPARATOR, $nombre);
        }
        if (!file_exists($dir . DIRECTORY_SEPARATOR . "models"))
            mkdir($dir . DIRECTORY_SEPARATOR . "models", 0777);
        if (!file_exists($dir . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . "bussines"))
            mkdir($dir . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . "bussines", 0777);
        if (!file_exists($dir . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . "domain"))
            mkdir($dir . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . "domain", 0777);
        if (!file_exists($dir . DIRECTORY_SEPARATOR . "views"))
            mkdir($dir . DIRECTORY_SEPARATOR . "views", 0777);
        if (!file_exists($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "idioma")) {
            mkdir($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "idioma", 0777);
            /* $fp = fopen($dir."/views/idioma/Idioma.json", 'w');
              fclose($fp); */
        }
        if (!file_exists($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "idioma" . DIRECTORY_SEPARATOR . "es"))
            mkdir($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "idioma" . DIRECTORY_SEPARATOR . "es", 0777);
        if (!file_exists($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "idioma" . DIRECTORY_SEPARATOR . "en"))
            mkdir($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "idioma" . DIRECTORY_SEPARATOR . "en", 0777);
//        if (!file_exists($dir . "/views/idioma/es/" . $nombre)) {
//            mkdir($dir . "/views/idioma/es/" . $nombre);
//            //	$this->createidiomaes($dir.DIRECTORY_SEPARATOR,$nombre);
//        }
//        if (!file_exists($dir . "/views/idioma/en/" . $nombre)) {
//            mkdir($dir . "/views/idioma/en/" . $nombre);
//            //$this->createidiomaen($dir.DIRECTORY_SEPARATOR,$nombre);
//        }
        if (!file_exists($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "scripts"))
            mkdir($dir . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "scripts", 0777);
//        if (!file_exists($dir . "/views/scripts/" . $nombre)) {
//            mkdir($dir . "/views/scripts/" . $nombre, 0777);
//            //$this->crearphtml($dir.DIRECTORY_SEPARATOR,$nombre);
//        }
        if (!file_exists($dir . DIRECTORY_SEPARATOR . "comun"))
            mkdir($dir . DIRECTORY_SEPARATOR . "comun", 0777);
        if (!file_exists($dir . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos"))
            mkdir($dir . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos", 0777);
        if (!file_exists($dir . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "json"))
            mkdir($dir . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "json", 0777);
        if (!file_exists($dir . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml")) {
            mkdir($dir . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml", 0777);
        }
        $this->CreaXmlComponents($idnodopadre, $abrevPath, $nombre, $dir . DIRECTORY_SEPARATOR . "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml", $componente);
    }

    function creacontroller($dir2, $nombre) {
        $class = new Zend_CodeGenerator_Php_Class();
        $class->setName(ucfirst($nombre) . 'Controller');
        $class->setExtendedClass('ZendExt_Controller_Secure');
        $docblock = new Zend_CodeGenerator_Php_Docblock(array(
            'shortDescription' => 'Componente para gestinar los sistemas.',
            'tags' => array(
                array(
                    'name' => 'package',
                    'description' => 'SAUXE_v2.3'
                ),
                array(
                    'name' => 'Copyright',
                    'description' => 'UCI'
                ),
                array(
                    'name' => 'Author',
                    'description' => 'Esta clase fue generada automáticamente'
                ),
                array(
                    'name' => 'Version',
                    'description' => '3.0-0'
                )
            )
        ));
        $peso = "$";
        $class->setDocblock($docblock);
        $constructor = new Zend_CodeGenerator_Php_Method();
        $constructor->setName('init');
        $constructor->setBody("parent::init();");
        $class->setMethod($constructor);
        $metodo = new Zend_CodeGenerator_Php_Method();
        $metodo->setName($nombre . "Action");
        $metodo->setBody($peso . "this->render();");
        $class->setMethod($metodo);
        $file = new Zend_CodeGenerator_Php_File();
        $file->setBody($class->generate() . "\n?>");
        $nombreclase = ucfirst($nombre) . "Controller.php";
        $url = $dir2 . "controllers/" . $nombreclase;
        file_put_contents($url, $file->generate());
    }

    function createidiomaes($dir2, $nombre) {
        $fp = fopen($dir2 . "views" . DIRECTORY_SEPARATOR . "idioma" . DIRECTORY_SEPARATOR . "es" . DIRECTORY_SEPARATOR . $nombre . DIRECTORY_SEPARATOR . $nombre . ".json", 'w');
        $codigo = "
{lbBtnAdicionar:'Adicionar',
lbBtnModificar:'Modificar',
lbBtnAyuda:'Ayuda',
lbBtnCancelar:'Cancelar',
lbBtnAplicar:'Aplicar',
lbBtnAceptar:'Aceptar',
lbTitDiasCaducidad:'D&iacute;as de caducidad',
lbTitFieldSet:'Tipos de caracteres:',
lbTitMinCaracteres:'M&iacute;nimo de caracteres',
lbTitAlfabetico:'Alfab&eacute;tico',
lbTitSignos:'Signos',
lbTitNumerico:'Num&eacute;rico',
lbTitVentanaTitI:'Adicionar clave',
lbTitVentanaTitII:'Modificar clave',
lbTitPanelTit:'Gestionar nomenclador de claves',
lbBLNumerico:'Num&eacute;rico',
lbBLAlfabetico:'Alfab&eacute;tico',
lbBLSignos:'Signos',
lbFLMinCaracteres:'M&iacute;nimo de caracteres',
lbFLDiasCaducidad:'D&iacute;as de caducidad',
lbMsgbbarI:'Resultados {0} - {1} de {2}',
lbMsgbbarII:'Ning&uacute;n resultado para mostrar.',
lbMsgBlank:'Este campo es obligatorio.',
lbMsgregexI:'Solo puede entrar valores enteros num&eacute;ricos.',
lbMsgFunAdicionarMsg:'Registrando clave...',
lbMsgErrorEnCamops:'Existen campos obligatiorios en blanco.',
lbMsgFunModificarMsg:'Modificando clave...',
lbMsgFunValidaMsg:'Debe seleccionar al menos un tipo de caracter.'}";
        fwrite($fp, $codigo);
        fclose($fp);
    }

    function createidiomaen($dir2, $nombre) {
        $fp = fopen($dir2 . "views" . DIRECTORY_SEPARATOR . "idioma" . DIRECTORY_SEPARATOR . "en" . $nombre . DIRECTORY_SEPARATOR . $nombre . ".json", 'w');
        $codigo = "
lbBtnAdicionar:'Add',
lbBtnModificar:'Modify',
lbBtnCancelar:'Cancel',
lbBtnAyuda:'Help',
lbBtnAplicar:'Apply',
lbBtnEliminar:'Delete',
lbBtnAceptar:'Accept',
lbTitDiasCaducidad:'Days of caducity',
lbTitFieldSet:'Character's Types',
lbTitMinCaracteres:'Minimum of character's',
lbTitAlfabetico:'Alphabetic',
lbTitSignos:'Signs',
lbTitNumerico:'Numeric',
lbTitVentanaTitI:'Add key',
lbTitVentanaTitII:'Modifying key',
lbTitPanelTit:'Management nomenclador of keys',
lbBLNumerico:'Numeric',
lbBLAlfabetico:'Alphabetic',
lbBLSignos:'Signs',
lbFLMinCaracteres:'Minimum of character's',
lbFLDiasCaducidad:'Days of caducity',
lbMsgbbarI:'Results {0} - {1} de {2}',
lbMsgbbarII:'No result to show',
lbMsgBlank:'This field is required.',
lbMsgregexI:'Only  can enter integer numerical  values',
lbMsgFunAdicionarMsg:'Registering key...',
lbMsgFunModificarMsg:'Modifying key...',
lbMsgFunValidaMsg:'You should select at least a kind of character.'}";
        fwrite($fp, $codigo);
        fclose($fp);
    }

    function crearphtml($dir2, $nombre) {

        // $fp1 = fopen($dir2."views/scripts/".$nombre."/".$nombre.".phtml", 'w');
        $comilla = '"';
        $peso = "$";
        $codigo =
                "<html>
<head>
<meta http-equiv=" . $comilla . "Content-Type" . $comilla . " content=" . $comilla . "text/html; charset=utf-8" . $comilla . " />
<title>" . ucfirst($nombre) . "</title>
<script type=" . $comilla . "text/javascript" . $comilla . " src=" . $comilla . "<?php echo " . $peso . "this->dir_ucid;?>js/imporcss.js" . $comilla . "></script>
<script language=" . $comilla . "javascript" . $comilla . " type=" . $comilla . "text/javascript" . $comilla . ">importarCSS(window.parent.UCID.portal.perfil.dirCss);</script>
</head>

<body>
<div id=" . $comilla . "panel" . $comilla . "></div>
<script type=" . $comilla . "text/javascript" . $comilla . " src=" . $comilla . "<?php echo " . $peso . "this->dir_extjs;?>js" . DIRECTORY_SEPARATOR . "ext-base.js" . $comilla . "></script>
<script type=" . $comilla . "text/javascript" . $comilla . " src=" . $comilla . "<?php echo " . $peso . "this->dir_extjs;?>js" . DIRECTORY_SEPARATOR . "ext-all.js" . $comilla . "></script>
<script type=" . $comilla . "text/javascript" . $comilla . " src=" . $comilla . "<?php echo " . $peso . "this->dir_ucid;?>js" . DIRECTORY_SEPARATOR . "ucid-all.js" . $comilla . "></script>
<script type=" . $comilla . "text/javascript" . $comilla . " src=" . $comilla . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "js" . DIRECTORY_SEPARATOR . $nombre . DIRECTORY_SEPARATOR . $nombre . ".js" . $comilla . "></script>
</body>
</html>
";
        //fwrite($fp1,$codigo);
        // fclose($fp1);
        file_put_contents($dir2 . "views" . DIRECTORY_SEPARATOR . "scripts" . DIRECTORY_SEPARATOR . $nombre . DIRECTORY_SEPARATOR . $nombre . ".phtml", $codigo);
    }

    private function CreaXmlComponents($idnodopadre, $abrevPath, $nombre, $dir_creacion, $componente) {
        $doc = new DomDocument("1.0");
        $doc->formatOutput = true;
        $root = $doc->createElement("package_component");
        $doc->appendChild($root);
        $hijo = $doc->createElement('cmp' . $idnodopadre);
        $hijo->setAttribute("id", $idnodopadre/* rand(0,1000000000000000) */);
        $hijo->setAttribute("text", $nombre);
        if ($componente) {
            $hijo->setAttribute("leaf", true);
            $hijo->setAttribute("component", true);
        } else {
            $hijo->setAttribute("leaf", false);
            $hijo->setAttribute("component", false);
        }
        $hijo->setAttribute("path", $abrevPath);
        $root->appendChild($hijo);
        $xml = $doc->saveXML();
        file_put_contents($dir_creacion . DIRECTORY_SEPARATOR . 'components.xml', $xml);
        //file_put_contents($dir_creacion . DIRECTORY_SEPARATOR . 'requisitos.xml', $xml);
        file_put_contents($dir_creacion . DIRECTORY_SEPARATOR . 'funcionalidades.xml', $xml);
    }

    public function parseDir($nombre) {
        if (substr_count($nombre, '.php') > 0 || substr_count($nombre, '.xml') > 0 || substr_count($nombre, '.phtml') > 0 || substr_count($nombre, '.html') > 0)
            return 1;
        else
            return 0;
    }

    function parserString($palabra) {
        $path = $palabra;
        $path_devolver = "";
        if ($path[0] == DIRECTORY_SEPARATOR) {
            $path = "";
            for ($i = 1; $i < $strlen($palabra); $i++) {
                $path = $path . $palabra[$i];
            }
        }
        if ($path[strlen($path) - 1] == DIRECTORY_SEPARATOR) {
            for ($i = 1; $i < strlen($path); $i++) {
                $path_devolver = $path_devolver . $path[$i];
            }
        }
        else
            $path_devolver = $path;

        return $path_devolver;
    }

    public function directory_map($source_dir, $top_level_only = FALSE) {
        if ($fp = @opendir($source_dir)) {
            $source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $filedata = array();
            while (FALSE !== ($file = readdir($fp))) {
                if (strncmp($file, '.', 1) == 0) {
                    continue;
                }
                if ($top_level_only == FALSE && @is_dir($source_dir . $file)) {
                    $temp_array = array();
                    $temp_array = directory_map($source_dir . $file . DIRECTORY_SEPARATOR);
                    $filedata[$file] = $temp_array;
                } else {
                    $filedata[] = $file;
                }
            }
            closedir($fp);
            return $filedata;
        }
    }

}

?>
