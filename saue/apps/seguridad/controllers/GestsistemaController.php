<?php

/*
 * Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garcï¿½a Tejo
 * @author Julio Cesar Garcï¿½a Mosquera
 * @author Yoel HernÃ¡ndez Mendoza
 * @version 3.0-0
 */

class GestsistemaController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestsistemaAction() {
        $this->render();
    }

    /*
     * Función Insertar Sistema
     */

    function insertarsistemaAction() {
        $sistema = new DatSistema();
        $sistemamodel = new DatSistemaModel();
        $idpadre = $this->_request->getPost('idpadre');
        $idservidorauth = $this->_request->getPost('idservidor');
        $sistema->denominacion = $this->_request->getPost('denominacion');
        $sistema->abreviatura = $this->_request->getPost('abreviatura');
        $sistema->descripcion = $this->_request->getPost('descripcion');
        $sistema->icono = $this->_request->getPost('icono');
        $sistema->iddominio = $this->global->Perfil->iddominio;
        $servidorweb = $this->_request->getPost('servidorweb');
        $aux = $sistemamodel->ValorMaximo();
        $valor = $aux[0]["MAX"];
        $pgsql = json_decode(stripcslashes($this->_request->getPost('pgsql')));
        if ($this->_request->getPost('idservidor') != '') {
            $sistema->idservidor = $this->_request->getPost('idservidor');
        } else {
            $sistema->idservidor = null;
        }
        $arraySistemashijos = array();
        $arraySistemashijos = DatSistema::obtenerSistemasV($idpadre);
        $denom = true;
        $abrev = true;
        if ($idpadre != 0) {
            foreach ($arraySistemashijos as $sistemas) {
                if ($sistemas['denominacion'] == $sistema->denominacion) {
                    $denom = false;
                    break;
                }
                if ($sistemas['abreviatura'] == $sistema->abreviatura) {
                    $abrev = false;
                    break;
                }
            }
            if (!$denom)
                throw new ZendExt_Exception('SEG046');
            else if (!$abrev)
                throw new ZendExt_Exception('SEG047');

            else {
                if ($idpadre == 0) {
                    if ($servidorweb)
                        $sistema->externa = $servidorweb;
                    $sistema->idpadre = $sistema->idsistema;
                    $sistemamodel->insertarsistema($arrayObjServidores, $sistema);
                }
                else {
                    $sistema->idpadre = $idpadre;
                    $servwebpadre = DatSistema::buscarservidorweb($idpadre);
                    if (count($servwebpadre))
                        $sistema->externa = $servwebpadre[0]->externa;
                    $sistemaaux = $sistemamodel->getSistema($idpadre, $sistemaaux);
                    if ($sistemamodel->Hoja($sistemaaux)) {
                        $sistemaaux->rgt = $sistemaaux->lft;
                        $sistemamodel->insertarsistema($arrayObjServidores, $sistemaaux);
                    }
                    $sistema->lft = $valor + 1;
                    $sistema->rgt = $sistema->lft + 1;
                    $sistemamodel->insertarsistema($arrayObjServidores, $sistema);
                }
                $objSistemaComp = new DatSistemaCompartimentacion();
                $objSistemaComp->idsistema = $sistema->idsistema;
                $objSistemaComp->iddominio = $this->global->Perfil->iddominio;
                $objSistemaComp->save();
                $arrayObjServidores = array();
                $pgsql = json_decode(stripcslashes($this->_request->getPost('pgsql')));
                $asignarconexionespadres = true;
                if (count($pgsql) > 0) {
                    foreach ($pgsql as $value) {
                        $asignarconexionespadres = false;
                        $idbd = $this->insertaNomBaseDato($value[2]);
                        $idesquema = $this->insertaNomEsquema($value[3]);
                        $servidorsistema = new DatSistemaDatServidores();
                        $servidorsistema->idservidor = $value[4];
                        $servidorsistema->idgestor = $value[1];
                        $servidorsistema->idbd = $idbd;
                        $servidorsistema->idesquema = $idesquema;
                        $servidorsistema->idsistema = $sistema->idsistema;
                        $servidorsistema->idrolesbd = $value[0];
                        $arrayObjServidores[] = $servidorsistema;
                    }
                }
                $oracle = json_decode(stripcslashes($this->_request->getPost('oracle')));
                if (count($oracle) > 0) {
                    foreach ($oracle as $value) {
                        $asignarconexionespadres = false;
                        $idbd = $this->insertaNomBaseDato($value[2]);
                        $idesquema = $this->insertaNomEsquema($value[3]);
                        $servidorsistema = new DatSistemaDatServidores();
                        $servidorsistema->idservidor = $value[4];
                        $servidorsistema->idgestor = $value[1];
                        $servidorsistema->idbd = $idbd;
                        $servidorsistema->idesquema = $idesquema;
                        $servidorsistema->idsistema = $sistema->idsistema;
                        $servidorsistema->idrolesbd = $value[0];
                        $arrayObjServidores[] = $servidorsistema;
                    }
                }
                $conexionesPadre = DatSistemaDatServidores::conexionesdelSistema($idpadre);
                if ($asignarconexionespadres == true)
                    foreach ($conexionesPadre as $value) {
                        $idbd = $this->insertaNomBaseDato($value[2]);
                        $idesquema = $this->insertaNomEsquema($value[3]);
                        $servidorsistema = new DatSistemaDatServidores();
                        $servidorsistema->idservidor = $value['idservidor'];
                        $servidorsistema->idgestor = $value['idgestor'];
                        $servidorsistema->idbd = $value['idbd'];
                        $servidorsistema->idesquema = $value['idesquema'];
                        $servidorsistema->idsistema = $sistema->idsistema;
                        $servidorsistema->idrolesbd = $value['idrolesbd'];
                        $arrayObjServidores[] = $servidorsistema;
                    }
                $sistemamodel->insertarsistema($arrayObjServidores, $sistema);
                if ($this->_request->getPost('createXML') == 'true') {
                    if ($idpadre == 0) {
                        $this->ModificarSistemaXML_ModulesConfig($sistema->denominacion, $sistema->denominacion, $sistema, $pgsql);
                    } else {
                        $this->InsertarSistemaXML_ModulesConfig($sistema, $pgsql);
                    }
                }
                echo"{'codMsg':1,'mensaje':perfil.etiquetas.MsgInfAdicionar}";
            }
        } else {
            $arraySistemasP = DatSistema::obtenerSistemasP();
            $denom = true;
            $abrev = true;
            foreach ($arraySistemasP as $sistemas) {
                if ($sistemas['denominacion'] == $sistema->denominacion) {
                    $denom = false;
                    break;
                }
                if ($sistemas['abreviatura'] == $sistema->abreviatura) {
                    $abrev = false;
                    break;
                }
            }
            if (!$denom)
                throw new ZendExt_Exception('SEG046');
            else if (!$abrev)
                throw new ZendExt_Exception('SEG047');
            else {
                if ($idpadre == 0) {
                    if ($servidorweb) {
                        $sistema->externa = $servidorweb;
                    }
                    $sistema->idpadre = 0;
                    $sistemamodel->insertarsistema($arrayObjServidores, $sistema);
                    $sistema->idpadre = $sistema->idsistema;
                     $sistema->lft = $valor + 1;
                    $sistema->rgt = $sistema->lft + 2;
                    $sistemamodel->insertarsistema($arrayObjServidores, $sistema);
                } else {
                    $sistema->idpadre = $idpadre;
                    $servwebpadre = DatSistema::buscarservidorweb($idpadre);
                    if (count($servwebpadre))
                        $sistema->externa = $servwebpadre[0]->externa;
                    $sistemamodel->insertarsistema($arrayObjServidores, $sistema);
                }
                $objSistemaComp = new DatSistemaCompartimentacion();
                $objSistemaComp->idsistema = $sistema->idsistema;
                $objSistemaComp->iddominio = $this->global->Perfil->iddominio;
                $objSistemaComp->save();
                $arrayObjServidores = array();
                if (count($pgsql) > 0) {
                    foreach ($pgsql as $value) {
                        $idbd = $this->insertaNomBaseDato($value[2]);
                        $idesquema = $this->insertaNomEsquema($value[3]);
                        $servidorsistema = new DatSistemaDatServidores();
                        $servidorsistema->idservidor = $value[4];
                        $servidorsistema->idgestor = $value[1];
                        $servidorsistema->idbd = $idbd;
                        $servidorsistema->idesquema = $idesquema;
                        $servidorsistema->idsistema = $sistema->idsistema;
                        $servidorsistema->idrolesbd = $value[0];
                        $arrayObjServidores[] = $servidorsistema;
                    }
                }
                $oracle = json_decode(stripcslashes($this->_request->getPost('oracle')));
                if (count($oracle) > 0) {
                    foreach ($oracle as $value) {

                        $idbd = $this->insertaNomBaseDato($value[2]);
                        $idesquema = $this->insertaNomEsquema($value[3]);
                        $servidorsistema = new DatSistemaDatServidores();
                        $servidorsistema->idservidor = $value[4];
                        $servidorsistema->idgestor = $value[1];
                        $servidorsistema->idbd = $idbd;
                        $servidorsistema->idesquema = $idesquema;
                        $servidorsistema->idsistema = $sistema->idsistema;
                        $servidorsistema->idrolesbd = $value[0];
                        $arrayObjServidores[] = $servidorsistema;
                    }
                }
                $sistemamodel->insertarsistema($arrayObjServidores, $sistema);
                if ($this->_request->getPost('createXML') == 'true') {
                    if ($idpadre == 0) {
                        $this->ModificarSistemaXML_ModulesConfig($sistema->denominacion, $sistema->denominacion, $sistema, $pgsql);
                    } else {
                        $this->InsertarSistemaXML_ModulesConfig($sistema, $pgsql);
                    }
                }
                echo"{'codMsg':1,'mensaje':perfil.etiquetas.MsgInfAdicionar}";
            }
        }
    }

    private function InsertarSistemaXML_ModulesConfig($sistema, $pgsql) {
        $model = new DatSistemaModel();
        $nombreRole = $this->_request->getPost('nombrerole');
        $passRoles = $this->_request->getPost('rolepassword');
        $ip = $this->_request->getPost('ip');
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $bd = $this->_request->getPost('bd');
        $RSA = new ZendExt_RSA_Facade();
        $passRoles = $RSA->encrypt($passRoles);
        $sistemaDenominacion = $sistema->denominacion;
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->modulesconfig;
        $DOM_XML_Modules = new DOMDocument();
        $contentfile = file_get_contents($dirconfigConection);
        $DOM_XML_Modules->preserveWhiteSpace = FALSE;
        $DOM_XML_Modules->loadXML($contentfile);
        $rolutilizado = $model->verificarRolModulesConfig($nombreRole, $DOM_XML_Modules);
        if ($rolutilizado) {
            throw new ZendExt_Exception('SEG069');
        }
        $sistemaDenominacion = $this->TrabajarNombreSistema($sistemaDenominacion);
        $Subsistema = $DOM_XML_Modules->createElement($sistemaDenominacion);
        $Subsistema->setAttribute('id', $sistema->idsistema);
        $Conexion = $DOM_XML_Modules->createElement("conn");
        $Conexion->setAttribute('id', $sistema->idsistema . "conn");
        $Conexion->setAttribute('host', $ip);
        $Conexion->setAttribute('gestor', $gestor);
        $Conexion->setAttribute('port', $puerto);
        $Conexion->setAttribute('bd', $bd);
        $Conexion->setAttribute('usuario', $nombreRole);
        $Conexion->setAttribute('password', $passRoles);
        $esquemas = $model->DevolverEsquemas($pgsql);        
        $Conexion->setAttribute('esquema', $esquemas);
        $Subsistema->appendChild($Conexion);
        $id = $sistema->idpadre != "" ? $sistema->idpadre : 0;
        $father = $model->getElementByID($DOM_XML_Modules, $id);
        if (!$father)
            throw new ZendExt_Exception('SEG060');
        $father->appendChild($Subsistema);
        $DOM_XML_Modules->formatOutput = TRUE;
        $DOM_XML_Modules->save($dirconfigConection);
    }

    function modificarsistemaAction() {
        $idsistema = $this->_request->getPost('idsistema');
        $servidor = $this->_request->getPost('bd');
        $rol = $this->_request->getPost('nombrerole');
        $pgsql = json_decode(stripcslashes($this->_request->getPost('pgsql')));
        $oracle = json_decode(stripcslashes($this->_request->getPost('oracle')));
        $modelsistema = new DatSistemaModel();
        $idpadre = -1;
        if ($idsistema != 0) {
            $sistema = Doctrine::getTable('DatSistema')->find($idsistema);
            $idpadre = $sistema->idpadre;
            $OLdDenominacion = $sistema->denominacion;
            $sistema->denominacion = $this->_request->getPost('denominacion');
            $NewDenominacion = $sistema->denominacion;
            $sistema->abreviatura = $this->_request->getPost('abreviatura');
            $sistema->descripcion = $this->_request->getPost('descripcion');
            $externa = $this->_request->getPost('servidorweb');
            $sistema->icono = $this->_request->getPost('icono');
        } else {
            $sistema = new DatSistema();
            $sistema->denominacion = $this->_request->getPost('denominacion');
            $sistema->idsistema = $idsistema;
            $OLdDenominacion = $sistema->denominacion;
            $NewDenominacion = $sistema->denominacion;
        }
        $denom = true;
        $abrev = true;
        if ($idpadre != 0) {
            if ($idsistema != 0 && $sistema->idpadre == $sistema->idsistema) {
                $arraySistemasP = DatSistema::obtenerSistemasP();
                $auxden = '';
                $auxabv = '';
                foreach ($arraySistemasP as $aux2) {
                    if ($aux2['idsistema'] == $idsistema) {
                        $auxden = $aux2['denominacion'];
                        $auxabv = $aux2['abreviatura'];
                    }
                }
                $denom = true;
                $abrev = true;
                if ($sistema->denominacion != $auxden) {
                    foreach ($arraySistemasP as $sistemas) {
                        if ($sistemas['denominacion'] == $sistema->denominacion) {
                            $denom = false;
                            break;
                        }
                    }
                }
                if ($sistema->abreviatura != $abrev) {
                    foreach ($arraySistemasP as $sistemas) {
                        if ($sistemas['abreviatura'] == $sistema->abreviatura) {
                            $abrev = false;
                            break;
                        }
                    }
                }
                if (!$denom) {
                    if ($idsistema != 0)
                        throw new ZendExt_Exception('SEG046');
                }else if (!$abrev) {
                    if ($idsistema != 0)
                        throw new ZendExt_Exception('SEG047');
                }
            }
            else
            if ($idsistema != 0 && $sistema->idpadre != $sistema->idsistema) {
                $arraySistemashijos = array();
                $arraySistemashijos = DatSistema::obtenerSistemasV($idpadre);
                $auxden = '';
                $auxabv = '';
                foreach ($arraySistemashijos as $hijos) {
                    if ($hijos['idsistema'] == $idsistema) {
                        $auxden = $aux2['denominacion'];
                        $auxabv = $aux2['abreviatura'];
                    }
                }
                $denom = true;
                $abrev = true;
                if ($sistema->denominacion != $auxden) {
                    foreach ($arraySistemashijos as $hijos) {
                        if ($hijos['denominacion'] == $sistema->denominacion) {
                            $denom = false;
                            break;
                        }
                    }
                }
                if ($sistema->abreviatura != $abrev) {
                    foreach ($arraySistemashijos as $hijos) {
                        if ($hijos['abreviatura'] == $sistema->abreviatura) {
                            $abrev = false;
                            break;
                        }
                    }
                }
                $array = $modelsistema->SistemaVacio($idsistema);
                if (!$denom && $servidor == null && $array == "NO") {
                    if ($idsistema != 0)
                        throw new ZendExt_Exception('SEG046');
                }
                else
                if (!$abrev && $servidor == null && $array == "NO") {
                    if ($idsistema != 0)
                        throw new ZendExt_Exception('SEG047');
                }
            }
            if ($idsistema != 0) {
                if ($externa) {
                    $sistema->externa = $externa;
                    $this->modificarhijos($idsistema, $externa);
                }
                if ($idsistema != 0) {
                    $esquemasEliminados = json_decode(stripcslashes($this->_request->getPost('esquemasEliminados')));
                    $tipo = $this->TipoConexion();
                    if ($tipo == 2 || $tipo == 3)
                        $this->RevocarPermisosSobreEsquemasEliminados($esquemasEliminados);
                    $this->modificarConexionHijos($idsistema, $esquemasEliminados, $pgsql, $oracle);

                    if (count($esquemasEliminados) > 0) {
                        foreach ($esquemasEliminados as $esquemaEl) {
                            $esquemaElArr = explode('_', $esquemaEl);
                            $array = array($esquemaElArr[0], $esquemaElArr[1], $esquemaElArr[2], $esquemaElArr[3], $esquemaElArr[4]);
                            $esquemaELObj = Doctrine::getTable('DatSistemaDatServidores')->findByDql('idsistema = ? and idservidor = ? and idgestor = ? and idbd = ? and idesquema = ?', $array);
                            $esquemaELObj[0]->delete();
                            $idesquema = $esquemaElArr[4];
                            $isdeleted = Doctrine::getTable('DatSistemaDatServidores')->findByDql('idesquema=?', array($idesquema));
                            if (count($isdeleted) == 0) {
                                $ElDatEsq = Doctrine::getTable('DatEsquema')->findByDql('idesquema = ?', array($idesquema));
                                $ElDatEsq[0]->delete();
                            }
                        }
                    }
                    $arrayObjServidores = array();
                    if (count($pgsql) > 0) {
                        foreach ($pgsql as $value) {
                            $idbd = $this->insertaNomBaseDato($value[2]);
                            $idesquema = $this->insertaNomEsquema($value[3]);
                            $result = DatSistemaDatServidores::seleccionarconex($idbd, $idsistema, $idesquema, $value[4], $value[1], $value[0]);
                            if (count($result) == 0) {
                                $servidorsistema = new DatSistemaDatServidores();
                                $servidorsistema->idservidor = $value[4];
                                $servidorsistema->idgestor = $value[1];
                                $servidorsistema->idbd = $idbd;
                                $servidorsistema->idesquema = $idesquema;
                                $servidorsistema->idsistema = $idsistema;
                                $servidorsistema->idrolesbd = $value[0];
                                $arrayObjServidores[] = $servidorsistema;
                            }
                        }
                    }

                    if (count($oracle) > 0) {
                        foreach ($oracle as $value) {

                            $idbd = $this->insertaNomBaseDato($value[2]);
                            $idesquema = $this->insertaNomEsquema($value[3]);
                            $result = DatSistemaDatServidores::seleccionarconex($idbd, $idsistema, $idesquema, $value[4], $value[1], $value[0]);

                            if (count($result) == 0) {
                                $servidorsistema = new DatSistemaDatServidores();
                                $servidorsistema->idservidor = $value[4];
                                $servidorsistema->idgestor = $value[1];
                                $servidorsistema->idbd = $idbd;
                                $servidorsistema->idesquema = $idesquema;
                                $servidorsistema->idsistema = $idsistema;
                                $servidorsistema->idrolesbd = $value[0];
                                $arrayObjServidores[] = $servidorsistema;
                            }
                        }
                    }
                    $modelsistema->modificarsistema($sistema);
                    if (count($arrayObjServidores)) {
                        $modelservidor = new DatSistemaDatServidoresModel();
                        $modelservidor->registrarConexiones($arrayObjServidores);
                    }
                }
                if ($this->_request->getPost('createXML') == 'false' &&
                        $this->_request->getPost('systemRelatedWithRole') == 'false' && $idsistema != 0) {
                    $this->EliminarSistemaXML_ModulesConfig($idsistema, $pgsql);
                } else {
                    if ($this->_request->getPost('systemRelatedWithRole') != 'doNotAction')
                        $this->ModificarSistemaXML_ModulesConfig($OLdDenominacion, $NewDenominacion, $sistema, $pgsql);
                }
                echo"{'codMsg':1,'mensaje':perfil.etiquetas.MsgInfModificar}";
            }
        }
    }

    private function TipoConexion() {
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->configConection;
        $DOM_XML_Conex = new DOMDocument();
        $contentfile = file_get_contents($dirconfigConection);
        $DOM_XML_Conex->loadXML($contentfile);
        $elements = $this->getElementsByAttr($DOM_XML_Conex, "seleccion", "true");
        $element = $elements->item(0);
        return $element->getAttribute('tipo');
    }

    private function getElementsByAttr($DOM, $nameAtrr, $value) {
        $xpath = new DOMXpath($DOM);
        $elements = $xpath->query("//*[@$nameAtrr='$value']");
        if ($elements->length > 0) {
            return $elements;
        }
        return false;
    }

    private function ModificarSistemaXML_ModulesConfig($OLdDenominacion, $NewDenominacion, $sistema, $pgsql) {
        $model = new DatSistemaModel();
        $nombreRole = $this->_request->getPost('nombrerole');
        $passRoles = $this->_request->getPost('rolepassword');
        $ip = $this->_request->getPost('ip');
        $gestor = $this->_request->getPost('gestor');
        $puerto = $this->_request->getPost('puerto');
        $bd = $this->_request->getPost('bd');
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$nombreRole:$passRoles@$ip:$puerto/$bd", 'pg_catalog');
        try {
            $conn->connect();
            $dm->setCurrentConnection($nameCurrentConn);
        } catch (Exception $e) {
            $excepcion = $e->getMessage();
            if (strlen($excepcion) > 0) {
                throw new ZendExt_Exception('SEG013');
            }
            $dm->setCurrentConnection($nameCurrentConn);
        }
        $RSA = new ZendExt_RSA_Facade();
        $passRoles = $RSA->encrypt($passRoles);
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->modulesconfig;
        $DOM_XML_Modules = new DOMDocument();
        $contentfile = file_get_contents($dirconfigConection);
        $DOM_XML_Modules->preserveWhiteSpace = FALSE;
        $DOM_XML_Modules->loadXML($contentfile);
        $existenConexiones = $model->getConexionesSistemas($DOM_XML_Modules);
        $NodeToModify = $model->getElementByID($DOM_XML_Modules, $sistema->idsistema);
        if ($existenConexiones) {
            $rolutilizado = $model->verificarRolModulesConfig($nombreRole, $DOM_XML_Modules);
            if ($NodeToModify) {
                $esquemaXML = $NodeToModify->getAttribute('esquema');
                if ($esquemaXML != null) {
                    if ($esquemaXML == $pgsql[0][3] && $rolutilizado) {
                        throw new ZendExt_Exception('SEG069');
                    }
                } else {
                    if ($rolutilizado && $pgsql == 0) {
                        throw new ZendExt_Exception('SEG069');
                    }
                }
            } else {
                if ($rolutilizado) {
                    throw new ZendExt_Exception('SEG069');
                }
            }
        }
        if ($OLdDenominacion != $NewDenominacion && $NodeToModify) {
            $NewDenominacion = $this->TrabajarNombreSistema($NewDenominacion);
            $this->renameElement($NodeToModify, $NewDenominacion);
        }
        if ($this->_request->getPost('createXML') == 'true') {
            if ($NodeToModify == false) {
                $NewDenominacion = $this->TrabajarNombreSistema($NewDenominacion);
                $Subsistema = $DOM_XML_Modules->createElement($NewDenominacion);
                $Subsistema->setAttribute('id', $sistema->idsistema);
                $Conexion = $DOM_XML_Modules->createElement("conn");
                $Conexion->setAttribute('id', $sistema->idsistema . "conn");
                $Conexion->setAttribute('host', $ip);
                $Conexion->setAttribute('gestor', $gestor);
                $Conexion->setAttribute('port', $puerto);
                $Conexion->setAttribute('bd', $bd);
                $Conexion->setAttribute('usuario', $nombreRole);
                $Conexion->setAttribute('password', $passRoles);
                $esquemas = $model->DevolverEsquemas($pgsql);                
                $Conexion->setAttribute('esquema', $esquemas);
                $Subsistema->appendChild($Conexion);
                $id = $sistema->idpadre == $sistema->idsistema ? 0 : $sistema->idpadre;
                $father = $model->getElementByID($DOM_XML_Modules, $id);
                if (!$father)
                    throw new ZendExt_Exception('SEG060');
                $father->appendChild($Subsistema);
            } else {
                $NodeToModify = $model->getElementByID($DOM_XML_Modules, $sistema->idsistema . "conn");
                $hostXML = $NodeToModify->getAttribute('host');
                $gestorXML = $NodeToModify->getAttribute('gestor');
                $puertoXML = $NodeToModify->getAttribute('port');
                $bdXML = $NodeToModify->getAttribute('bd');
                $usuarioXML = $NodeToModify->getAttribute('usuario');
                $passwordXML = $NodeToModify->getAttribute('password');
                $esquemaXML = $NodeToModify->getAttribute('esquema');
                $modifyRole = false;
                if ($ip != $hostXML) {
                    $NodeToModify->setAttribute('host', $ip);
                    $modifyRole = true;
                }
                if ($gestor != $gestorXML) {
                    $NodeToModify->setAttribute('gestor', $gestor);
                    $modifyRole = true;
                }
                if ($puerto != $puertoXML) {
                    $NodeToModify->setAttribute('puerto', $puerto);
                    $modifyRole = true;
                }
                if ($bd != $bdXML) {
                    $NodeToModify->setAttribute('bd', $bd);
                    $modifyRole = true;
                }
                $esquemas = $model->DevolverEsquemas($pgsql);
                if ($esquemas != $esquemaXML) {
                    $NodeToModify->setAttribute('esquema', $esquemas);
                    $modifyRole = true;
                }
                if ($nombreRole != $usuarioXML || $modifyRole) {
                    $NodeToModify->setAttribute('usuario', $nombreRole);
                    $NodeToModify->setAttribute('password', $passRoles);
                }
            }
        }
        $DOM_XML_Modules->formatOutput = TRUE;
        $DOM_XML_Modules->save($dirconfigConection);
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

    private function TrabajarNombreSistema($name) {
        $arrayWordsNames = array();
        $arrayWordsNames = explode(" ", $name);
        $name = implode("_", $arrayWordsNames);
        return $name;
    }

    private function RevocarPermisosSobreEsquemasEliminados($esquemasEliminados) {
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        foreach ($esquemasEliminados as $esquemaEl) {
            $esquemaElArr = explode('_', $esquemaEl);
            $idesquema = $esquemaElArr[4];
            $resultados = SegRol::ObtenerIdRolesRelacionadoEsquema($idesquema);
            $conexion = $esquemaElArr[5];
            $esquema = DatEsquema::obtenerNomEsquemaXId($idesquema);
            foreach ($resultados as $value) {
                $idrol = $value->idrol;
                $nombreRol = SegRol::obtenerNombreRol($idrol);
                $nombreRol = $nombreRol[0]['denominacion'];
                $nombreRol = "rol_" . $nombreRol . "_acaxia";
                $array = array($esquemaElArr[1], $esquemaElArr[2], $esquemaElArr[3], $esquemaElArr[4]);
                $cantConex = Doctrine::getTable('DatSistemaDatServidores')->findByDql('idservidor = ? and idgestor = ? and idbd = ? and idesquema = ?', $array);
                if (count($cantConex) == 1) {
                    $Pgsql->RevocarPrivilegiosSobreEsquema($conexion, $esquema, $nombreRol);
                    $array = array($esquemaElArr[1], $esquemaElArr[2], $esquemaElArr[3], $esquemaElArr[4]);
                    $esquemaELObj = Doctrine::getTable('DatObjetobd')->findByDql('idservidor = ? and idgestor = ? and idbd = ? and idesquema = ?', $array);
                    foreach ($esquemaELObj as $value) {
                        $value->delete();
                    }
                }
            }
        }
    }

    private function modificarConexionHijos($idPadre, $esquemasEliminados, $pgsql, $oracle) {
        $sistemasHijos = DatSistema::obtenersistemashijos($idPadre);
        foreach ($sistemasHijos as $hijos) {
            if ($this->TieneConexionPadre($idPadre, $hijos->idsistema)) {
                $this->modificarConexionHijos($hijos->idsistema, $esquemasEliminados, $pgsql, $oracle);
                $idsistema = $hijos->idsistema;
                foreach ($esquemasEliminados as $esquemaEl) {
                    $esquemaElArr = explode('_', $esquemaEl);
                    $esquemaELObj = Doctrine::getTable('DatSistemaDatServidores')->findByDql('idsistema = ? and idservidor = ? and idgestor = ? and idbd = ? and idesquema = ?', array($idsistema, $esquemaElArr[1], $esquemaElArr[2], $esquemaElArr[3], $esquemaElArr[4]));
                    $esquemaELObj[0]->delete();
                }
                $arrayObjServidores = array();
                foreach ($pgsql as $value) {

                    $idbd = $this->insertaNomBaseDato($value[2]);
                    $idesquema = $this->insertaNomEsquema($value[3]);
                    $result = DatSistemaDatServidores::seleccionarconex($idbd, $idsistema, $idesquema, $value[4], $value[1], $value[0]);
                    if (count($result) == 0) {
                        $servidorsistema = new DatSistemaDatServidores();
                        $servidorsistema->idservidor = $value[4];
                        $servidorsistema->idgestor = $value[1];
                        $servidorsistema->idbd = $idbd;
                        $servidorsistema->idesquema = $idesquema;
                        $servidorsistema->idsistema = $idsistema;
                        $servidorsistema->idrolesbd = $value[0];
                        $arrayObjServidores[] = $servidorsistema;
                    }
                }
                foreach ($oracle as $value) {
                    $idbd = $this->insertaNomBaseDato($value[2]);
                    $idesquema = $this->insertaNomEsquema($value[3]);
                    $result = DatSistemaDatServidores::seleccionarconex($idbd, $idsistema, $idesquema, $value[4], $value[1], $value[0]);
                    if (count($result) == 0) {
                        $servidorsistema = new DatSistemaDatServidores();
                        $servidorsistema->idservidor = $value[4];
                        $servidorsistema->idgestor = $value[1];
                        $servidorsistema->idbd = $idbd;
                        $servidorsistema->idesquema = $idesquema;
                        $servidorsistema->idsistema = $idsistema;
                        $servidorsistema->idrolesbd = $value[0];
                        $arrayObjServidores[] = $servidorsistema;
                    }
                }
                if (count($arrayObjServidores)) {
                    $modelservidor = new DatSistemaDatServidoresModel();
                    $modelservidor->registrarConexiones($arrayObjServidores);
                }
            }
        }
    }

    private function TieneConexionPadre($idpadre, $idhijo) {
        $conexionesPadre = DatSistemaDatServidores::conexionesdelSistema($idpadre);

        $conexionesHijo = DatSistemaDatServidores::conexionesdelSistema($idhijo);

        if (count($conexionesPadre) == count($conexionesHijo)) {

            while (count($conexionesPadre) > 0) {
                $conexion = array_shift($conexionesPadre);
                $existe = false;
                foreach ($conexionesHijo as $value) {
                    if ($conexion['idbd'] == $value['idbd'] && $conexion['idesquema'] == $value['idesquema'] && $conexion['idservidor'] == $value['idservidor'] && $conexion['idgestor'] == $value['idgestor'] && $conexion['idrolesbd'] == $value['idrolesbd']) {
                        $existe = true;
                        break;
                    }
                }
                if (!$existe) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    function verificarsistema($denominacion, $abreviatura) {
        $datossistema = DatSistema::verificarsistema($denominacion, $abreviatura);
        if ($datossistema)
            return 1;
        else
            return 0;
    }

    function insertaNomBaseDato($db) {
        $idbd = DatBd::buscarnombd($db, 1, 0);
        if (count($idbd))
            return $idbd[0]->idbd;
        else {
            $objBD = new DatBd();
            $objBD->denominacion = $db;
            $objBD->descripcion = $db;
            $objBD->save();
            return $objBD->idbd;
        }
    }

    function insertaNomEsquema($esquema) {
        $idesquema = DatEsquema::buscarnomesquemas($esquema, 1, 0);
        if (count($idesquema))
            return $idesquema[0]->idesquema;
        else {
            $objEsquemas = new DatEsquema();
            $objEsquemas->denominacion = $esquema;
            $objEsquemas->descripcion = $esquema;
            $objEsquemas->save();
            return $objEsquemas->idesquema;
        }
    }

    function eliminarsistemaAction() {
        $idsistema = $this->_request->getPost('idsistema');
        $this->sistemaeliminar($idsistema);
        $this->EliminarSistemaXML_ModulesConfig($idsistema);
        echo"{'codMsg':1,'mensaje': perfil.etiquetas.MsgInfEliminar}";
    }

    private function EliminarSistemaXML_ModulesConfig($idsistema) {
        $model = new DatSistemaModel();
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->modulesconfig;
        $DOM_XML_Modules = new DOMDocument();
        $contentfile = file_get_contents($dirconfigConection);
        $DOM_XML_Modules->preserveWhiteSpace = FALSE;
        $DOM_XML_Modules->loadXML($contentfile);
        $Sistema = $model->getElementByID($DOM_XML_Modules, $idsistema);
        if ($Sistema != false) {
            $parent = $Sistema->parentNode;
            $parent->removeChild($Sistema);
            $DOM_XML_Modules->formatOutput = TRUE;
            $DOM_XML_Modules->save($dirconfigConection);
        }
    }

    function cargarsistemaAction() {
        $sistemas = DatSistema::cargarsistema($this->_request->getPost('node'));
        if (count($sistemas)) {
            $sistemaArr = array();            
            foreach ($sistemas as $valores => $valor) {                
                $sistemaArr[$valores]['id'] = $valor['id'];
                $sistemaArr[$valores]['text'] = $valor['text'];                
                $sistemaArr[$valores]['abreviatura'] = $valor['abreviatura'];
                $sistemaArr[$valores]['descripcion'] = $valor['descripcion'];
                $sistemaArr[$valores]['icono'] = $valor['icono'];
                $sistemaArr[$valores]['leaf'] = $valor['leaf'];
            }
            echo json_encode($sistemaArr);
            return;
        }else {
            $sist = $sistemas->toArray();
            echo json_encode($sist);
            return;
        }
    }

    private function cambiarImagen_Sistema($sistemas) {
        $sistemas_conexiones = DatSistemaDatServidores::cargarSistemas();

        $idSistemasCambiar = array();

        foreach ($sistemas_conexiones as $value) {
            $idSistema = $value['idsistema'];
            $idSistemasCambiar = $this->ObtenerSistemasYo_MasPadres($idSistemasCambiar, $idSistema);
        }

        $sistemaArr = array();
        foreach ($sistemas as $valores => $valor) {
            $sistemaArr[$valores]['id'] = $valor->id;
            $sistemaArr[$valores]['text'] = $valor->text;
            $sistemaArr[$valores]['abreviatura'] = $valor->abreviatura;
            $sistemaArr[$valores]['descripcion'] = $valor->descripcion;
            $sistemaArr[$valores]['idpadre'] = $valor->idpadre;
            $sistemaArr[$valores]['servidorweb'] = $valor->externa;
            $sistemaArr[$valores]['leaf'] = (($valor->idpadre != $valor->id && $valor->Hijos->count() > 0) || ($valor->idpadre == $valor->id && $valor->Hijos->count() > 1)) ? false : true;
            if (in_array($valor->id, $idSistemasCambiar)) {
                $sistemaArr[$valores]['icon'] = '../../views/images/ok.png';
            } else {
                $sistemaArr[$valores]['icono'] = $valor->icono;
            }
        }
        return $sistemaArr;
    }

    private function ObtenerSistemasYo_MasPadres($arreglo, $sistema) {
        $existe = in_array($sistema, $arreglo);

        if (!$existe) {
            $arreglo[] = $sistema;

            $Arrpadre = DatSistema::ObtenerPadre($sistema);

            $padre = $Arrpadre[0]['idpadre'];

            if ($padre != $sistema) {

                $array = $this->ObtenerSistemasYo_MasPadres($arreglo, $padre);
                $arreglo = array();
                $arreglo = $array;
            }
        }
        return $arreglo;
    }

    function cargarsistemashojasAction() {
        $sistemas = DatSistema::cargarsistema($this->_request->getPost('node'));
        if ($sistemas->count()) {
            foreach ($sistemas as $valores => $valor) {
                $sistemaArr[$valores]['id'] = $valor->id;
                $sistemaArr[$valores]['text'] = $valor->text;
                $sistemaArr[$valores]['abreviatura'] = $valor->abreviatura;
                $sistemaArr[$valores]['descripcion'] = $valor->descripcion;
                $sistemaArr[$valores]['idpadre'] = $valor->idpadre;
                $sistemaArr[$valores]['icono'] = $valor->icono;
                $sistemaArr[$valores]['servidorweb'] = $valor->externa;
                $sistemaArr[$valores]['leaf'] = true;
            }
            echo json_encode($sistemaArr);
            return;
        } else {
            $sist = $sistemas->toArray();
            echo json_encode($sist);
            return;
        }
    }

    function comprobar($idesquema, $idsistema, $idservidor, $idgestor, $idbd) {
        $esquema = DatSistemaDatServidores::obteneresquemasmarcados($idsistema, $idservidor, $idgestor, $idbd);
        $esquemas = $esquema->toArray(true);
        foreach ($esquemas as $valor) {
            if ($valor['idesquema'] == $idesquema)
                return true;
        }
        return false;
    }

    function cargarservidoresAction() {
        $idnodo = $this->_request->getPost('node');
        $accion = $this->_request->getPost('accion');
        $idsistema = $this->_request->getPost('idsistema');
        $acc = $this->_request->getPost('acc');
        if (!$idnodo) {
            $servidores = DatServidor::cargarservidores(0, 0);
            $servidoresConectados = DatSistemaDatServidores::servidoresMarcados($idsistema);

            if ($servidores->getData() != NULL) {
                foreach ($servidores as $valores => $valor) {
                    $servidoresArr[$valores]['id'] = $valor->id;
                    $servidoresArr[$valores]['idservidor'] = $valor->id;
                    $servidoresArr[$valores]['text'] = $valor->text;
                    $servidoresArr[$valores]['type'] = 'servidores';
                    if ($this->TieneConexionServidores($servidoresConectados, $valor->id) && $acc != "add") {
                        $servidoresArr[$valores]['icon'] = '../../views/images/serverconectado.jpg';
                    } else
                        $servidoresArr[$valores]['icon'] = '../../views/images/server-white.PNG';
                }
                echo json_encode($servidoresArr);
                return;
            }
            else {
                $serv = $servidores->toArray();
                echo json_encode($serv);
                return;
            }
        } elseif ($accion == 'cargargestores') {
            $idservidor = $this->_request->getPost('idservidor');
            $gestores = DatGestor::cargargestores($idservidor, 0, 0);
            if ($gestores->getData() != NULL) {
                $gestConn = DatSistemaDatServidores::gestoresMarcados($idsistema, $idservidor);
                foreach ($gestores as $valores => $valor) {
                    $gestoresArr[$valores]['id'] = $valor->idgestor . '-' . $idnodo;
                    $gestoresArr[$valores]['text'] = $valor->gestor . ":" . $valor->puerto;
                    $gestoresArr[$valores]['idgestor'] = $valor->idgestor;
                    $gestoresArr[$valores]['idservidor'] = $idservidor;
                    $gestoresArr[$valores]['gestor'] = $valor->gestor;
                    if ($valor->gestor == 'pgsql') {
                        if ($this->TieneConexionGestores($gestConn, $valor->idgestor) && $acc != "add")
                            $gestoresArr[$valores]['icon'] = '../../views/images/pgadmin conectado.jpg';
                        else
                            $gestoresArr[$valores]['icon'] = '../../views/images/pgadmin.png';
                    }else {
                        if ($this->TieneConexionGestores($gestConn, $valor->idgestor))
                            $gestoresArr[$valores]['icon'] = '../../views/images/ok.png';
                        else
                            $gestoresArr[$valores]['icon'] = '../../views/images/oracle.png';
                    }
                    $gestoresArr[$valores]['puerto'] = $valor->puerto;
                    $gestoresArr[$valores]['type'] = 'gestor';
                    $gestoresArr[$valores]['ipgestorbd'] = $valor->DatGestorDatServidorbd[0]->DatServidorbd->DatServidor->ip;
                }
                echo json_encode($gestoresArr);
                return;
            }
            else {
                $gest = $gestores->toArray();
                echo json_encode($gest);
                return;
            }
        } elseif ($accion == 'cargarbd') {           
            $RSA = new ZendExt_RSA_Facade();
            $gestor = $this->_request->getPost('gestor');
            $ipgestorbd = $this->_request->getPost('ipgestorbd');
            $idservidor = $this->_request->getPost('idservidor');
            $idgestor = $this->_request->getPost('idgestor');
            $puerto = $this->_request->getPost('puerto');
            $rolbd = SegRolesbd::loadRoleBD($idservidor, $idgestor);            
            $user = $rolbd[0]->nombrerol;
            $passw = $rolbd[0]->passw;
            $pass = $RSA->decrypt($passw);
            $arrayBD = array();
            $getDatabases = 'get' . ucfirst($gestor) . 'Databases';            
            $arrayBD = $this->$getDatabases($gestor, $user, $pass, $ipgestorbd, $idgestor, $idservidor, $idsistema, $idnodo, $puerto, $acc);            
            $this->verificarBasesDatos($arrayBD, $idservidor, $idgestor);             
            echo json_encode($arrayBD);
        } elseif ($accion == 'cargaresquemas') {
            $RSA = new ZendExt_RSA_Facade();
            $user = $this->_request->getPost('user');
            $pass = $RSA->decrypt($this->_request->getPost('passw'));
            $gestor = $this->_request->getPost('gestor');
            $ipgestorbd = $this->_request->getPost('ipgestorbd');
            $namebd = $this->_request->getPost('namebd');
            $idsistema = $this->_request->getPost('idsistema');
            $idservidor = $this->_request->getPost('idservidor');
            $idgestor = $this->_request->getPost('idgestor');
            $puerto = $this->_request->getPost('puerto');
            $schemasArr = array();
            $getSchemas = 'get' . ucfirst($gestor) . 'Schemas';
            $schemasArr = $this->$getSchemas($gestor, $user, $pass, $ipgestorbd, $namebd, $idsistema, $idnodo, $puerto, $idservidor, $idgestor);
            $this->verificarEsquemas($schemasArr, $idservidor, $idgestor, $namebd);
            echo json_encode($schemasArr);
        }
    }

    private function TieneConexionServidores($arrserv, $idser) {
        foreach ($arrserv as $serv) {
            if ($serv['idservidor'] == $idser) {
                return true;
            }
        }
        return false;
    }

    private function TieneConexionGestores($arrgest, $idgest) {
        foreach ($arrgest as $gest) {
            if ($gest['idgestor'] == $idgest) {
                return true;
            }
        }
        return false;
    }

    private function TieneConexionBD($bdconectadas, $idservidor, $idgestor, $bdname) {

        foreach ($bdconectadas as $bd) {
            if ($bd['idservidor'] == $idservidor && $bd['idgestor'] == $idgestor) {
                $nombd = DatBd::getNomBdPorId($bd['idbd']);
                if ($nombd[0]['denominacion'] == $bdname) {
                    return true;
                }
            }
        }
        return false;
    }

    private function getPgsqlDatabases($gestor, $user, $pass, $ipgestorbd, $idgestor, $idservidor, $idsistema, $idnodo, $puerto, $acc) {
        
        $model = new DatSistemaModel();
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $bdArr = array();
        $rsa = new ZendExt_RSA_Facade();
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        if (!$this->VerifyConnection($ipgestorbd)) {
            throw new ZendExt_Exception('SEG060');
        }
        $resp = $Pgsql->PgsqlVerificarDisponibilidadServidor($gestor, $user, $pass, $ipgestorbd, "template1", $puerto);
        if ($resp != 1) {
            throw new ZendExt_Exception('SEG060');
        }
        $key = 0;
        if ($acc == "add" || $idsistema != 0) {
            $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/template1", 'pg_catalog');
            $db = PgDatabase::getPgsqlDatabases($conn);
            $dm->setCurrentConnection($nameCurrentConn);
            $bdconectadas = DatSistemaDatServidores::bdMarcadas($idsistema, $idservidor);
            $datosSistemaConexion = $model->DeterminarDatosDeSistemaFromModulesConfig($idsistema);
            if ($db->getData() != null) {
                foreach ($db as $valor) {
                    $bdArr[$key]['id'] = $valor->datname . '-' . $idnodo;
                    $bdArr[$key]['text'] = $valor->datname;
                    $bdArr[$key]['namebd'] = $valor->datname;
                    $bdArr[$key]['gestor'] = $gestor;
                    $bdArr[$key]['ipgestorbd'] = $ipgestorbd;
                    $bdArr[$key]['puerto'] = $puerto;
                    $bdArr[$key]['user'] = $user;
                    $bdArr[$key]['passw'] = $rsa->encrypt($pass);
                    $bdArr[$key]['idgestor'] = $idgestor;
                    $bdArr[$key]['checked'] = false;
                    $bdArr[$key]['idservidor'] = $idservidor;
                    $bdArr[$key]['type'] = 'bd';
                    $bdArr[$key]['expandable'] = true;
                    if ($datosSistemaConexion != null && $datosSistemaConexion['gestor'] == $gestor &&
                            $datosSistemaConexion['host'] == $ipgestorbd && $datosSistemaConexion['puerto'] == $puerto &&
                            $datosSistemaConexion['bd'] == $valor->datname) {
                        $bdArr[$key]['checked'] = true;
                    }
                    if ($this->TieneConexionBD($bdconectadas, $idservidor, $idgestor, $valor->datname) && $acc != "add")
                        $bdArr[$key]['icon'] = '../../views/images/bdconectada.jpg';
                    else
                        $bdArr[$key]['icon'] = '../../views/images/server.PNG';
                    $key++;
                }
            }            
            $nameCurrentConn = $dm->getCurrentConnection()->getName();            
        }

        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd/template1", 'pg_catalog');
        $rolesbd = PgAuthid::getRolBD($conn, 0, 0);
        $dm->setCurrentConnection($nameCurrentConn);
        if (count($rolesbd)) {
            foreach ($rolesbd as $roles) {
                $bdArr[$key]['id'] = $roles->oid . '-' . $idnodo;
                $bdArr[$key]['idrolbd'] = $roles->oid;
                $bdArr[$key]['text'] = $roles->rolname;
                $bdArr[$key]['leaf'] = true;
                $bdArr[$key]['rol'] = true;
                $bdArr[$key]['gestor'] = $gestor;
                $bdArr[$key]['ipgestorbd'] = $ipgestorbd;
                $bdArr[$key]['puerto'] = $puerto;
                $bdArr[$key]['type'] = 'roles';
                $bdArr[$key]['icon'] = '../../views/images/usuario.png';
                $bdArr[$key]['checked'] = false;
                $bdArr[$key]['pass'] = "";
                $bdArr[$key]['isconex'] = false;
                if ($datosSistemaConexion != null && $datosSistemaConexion['usuario'] == $roles->rolname &&
                        $datosSistemaConexion['gestor'] == $gestor && $datosSistemaConexion['host'] == $ipgestorbd &&
                        $datosSistemaConexion['puerto'] == $puerto) {
                    $bdArr[$key]['checked'] = true;
                    $bdArr[$key]['pass'] = $rsa->decrypt($datosSistemaConexion['pass']);
                }
                if ($roles->rolname == $user) {
                    $bdArr[$key]['isconex'] = true;
                    $servidor_gestor = explode('-', $idnodo);
                    $rolConex = SegRolesbd::exist($user, $servidor_gestor[0], $servidor_gestor[1]);
                    $bdArr[$key]['idrolbd'] = $rolConex[0]->idrolesbd;
                }
                $key++;
            }
        }        
        return $bdArr;
    }

    private function VerifyConnection($ip) {        
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

    private function verifyRoles($array, $gestor, $user, $pass, $ipgestorbd) {
        $arrayResult = array();
        $arrayDelete = array();
        $arrayReturn = array();
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd/template1", 'pg_catalog');
        $arrayRolesGestor = PgAuthid::getRoles($conn);
        $dm->setCurrentConnection($nameCurrentConn);
        $arrayRolesGestor = $this->convetToUnidimensional($arrayRolesGestor);
        $arrayRoles = $this->convetToUnidimensional($array);
        $arrayDelete = array_diff($arrayRoles, $arrayRolesGestor);
        $arrayReturn = array_intersect($arrayRoles, $arrayRolesGestor);
        if (count($arrayDelete))
            SegRolesbd::deleteRoles($arrayDelete);
        if (count($arrayReturn))
            $arrayReturn = SegRolesbd::getRolInformation($arrayReturn);
        return $arrayReturn;
    }

    private function convetToUnidimensional($arrayRoles) {
        $result = array();
        foreach ($arrayRoles as $rol)
            $result[] = $rol->rolname;
        return $result;
    }

    private function getPgsqlSchemas($gestor, $user, $pass, $ipgestorbd, $namebd, $idsistema, $idnodo, $puerto, $idservidor, $idgestor) {
        $schemasArr = array();
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$namebd", 'information_schema');
        $schemas = Schemata::getPgsqlSchemasByDb($conn, $namebd);
        $dm->setCurrentConnection($nameCurrentConn);
        foreach ($schemas as $key => $esquemas) {
            $schemasArr[$key]['id'] = $esquemas['id'] . '-' . $idnodo;
            $schemasArr[$key]['text'] = $esquemas['text'];
            $schemasArr[$key]['leaf'] = true;
            $schemasArr[$key]['type'] = 'schemas';
            $schemasArr[$key]['lenguaje'] = 'pgsql';
            $schemasArr[$key]['icon'] = '../../views/images/schemas.PNG';
            $marcado = DatSistemaDatServidores::chequeado($idsistema, $ipgestorbd, $gestor, $namebd, $esquemas['esquema']);
            if ($marcado->getData() != null) {
                $schemasArr[$key]['checked'] = true;
                $schemasArr[$key]['marcado'] = $marcado[0]->idsistema . '_' . $marcado[0]->idservidor . '_' . $marcado[0]->idgestor . '_' . $marcado[0]->idbd . '_' . $marcado[0]->idesquema . '_' . "$gestor://$user:$pass@$ipgestorbd:$puerto/$namebd";
            } else
                $schemasArr[$key]['checked'] = false;
        }
        return $schemasArr;
    }

    private function getOracleDatabases($gestor, $user, $pass, $ipgestorbd, $idgestor, $idservidor, $idsistema, $idnodo, $puerto) {
        $rsa = new ZendExt_RSA_Facade();
        $SID = DatGestorDatServidorbd::getSid($idgestor, $idservidor);
        $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
        $bdArr = array();
        $key = 0;
        @$conn = oci_connect("$user", "$pass", "$cadenaConex");
        if ($conn == true) {
            $query = 'select name from sys.v_$database';
            $stid = oci_parse($conn, $query);
            oci_execute($stid);
            while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
                $bdArr[$key]['id'] = $row['NAME'] . '-' . $idnodo;
                $bdArr[$key]['text'] = $row['NAME'];
                $bdArr[$key]['namebd'] = $row['NAME'];
                $bdArr[$key]['gestor'] = $gestor;
                $bdArr[$key]['ipgestorbd'] = $ipgestorbd;
                $bdArr[$key]['puerto'] = $puerto;
                $bdArr[$key]['user'] = $user;
                $bdArr[$key]['passw'] = $rsa->encrypt($pass);
                $bdArr[$key]['idgestor'] = $idgestor;
                $bdArr[$key]['idservidor'] = $idservidor;
                $bdArr[$key]['type'] = 'bd';
                $key++;
            }
            oci_close($conn);
            $rolesbd = SegRolesbd::loadRoleBD($idservidor, $idgestor);
            $RolBD = $this->verifyRolesOracle($rolesbd, $gestor, $user, $pass, $ipgestorbd, $cadenaConex);
            if (count($RolBD)) {
                foreach ($RolBD as $roles) {
                    $bdArr[$key]['id'] = $roles->id . '-' . $idnodo;
                    $bdArr[$key]['idrolbd'] = $roles->id;
                    $bdArr[$key]['text'] = $roles->rolname;
                    $bdArr[$key]['leaf'] = true;
                    if (DatSistemaDatServidores::verifyCheckedRole($roles->id, $idsistema)) {
                        $bdArr[$key]['checked'] = true;
                        $bdArr[$key]['marcado'] = true;
                    } else
                        $bdArr[$key]['checked'] = false;
                    $bdArr[$key]['rol'] = true;
                    $bdArr[$key]['type'] = 'roles';
                    $bdArr[$key]['icon'] = '../../views/images/usuario.png';
                    $key++;
                }
            }
            return $bdArr;
        }
        else {
            echo"{'codMsg':3,'mensaje': 'Error de conexiÃ³n.'}";
            die;
        }
    }

    private function verifyRolesOracle($array, $gestor, $user, $pass, $ipgestorbd, $cadenaConex) {
        $arrayResult = array();
        $arrayDelete = array();
        $arrayReturn = array();
        $keyy = 0;
        $conn = oci_connect("$user", "$pass", "$cadenaConex");
        $query = 'select user_id, username from dba_users';
        $stid = oci_parse($conn, $query);
        oci_execute($stid);

        while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
            $arrayRolesGestor[$keyy] = $row['USERNAME'];
            $keyy++;
        }

        $arrayRoles = $this->convetToUnidimensional($array);
        $arrayDelete = array_diff($arrayRoles, $arrayRolesGestor);
        $arrayReturn = array_intersect($arrayRoles, $arrayRolesGestor);
        if (count($arrayDelete))
            SegRolesbd::deleteRoles($arrayDelete);
        if (count($arrayReturn))
            $arrayReturn = SegRolesbd::getRolInformation($arrayReturn);
        oci_close($conn);
        return $arrayReturn;
    }

    private function getOracleSchemas($gestor, $user, $pass, $ipgestorbd, $namebd, $idsistema, $idnodo, $puerto, $idservidor, $idgestor) {
        $SID = DatGestorDatServidorbd::getSid($idgestor, $idservidor);
        $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";

        $key = 0;
        $schemasArr = array();
        $conn = oci_connect("$user", "$pass", "$cadenaConex");
        $valor = 'XS$NULL';
        $query = "select username from dba_users where username <> all
                                    ('SYS', 'SYSTEM', 'DBSNMP', 'SYSMAN', 'OUTLN', 'MDSYS',
                                    'ORDSYS', 'EXFSYS', 'DMSYS', 'WMSYS', 'WKSYS', 'CTXSYS',
                                    'ANONYMOUS', 'XDB', 'WKPROXY', 'ORDPLUGINS', 'DIP',
                                    'SI_INFORMTN_SCHEMA', 'OLAPSYS', 'MDDATA', 'WK_TEST',
                                    'MGMT_VIEW', 'TSMSYS', 'FLOWS_FILES', 'FLOWS_030000',
                                    'OWBSYS', 'SCOTT', 'ORACLE_OCM', '$valor', 'APEX_PUBLIC_USER',
                                    'SPATIAL_CSW_ADMIN_USR', 'SPATIAL_WFS_ADMIN_USR', 'WORKFLOW') ";
        $stid = oci_parse($conn, $query);
        oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_ASSOC)) {

            $schemasArr[$key]['id'] = $row['USERNAME'] . '-' . $idnodo;
            $schemasArr[$key]['text'] = $row['USERNAME'];
            $schemasArr[$key]['leaf'] = true;
            $schemasArr[$key]['type'] = 'schemas';
            $marcado = DatSistemaDatServidores::chequeado($idsistema, $ipgestorbd, $gestor, $namebd, $row['USERNAME']);
            if ($marcado->getData() != null) {
                $schemasArr[$key]['checked'] = true;
                $schemasArr[$key]['marcado'] = $marcado[0]->idsistema . '_' . $marcado[0]->idservidor . '_' . $marcado[0]->idgestor . '_' . $marcado[0]->idbd . '_' . $marcado[0]->idesquema;
            } else
                $schemasArr[$key]['checked'] = false;
            $key++;
        }
        oci_close($conn);
        return $schemasArr;
    }

    private function verificarBasesDatos($bdObj, $idservidor, $idgestor) {       
        $bdArr = array();
        foreach ($bdObj as $bd) {
            if ($bd['type'] == 'bd')
                $bdArr[] = $bd['namebd'];
        }
        $bdNoUsadasObj = DatSistemaDatServidores::obtenerBdNoUsadas($bdArr, $idservidor, $idgestor);
        if (count($bdNoUsadasObj)) {
            $bdNUArr = array();
            foreach ($bdNoUsadasObj as $bdNoUsada)
                $bdNUArr[] = $bdNoUsada->idbd;
            $bdUsadasObj = DatSistemaDatServidores::obtenerBdUsadas($bdNUArr, $idservidor, $idgestor);
            if (count($bdUsadasObj)) {
                $bdBorrar = array();
                foreach ($bdNUArr as $idbd) {
                    $isBdUsada = false;
                    foreach ($bdUsadasObj as $bdUsada)
                        if ($bdUsada->idbd == $idbd) {
                            $isBdUsada = true;
                            break;
                        }
                    if (!$isBdUsada)
                        $bdBorrar[] = $idbd;
                }
            } else
                $bdBorrar = $bdNUArr;
            $esquemas = DatSistemaDatServidores::obtenerEsquemas($bdNUArr, $idservidor, $idgestor);
            $esqBorrar = array();
            if (count($esquemas)) {
                $arrayEsq = array();
                foreach ($esquemas as $esq)
                    $arrayEsq[] = $esq->idesquema;
                $arrayEsqUsados = DatSistemaDatServidores::obtenerEsquemasUsados($arrayEsq, $idservidor, $idgestor);
                if (count($arrayEsqUsados)) {
                    foreach ($arrayEsq as $idEsq) {
                        $isEsqUsado = false;
                        foreach ($arrayEsqUsados as $esqUsado)
                            if ($esqUsado->idesquema == $idEsq) {
                                $isEsqUsado = true;
                                break;
                            }
                        if (!$isEsqUsado)
                            $esqBorrar[] = $idEsq;
                    }
                } else
                    $esqBorrar = $arrayEsq;
            }
            DatSistemaDatServidores::borrarBdFisicamente($bdNUArr, $idservidor, $idgestor);
            if (count($esqBorrar))
                DatEsquema::borrarEsqFisicamente($esqBorrar);
            if (count($bdBorrar))
                DatBd::borrarBdFisicamente($bdBorrar);
        }
    }

    private function verificarEsquemas($schemasArr, $idservidor, $idgestor, $namebd) {
        $bdObj = DatBd::buscarnombd($namebd, 1, 0);
        if ($bdObj->getData() != null) {
            $idbd = $bdObj[0]->idbd;
            $esqArr = array();
            foreach ($schemasArr as $esq)
                $esqArr[] = $esq['text'];
            $esqNoUsadosObj = DatSistemaDatServidores::obtenerEsquemasNoUsados($esqArr, $idservidor, $idgestor, $idbd);

            if (count($esqNoUsadosObj)) {
                $esqNUArr = array();
                foreach ($esqNoUsadosObj as $esqNoUsado)
                    $esqNUArr[] = $esqNoUsado->idesquema; //esquemas no usados en ese servidor
                $esqUsadosObj = DatSistemaDatServidores::obtenerEsquemasUsadosByBD($esqNUArr, $idservidor, $idgestor, $idbd);
                $esqBorrar = array();
                if (count($esqUsadosObj)) { // esquemas usuados en otro servidor
                    foreach ($esqNUArr as $idesq) {
                        $isEsqUsado = false;
                        foreach ($esqUsadosObj as $esqUsado)
                            if ($esqUsado->idesquema == $idesq) {
                                $isEsqUsado = true;
                                break;
                            }
                        if (!$isEsqUsado)
                            $esqBorrar[] = $idesq;
                    }
                } else
                    $esqBorrar = $esqNUArr;
                DatSistemaDatServidores::borrarEsqFisicamente($esqNUArr, $idservidor, $idgestor, $idbd);
                if (count($esqBorrar))
                    DatEsquema::borrarEsqFisicamente($esqBorrar);
            }
            else {
                $esqUsadosByOther = DatSistemaDatServidores::existEsquemasUsadosByBD($esqArr, $idservidor, $idgestor, $idbd);
                if (count($esqUsadosByOther)) {
                    $esqUsadosByOther = $this->arrayUnidimencional($esqUsadosByOther);
                    $arrayEsqUsados = array_diff($esqArr, $esqUsadosByOther);
                    $esqUsados = DatSistemaDatServidores::existEsquemasUsedByBD($arrayEsqUsados, $idservidor, $idgestor, $idbd);
                    $esqUsados = $this->arrayUnidimencional($esqUsados);
                    $arrayNotUsed = array_diff($arrayEsqUsados, $esqUsados);
                    if (count($arrayNotUsed))
                        DatEsquema::deleteSchemas($arrayNotUsed);
                }
                else {
                    $esqUsados = DatSistemaDatServidores::existEsquemasUsedByBD($esqArr, $idservidor, $idgestor, $idbd);
                    if (!count($esqUsados))
                        DatEsquema::deleteSchemas($esqArr);
                }
            }
        }
    }

    private function arrayUnidimencional($esqUsados) {
        $array = array();
        foreach ($esqUsados as $valores)
            $array[] = $valores->text;
        return $array;
    }

    function sistemaeliminar($idsistema) {
        DatSistema::eliminarsistema($idsistema);
        $sistemashijos = DatSistema::obtenersistemashijos($idsistema);
        if (count($sistemashijos)) {
            foreach ($sistemashijos as $hijo) {
                if ($idsistema != $hijo->idsistema) {
                    $this->sistemaeliminar($hijo->idsistema);
                }
            }
            return true;
        } else
            return true;
    }

    function modificarhijos($idsistema, $externa) {
        $canthijos = DatSistema::obtenersistemashijos($idsistema);
        if (count($canthijos)) {
            foreach ($canthijos as $hijos) {
                $hijos->externa = $externa;
                $idsistema = $hijos->idsistema;
                $hijos->save();
                $this->modificarhijos($idsistema, $externa);
            }
        }
    }

    function devolverXML($idsistema) {
        $sistemas = DatSistema::obtenersistemaexportarxml($idsistema)->toArray(true);
        if (count($sistemas)) {
            $menu = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><menu>";
            foreach ($sistemas as $mdf)
                $this->subsistemasxml($mdf, $menu);
            $menu .= "</menu>";
            return $menu;
        } else {

            $menu1 = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><menu></menu>";
            return $menu1;
        }
    }

    private function subsistemasxml($raiz, &$menu) {
        $menu .= "<MenuItem name=\"{$raiz['denominacion']}\" id=\"{$raiz['idsistema']}\" externa=\"{$raiz['externa']}\" icon=\"{$raiz['icono']}\"  status=\"{$raiz['descripcion']}\">";
        $this->funcionalidadesxml($raiz['idsistema'], $menu);
        $sistemashijos = DatSistema::cargarsistemahijjos($raiz['idsistema']);
        if (count($sistemashijos)) {
            foreach ($sistemashijos as $hijo)
                if ($raiz['idsistema'] != $hijo['idsistema'])
                    $this->subsistemasxml($hijo, $menu);
        }
        $menu .= "</MenuItem>";
    }

    private function funcionalidadesxml($idsistema, &$menu) {
        $funcsistema = DatFuncionalidad::obtenerFuncionalidad($idsistema);
        if (count($funcsistema))
            foreach ($funcsistema as $funcionalidades) {
                $menu .= "<MenuItem name=\"{$funcionalidades->text}\" id=\"{$funcionalidades->idfuncionalidad}\"  src=\"{$funcionalidades->referencia}\" icon=\"{$funcionalidades->icono}\"  status=\"{$funcionalidades->descripcion}\" index=\"{$funcionalidades->index}\">";
                $this->accionesxml($funcionalidades->idfuncionalidad, $menu);
                $menu .= "</MenuItem>";
            }
    }

    private function accionesxml($idfuncionalidad, &$menu) {
        $acciones = DatAccion::obtenerAcciones($idfuncionalidad);
        if (count($acciones))
            foreach ($acciones as $accion)
                $menu .= "<MenuItem name=\"{$accion->denominacion}\" id=\"{$accion->idaccion}\"  icon=\"{$accion->icono}\"  status=\"{$accion->descripcion}\" index=\"{$accion->abreviatura}\"/>";
    }

    function exportarsistemaAction() {
        $idsistema = $this->_request->getPost('idsistema');
        $xml = $this->devolverXML($idsistema);
        $file_name = 'sistemas.xml';
        header('Content-Type: application/octet-stream');
        header('Content-Type: application/force-download');
        header("Content-Disposition: inline; filename=\"{$file_name}\"");
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Expires: 0');
        echo $xml;
    }

    function importarXMLAction() {
        $idsistema = $this->_request->getPost('idsistema');
        $dir_file = $_FILES['fileUpload']['tmp_name'];
        if (file_exists($dir_file)) {
            $xmlcargado = simplexml_load_file($dir_file);
            if ($xmlcargado && $this->ValidarXML($dir_file)) {
                    $array = $this->arrayainsertar($xmlcargado, $idsistema);
                    echo("{'codMsg':1,'mensaje': perfil.etiquetas.MsgInfImportar}");   
            }else
            echo("{'codMsg':3,'mensaje':perfil.etiquetas.MsgErrorXMLInvalidoII}");
        } else 
            echo("{'codMsg':3,'mensaje':perfil.etiquetas.MsgError}");        
    }

    function ValidarXML($dir_file) {
        $contentFile = file_get_contents($dir_file);
        $xml = DOMDocument::loadXML($contentFile);
        $dirweb = $_SERVER[DOCUMENT_ROOT];
        $dirapps = str_replace('web', 'apps', $dirweb);
        $dir_xml = $dirapps . DIRECTORY_SEPARATOR . 'seguridad' . DIRECTORY_SEPARATOR . 'comun' . DIRECTORY_SEPARATOR . 'recursos' . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'xsd' . DIRECTORY_SEPARATOR . 'estructSistema.xsd';
        if ($xml->schemaValidate($dir_xml)) {
            return true;
        } else
            return false;
    }

    function arrayainsertar($xmlcargado, $idpadre) {
        foreach ($xmlcargado as $xml) {
            if (!(string) $xml['src'] && !(string) $xml['index']) {
                $sistema = new DatSistema();
                $sistema->denominacion = (string) $xml['name'];
                $sistema->descripcion = (string) $xml['status'];
                $sistema->icono = (string) $xml['icon'];
                $sistema->externa = (string) $xml['externa'];
                $sistema->iddominio = $this->global->Perfil->iddominio;
                if ($idpadre)
                    $sistema->idpadre = $idpadre;
                $sistema->save();
                $sistemaCompartimentacion = new DatSistemaCompartimentacion();
                $sistemaCompartimentacion->idsistema = $sistema->idsistema;
                $sistemaCompartimentacion->iddominio = $this->global->Perfil->iddominio;
                $sistemaCompartimentacion->save();
                if (count($xml->MenuItem))
                    $this->arrayainsertar($xml->MenuItem, $sistema->idsistema);
            } elseif (!(string) $xml['src']) {
                $accion = new DatAccion();
                $accion->denominacion = (string) $xml['name'];
                $accion->descripcion = (string) $xml['status'];
                $accion->icono = (string) $xml['icon'];
                $accion->abreviatura = (string) $xml['index'];
                $accion->idfuncionalidad = $idpadre;
                $accion->iddominio = $this->global->Perfil->iddominio;
                $accion->save();
                $accionCompartimentacion = new DatAccionCompartimentacion();
                $accionCompartimentacion->idaccion = $accion->idaccion;
                $accionCompartimentacion->iddominio = $this->global->Perfil->iddominio;
                $accionCompartimentacion->save();
            } else {
                $funcionalidad = new DatFuncionalidad();
                $funcionalidad->denominacion = (string) $xml['name'];
                $funcionalidad->descripcion = (string) $xml['status'];
                $funcionalidad->icono = (string) $xml['icon'];
                $funcionalidad->referencia = (string) $xml['src'];
                $funcionalidad->index = (string) $xml['index'];
                $funcionalidad->idsistema = $idpadre;
                $funcionalidad->iddominio = $this->global->Perfil->iddominio;
                $funcionalidad->save();
                $funcionalidadCompartimentacion = new DatFuncionalidadCompartimentacion();
                $funcionalidadCompartimentacion->idfuncionalidad = $funcionalidad->idfuncionalidad;
                $funcionalidadCompartimentacion->iddominio = $this->global->Perfil->iddominio;
                $funcionalidadCompartimentacion->save();
                if (count($xml->MenuItem))
                    $this->arrayainsertar($xml->MenuItem, $funcionalidad->idfuncionalidad);
            }
        }
    }

    function pgsqlCargarRolesBD($gestor, $puerto, $user, $pass, $ipgestorbd, $bd, $limit, $start, $nombreRol, $rolConexion) {
        $dm = Doctrine_Manager::getInstance();
        $nameCurrentConn = $dm->getCurrentConnection()->getName();
        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd:$puerto/$bd", 'pg_catalog');
        $nombreRol = $this->TratarCriterioBusqueda($nombreRol);
        if ($nombreRol) {
            $result = PgAuthid::getRolBDbyName($conn, $nombreRol, $limit, $start)->toArray();
            $roles = GestrolesbdController::devolverDatosRol($result);
            $cant = PgAuthid::cantRolBDbyName($conn, $nombreRol);
        } else {
            $result = PgAuthid::getRolBD($conn, $limit, $start)->toArray();
            $roles = $this->devolverDatosRol($result);
            $cant = PgAuthid::cantRolBD($conn);
        }
        $dm->setCurrentConnection($nameCurrentConn);
        $roles = $this->Modificaciones_ArregloRoles($roles, $ipgestorbd, $gestor, $puerto, $rolConexion);
        return array('cantidad_filas' => $cant, 'datos' => $roles);
    }

    function TratarCriterioBusqueda($objetoName) {
        $inserted = "";
        for ($i = 0; $i < strlen($objetoName); $i++) {
            if ($objetoName[$i] == "_") {
                $inserted.='!';
            }
            $inserted.=$objetoName[$i];
        }
        return $inserted;
    }

    function Modificaciones_ArregloRoles($roles, $ipgestorbd, $gestor, $puerto, $rolConexion) {
        $result = array();
        foreach ($roles as $rol) {
            if (!$this->IsRolSistemaOrUserSistema($rol['rolname'], $ipgestorbd, $gestor, $puerto)) {
                if ($rol['rolname'] == $rolConexion)
                    $rol['estado'] = 1;
                else
                    $rol['estado'] = 0;
                if ($rolConexion != "") {
                    $rol['existconex'] = 1;
                } else {
                    $rol['existconex'] = 0;
                }
                $result[] = $rol;
            }
        }
        return $result;
    }

}

?>
