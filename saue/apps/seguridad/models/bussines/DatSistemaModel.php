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

class DatSistemaModel extends ZendExt_Model {

    public function DatSistemaModel() {
        parent::ZendExt_Model();
    }

    public function insertarsistema($arrayObjServidores, $sistema) {
        $sistema->save();
        if ($arrayObjServidores) {
            foreach ($arrayObjServidores as $servidor) {
                $servidor->save();
            }
        }
    }

    public function modificarsistema($sistema) {
        $sistema->save();
    }

    public function Sistema($idsistema) {
        $arreglo = array();
        $arreglo = DatSistemaDatServidores::Sistema($idsistema);
        return $arreglo;
    }

    public function ValorMaximo() {
        $valor = DatSistema::ValorMaximo();
        return $valor;
    }

    public function getSistema($idsistema, $sistema) {
        $sistema = new DatSistema();
        $sistema = DatSistema::getsistema($idsistema);
        return $sistema;
    }

    public function Hoja($sistema) {
        if ($sistema->rgt - $sistema->lft == 1)
            return true;
        return false;
    }

    public function SistemaVacio($idsistema) {
        $array = DatSistemaDatServidores::EstaRelacionado($idsistema);
        if (count($array) > 0)
            return "NO";
        return "SI";
    }

    public function cargarsistema($idnodo) {
        $array = DatSistema::cargarsistema($idnodo);
        return $array;
    }

    public function verificarRolModulesConfig($rol, $DOM_XML_Modules) {
        $sistemasPadres = DatSistema::obtenerSistemasP();
        foreach ($sistemasPadres as $SistemaPadre) {
            $idsistema = $SistemaPadre['idsistema'];
            $NodeToModify = $this->getElementByID($DOM_XML_Modules, $idsistema . "conn");
            if ($NodeToModify != null) {
                $usuario = $NodeToModify->getAttribute('usuario');
                if ($usuario == $rol) {
                    return true;
                }
            }
        }
        return false;
    }

    public function DeterminarDatosDeSistemaFromModulesConfig($idsistema) {
        if ($idsistema != 0) {
            $sistema = DatSistema::ObtenerSistemaXid($idsistema);
            $demoninacionSistema = $sistema->denominacion;
        } else {
            $demoninacionSistema = "config";
        }

        $registry = Zend_Registry::getInstance();
        $dirmodulesconfig = $registry->config->xml->modulesconfig;
        $DOM_XML_Modules = new DOMDocument();
        $contentfile = file_get_contents($dirmodulesconfig);
        $DOM_XML_Modules->loadXML($contentfile);
        $sistema = $this->getElementByID($DOM_XML_Modules, $idsistema . "conn");

        if ($sistema != false) {

            $usuarioXML = $sistema->getAttribute('usuario');
            $passwordXML = $sistema->getAttribute('password');
            $hostXML = $sistema->getAttribute('host');
            $gestorXML = $sistema->getAttribute('gestor');
            $puertoXML = $sistema->getAttribute('port');
            $bdXML = $sistema->getAttribute('bd');
            $sistemaArray = array('namesistema' => $demoninacionSistema,
                'usuario' => $usuarioXML,
                'pass' => $passwordXML,
                'host' => $hostXML,
                'gestor' => $gestorXML,
                'puerto' => $puertoXML,
                'bd' => $bdXML
            );

            return $sistemaArray;
        }

        return null;
    }

    public function getElementByID($DOM, $id) {
        $xpath = new DOMXpath($DOM);
        $elements = $xpath->query("//*[@id='$id']");
        if ($elements->length > 0) {
            return $elements->item(0);
        }
        return false;
    }

    public function getConexionesSistemas($DOM) {
        $xpath = new DOMXpath($DOM);
        $elements = $xpath->query("//*[@id]");
        if ($elements->length > 3) {
            return true;
        }
        return false;
    }

    public function DevolverEsquemas($pgsql) {
        $esquemasOrdenados = 0;
        $longitud = count($pgsql);       
        if ($longitud == 1) {
            $esquemasOrdenados = $pgsql[0][3];
        } else {
            foreach ($pgsql as $listadoEsquemas) {
                if ($longitud == count($pgsql)) {
                    $esquemasOrdenados = $listadoEsquemas[3] . ",";
                    $longitud--;
                } else {
                    if ($longitud != count($pgsql)){
                        $esquemasOrdenados.= $listadoEsquemas[3] . ",";
                        $longitud--;
                    }
                }
            }
            $esquemasOrdenados[strlen($esquemasOrdenados) - 1] = null;
            return $esquemasOrdenados;
        }
    }

}
?>