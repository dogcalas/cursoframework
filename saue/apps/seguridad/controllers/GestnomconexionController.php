<?php

class GestnomconexionController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestnomconexionAction() {
        $this->render();
    }

    function CargarGridTipoConexionesAction() {
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->configConection;
        $configConection = new SimpleXMLElement($dirconfigConection, null, true);
        $conexiones = $configConection->children();
        $filasGrid = array();
        $ConexionesEstaticas = array("defecto", "sistema", "rol", "usuario");
        $i = 0;
        foreach ($conexiones as $conexion) {
            array_push($filasGrid, array(
                'idconexion' => $i++,
                'seleccion' => ($conexion['seleccion']->__toString() == "false" ? false : true),
                'tipoconexion' => $conexion['denominacion']->__toString(),
                'descripcion' => $conexion['descripcion']->__toString(),
                'tipo' => $ConexionesEstaticas[$conexion['tipo']->__toString()]
            ));
        }
        $result['cantidad'] = count($filasGrid);
        $result['datos'] = $filasGrid;
        echo json_encode($result);
    }

    function cargarComboAction() {
        $ConexionesEstaticas = array(
            array("id" => 0, "conex" => "defecto"),
            array("id" => 1, "conex" => "sistema"),
            array("id" => 2, "conex" => "rol"),
            array("id" => 3, "conex" => "usuario"));
        echo json_encode(array(
            "cantidad" => count($ConexionesEstaticas),
            "datos" => $ConexionesEstaticas));
    }

    function InsertarConexionAction() {
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->configConection;
        $ConexionesEstaticas = array("defecto" => 0, "sistema" => 1, "rol" => 2, "usuario" => 3);
        $denominacion = $this->_request->getPost('tipoconexion');
        $descripcion = $this->_request->getPost('descripcion');
        $tipoConex = $this->_request->getPost('tipoconex');
        $tipo = $ConexionesEstaticas[$tipoConex];
        $this->AdicionarConexion($tipo, $descripcion, $denominacion, $tipoConex);
        echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgAddConx}");
    }

    function EliminarConexionAction() {
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->configConection;        
        $tipo = $this->_request->getPost('tipo');
        $contentFile = file_get_contents($dirconfigConection);        
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = FALSE;
        $isLoad = $dom->loadXML($contentFile);       
        if ($isLoad) {
            $deleteNode = $dom->getElementsByTagName($tipo);
            $elem = $deleteNode->item(0);
            $parent = $elem->parentNode;
            $parent->removeChild($elem);
            $dom->formatOutput = TRUE;
            $dom->save($dirconfigConection);
            echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgDelConx}");
        }
    }

    function ModificarTipoConexionAction() {
        $filasmodificadas = json_decode($this->_request->getPost('records'));             
        $registry = Zend_Registry::getInstance();        
        $dirconfigConection = $registry->config->xml->configConection;
        $DOM_XML_Conex = new DOMDocument();
        $contentfile = file_get_contents($dirconfigConection);
        $DOM_XML_Conex->preserveWhiteSpace = FALSE;
        $DOM_XML_Conex->loadXML($contentfile);
        $tipo = 0;           
        $ConexionesEstaticas = array("defecto" => 0, "sistema" => 1, "rol" => 2, "usuario" => 3);
        if (count($filasmodificadas) != 0) {
            $OldTipoConexion = $this->TipoConexion();
            foreach ($filasmodificadas as $fila) {
                if ($fila->seleccion == 1) {
                    $tipo = $ConexionesEstaticas[$fila->tipo];
                    break;
                }
            }
            $NewTipoConexion = $tipo;
            if ($OldTipoConexion != $NewTipoConexion) {
                $this->CambiarConexion($NewTipoConexion, $OldTipoConexion);
            }
            foreach ($filasmodificadas as $fila) {
                $tipo = $ConexionesEstaticas[$fila->tipo];
                $ListConexions = $this->getElementsByAttr($DOM_XML_Conex, "tipo", $tipo);
                $conexion = $ListConexions->item(0);
                $conexion->setAttribute('seleccion', $fila->seleccion ? "true" : "false");
            }
            $DOM_XML_Conex->formatOutput = TRUE;
            $DOM_XML_Conex->save($dirconfigConection);
        } else {
            $denominacion = $this->_request->getPost('tipoconexion');
            $descripcion = $this->_request->getPost('descripcion');            
            $OldTipo = $this->_request->getPost('tipo1');            
            $tipo = $this->_request->getPost('conex');
            //$conexionModificada=  $this->DenominacionConexion($descripcion);
            //print_r($conexionModificada);die("HOLA");
            if ($tipo == null) {
                $tipo = $OldTipo;
            }
            $this->ModificarConexion($DOM_XML_Conex, $dirconfigConection, $OldTipo, $tipo, $descripcion, $denominacion, $ConexionesEstaticas);
        }
        echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgModConx}");
    }

    private function ModificarConexion($DOM_XML_Conex, $dirconfigConection, $OldTipo, $tipo, $descripcion, $denominacion, $ConexionesEstaticas) {
        $ListConexion = $this->getElementsByAttr($DOM_XML_Conex, "tipo", $ConexionesEstaticas[$OldTipo]);
        $conexion = $ListConexion->item(0);
        if ($conexion->getAttribute('denominacion') != $denominacion) {
            $conexion->setAttribute('denominacion', $denominacion);
        }

        if ($conexion->getAttribute('descripcion') != $descripcion) {
            $conexion->setAttribute('descripcion', $descripcion);
        }

        if ($conexion->getAttribute('tipo') != $ConexionesEstaticas[$tipo]) {
            $conexion->setAttribute('tipo', $ConexionesEstaticas[$tipo]);
        }

        if ($OldTipo != $tipo) {
            $this->renameElement($conexion, $tipo);
        }
        $DOM_XML_Conex->formatOutput = TRUE;
        $DOM_XML_Conex->save($dirconfigConection);
    }

    private function AdicionarConexion($tipo, $descripcion, $denominacion, $tipoConex) {
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->configConection;
        $DOM_XML_Conex = new DOMDocument();
        $contentfile = file_get_contents($dirconfigConection);
        $DOM_XML_Conex->preserveWhiteSpace = FALSE;
        $DOM_XML_Conex->loadXML($contentfile);
        $conexion = $DOM_XML_Conex->createElement($tipoConex);
        $conexion->setAttribute('seleccion', "false");
        $conexion->setAttribute('denominacion', $denominacion);
        $conexion->setAttribute('tipo', $tipo);
        $conexion->setAttribute('descripcion', $descripcion);
        $root = $this->getElementByID($DOM_XML_Conex, "0");
        $root->appendChild($conexion);
        $DOM_XML_Conex->formatOutput = TRUE;
        $DOM_XML_Conex->save($dirconfigConection);
    }

    private function getElementByID($DOM, $id) {
        $xpath = new DOMXpath($DOM);
        $elements = $xpath->query("//*[@id='$id']");
        if ($elements->length > 0) {
            return $elements->item(0);
        }
        return false;
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
    
    private function DenominacionConexion($denominacion) {
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->configConection;
        $DOM_XML_Conex = new DOMDocument();
        $contentfile = file_get_contents($dirconfigConection);
        $DOM_XML_Conex->loadXML($contentfile);
        $elements = $this->getElementsByAttr($DOM_XML_Conex, "denominacion", $denominacion);
        $element = $elements->item(0);
        return $element->getAttribute('denominacion');
    }

    private function getElementsByAttr($DOM, $nameAtrr, $value) {
        $xpath = new DOMXpath($DOM);
        $elements = $xpath->query("//*[@$nameAtrr='$value']");
        if ($elements->length > 0) {
            return $elements;
        }
        return false;
    }

    private function CambiarConexion($NewTipoConexion, $OldTipoConexion) {
        $model = new NomTipoconexModel();
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->modulesconfig;
        $DOM_XML_Modules = new DOMDocument();
        $contentfile = file_get_contents($dirconfigConection);
        $DOM_XML_Modules->loadXML($contentfile);
        $RSA = new ZendExt_RSA_Facade();
        if ($OldTipoConexion == 0) {
            if ($NewTipoConexion == 1) {
                if (!$this->AllSubsistemasFathersHasConnections($DOM_XML_Modules)) {
                    throw new ZendExt_Exception('SEG061');
                }
                if (!$model->VerificarEsquemas()) {
                    throw new ZendExt_Exception('SEG067');
                }
                $this->ActivarDesactivarRoles($DOM_XML_Modules, true);
            } elseif ($NewTipoConexion == 2 || $NewTipoConexion == 3) {
                $Pgsql->InsertarRoles($NewTipoConexion, $OldTipoConexion);
                $model->AsignarPermisosNuevosRolesATraza($NewTipoConexion);
            }
            $Element = $this->getElementByID($DOM_XML_Modules, "0conn");
            $host = $Element->getAttribute('host');
            $gestor = $Element->getAttribute('gestor');
            $port = $Element->getAttribute('port');
            $rol = $Element->getAttribute('usuario');
            $bd = $Element->getAttribute('bd');
            $idServidor = DatServidor::getidServidorPorIP($host);
            $idServidor = $idServidor[0]['id'];
            $idgestor = DatGestor::getIdPorDenominacionPuerto($gestor, $port);
            $idgestor = $idgestor[0]['idgestor'];
            $rolConex = SegRolesbd::loadRoleBD($idServidor, $idgestor);
            $usuario = $rolConex[0]->rolname;
            $RolPassw = $rolConex[0]->passw;
            $pass = $RSA->decrypt($RolPassw);
            $Pgsql->ActivarDesactivarRole($host, $gestor, $port, $usuario, $pass, $rol, true);
        } elseif ($OldTipoConexion == 1) {
            if ($NewTipoConexion == 0) {
                $Element = $this->getElementByID($DOM_XML_Modules, "0conn");
                $host = $Element->getAttribute('host');
                $gestor = $Element->getAttribute('gestor');
                $port = $Element->getAttribute('port');
                $rol = $Element->getAttribute('usuario');
                $bd = $Element->getAttribute('bd');
                $esquemas = $Element->getAttribute('esquema');
                $idServidor = DatServidor::getidServidorPorIP($host);
                $idServidor = $idServidor[0]['id'];
                $idgestor = DatGestor::getIdPorDenominacionPuerto($gestor, $port);
                $idgestor = $idgestor[0]['idgestor'];
                $rolConex = SegRolesbd::loadRoleBD($idServidor, $idgestor);
                $usuario = $rolConex[0]->rolname;
                $RolPassw = $rolConex[0]->passw;
                $pass = $RSA->decrypt($RolPassw);
                $Pgsql->ActivarDesactivarRole($host, $gestor, $port, $usuario, $pass, $rol, true);
            } elseif ($NewTipoConexion == 2 || $NewTipoConexion == 3) {
                $Pgsql->InsertarRoles($NewTipoConexion, $OldTipoConexion);
            }
            $this->ActivarDesactivarRoles($DOM_XML_Modules, false);
            //$model->EliminarPermisosAEsquemas($host, $gestor, $port, $usuario, $pass, $bd);            
        } elseif ($OldTipoConexion == 2 || $OldTipoConexion == 3) {
            if ($NewTipoConexion == 0) {
                $Element = $this->getElementByID($DOM_XML_Modules, "0conn");
                $host = $Element->getAttribute('host');
                $gestor = $Element->getAttribute('gestor');
                $port = $Element->getAttribute('port');
                $rol = $Element->getAttribute('usuario');
                $idServidor = DatServidor::getidServidorPorIP($host);
                $idServidor = $idServidor[0]['id'];
                $idgestor = DatGestor::getIdPorDenominacionPuerto($gestor, $port);
                $idgestor = $idgestor[0]['idgestor'];
                $rolConex = SegRolesbd::loadRoleBD($idServidor, $idgestor);
                $usuario = $rolConex[0]->rolname;
                $RolPassw = $rolConex[0]->passw;
                $pass = $RSA->decrypt($RolPassw);
                $Pgsql->ActivarDesactivarRole($host, $gestor, $port, $usuario, $pass, $rol, true);
            } elseif ($NewTipoConexion == 1) {
                if (!$this->AllSubsistemasFathersHasConnections($DOM_XML_Modules)) {
                    throw new ZendExt_Exception('SEG061');
                }

                if (!$model->VerificarEsquemas()) {
                    throw new ZendExt_Exception('SEG067');
                }
                $this->ActivarDesactivarRoles($DOM_XML_Modules, true);
            } else {
                $Pgsql->InsertarRoles($NewTipoConexion, $OldTipoConexion);
            }
            $this->EliminarRoles($OldTipoConexion, $NewTipoConexion);
        }
        if ($OldTipoConexion == 2) {
            $registry = Zend_Registry::getInstance();
            $dirfile = $registry->config->dir_aplication;
            $dirfile.='/seguridad/comun/recursos/xml/securitypasswords.xml';
            unlink($dirfile);
        }
    }

    private function AllSubsistemasFathersHasConnections($DOM_XML_Modules) {
        $sistemasPadres = DatSistema::obtenerSistemasP();
        foreach ($sistemasPadres as $SistemaPadre) {
            $idsistema = $SistemaPadre['idsistema'];
            if ($this->getElementByID($DOM_XML_Modules, $idsistema) == false)
                return false;
        }
        return true;
    }

    private function ActivarDesactivarRoles($DOM_XML_Modules, $activate) {
        $AllSistemas = DatSistema::getsistemas();
        $ParamsForConnectionAndRolToActivate = array();
        $PgSql = new ZendExt_Db_Role_Pgsql();
        $RSA = new ZendExt_RSA_Facade();
        foreach ($AllSistemas as $Sistema) {
            $idsistema = $Sistema->idsistema;
            $Element = $this->getElementByID($DOM_XML_Modules, $idsistema . "conn");
            if ($Element != false) {
                $host = $Element->getAttribute('host');
                $gestor = $Element->getAttribute('gestor');
                $port = $Element->getAttribute('port');
                $usuario = $Element->getAttribute('usuario');
                $bd = $Element->getAttribute('bd');
                $esquema = $Element->getAttribute('esquema');
                if ($ParamsForConnectionAndRolToActivate[$host] == "") {
                    $idServidor = DatServidor::getidServidorPorIP($host);
                    $idServidor = $idServidor[0]['id'];
                    $idgestor = DatGestor::getIdPorDenominacionPuerto($gestor, $port);
                    $idgestor = $idgestor[0]['idgestor'];
                    $rolConex = SegRolesbd::loadRoleBD($idServidor, $idgestor);
                    $RolName = $rolConex[0]->rolname;
                    $RolPassw = $rolConex[0]->passw;
                    $RolPassw = $RSA->decrypt($RolPassw);
                    $ParamsForConnectionAndRolToActivate[$host][$gestor][$port][$usuario] = true;
                    $PgSql->VerificarDisponibilidadServidorBD(array(array('servidor' => $host, 'gestor' => $gestor, 'puerto' => $port, 'bd' => $bd, 'user' => $RolName, 'pass' => $RolPassw)));
                    $PgSql->ActivarDesactivarRole($host, $gestor, $port, $RolName, $RolPassw, $usuario, $activate);
                    $PgSql->AsignarPermisosARolSobreObjetos($host, $gestor, $port, $RolName, $RolPassw, $usuario, $esquema, $bd);
                } else if ($ParamsForConnectionAndRolToActivate[$host][$gestor] == "") {
                    $idServidor = DatServidor::getidServidorPorIP($host);
                    $idServidor = $idServidor[0]['id'];
                    $idgestor = DatGestor::getIdPorDenominacionPuerto($gestor, $port);
                    $idgestor = $idgestor[0]['idgestor'];
                    $rolConex = SegRolesbd::loadRoleBD($idServidor, $idgestor);
                    $RolName = $rolConex[0]->rolname;
                    $RolPassw = $rolConex[0]->passw;
                    $RolPassw = $RSA->decrypt($RolPassw);
                    $ParamsForConnectionAndRolToActivate[$host][$gestor][$port][$usuario] = true;
                    $PgSql->VerificarDisponibilidadServidorBD(array(array('servidor' => $host, 'gestor' => $gestor, 'puerto' => $port, 'bd' => $bd, 'user' => $RolName, 'pass' => $RolPassw)));
                    $PgSql->ActivarDesactivarRole($host, $gestor, $port, $RolName, $RolPassw, $usuario, $activate);
                    $PgSql->AsignarPermisosARolSobreObjetos($host, $gestor, $port, $RolName, $RolPassw, $usuario, $esquema, $bd);
                } else if ($ParamsForConnectionAndRolToActivate[$host][$gestor][$port] == "") {
                    $idServidor = DatServidor::getidServidorPorIP($host);
                    $idServidor = $idServidor[0]['id'];
                    $idgestor = DatGestor::getIdPorDenominacionPuerto($gestor, $port);
                    $idgestor = $idgestor[0]['idgestor'];
                    $rolConex = SegRolesbd::loadRoleBD($idServidor, $idgestor);
                    $RolName = $rolConex[0]->rolname;
                    $RolPassw = $rolConex[0]->passw;
                    $RolPassw = $RSA->decrypt($RolPassw);
                    $ParamsForConnectionAndRolToActivate[$host][$gestor][$port][$usuario] = true;
                    $PgSql->VerificarDisponibilidadServidorBD(array(array('servidor' => $host, 'gestor' => $gestor, 'puerto' => $port, 'bd' => $bd, 'user' => $RolName, 'pass' => $RolPassw)));
                    $PgSql->ActivarDesactivarRole($host, $gestor, $port, $RolName, $RolPassw, $usuario, $activate);
                    $PgSql->AsignarPermisosARolSobreObjetos($host, $gestor, $port, $RolName, $RolPassw, $usuario, $esquema, $bd);
                } else if ($ParamsForConnectionAndRolToActivate[$host][$gestor][$port][$usuario] == "") {
                    $idServidor = DatServidor::getidServidorPorIP($host);
                    $idServidor = $idServidor[0]['id'];
                    $idgestor = DatGestor::getIdPorDenominacionPuerto($gestor, $port);
                    $idgestor = $idgestor[0]['idgestor'];
                    $rolConex = SegRolesbd::loadRoleBD($idServidor, $idgestor);
                    $RolName = $rolConex[0]->rolname;
                    $RolPassw = $rolConex[0]->passw;
                    $RolPassw = $RSA->decrypt($RolPassw);
                    $ParamsForConnectionAndRolToActivate[$host][$gestor][$port][$usuario] = true;
                    $PgSql->VerificarDisponibilidadServidorBD(array(array('servidor' => $host, 'gestor' => $gestor, 'puerto' => $port, 'bd' => $bd, 'user' => $RolName, 'pass' => $RolPassw)));
                    $PgSql->ActivarDesactivarRole($host, $gestor, $port, $RolName, $RolPassw, $usuario, $activate);
                    $PgSql->AsignarPermisosARolSobreObjetos($host, $gestor, $port, $RolName, $RolPassw, $usuario, $esquema, $bd);
                }
            }
        }
    }

    private function EliminarRoles($OldTipoConexion, $NewTipoConexion) {
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $roles_servidores_gestores_bd = SegRolDatServidorDatGestor::getRoles_Servidores_Gestores_BD();
        if ($OldTipoConexion == 3)
            $usuario_servidores_gestores = SegUsuarioDatServidorDatGestor::getUsuario_Servidores_Gestores_BD();
        $ArrayOrganizado = $Pgsql->Unir__R_S_G_B_con_U_S_G($roles_servidores_gestores_bd, $usuario_servidores_gestores, $OldTipoConexion);

        $Pgsql->CrearConsultasEliminarRoles($ArrayOrganizado, $NewTipoConexion, $OldTipoConexion);
    }

}

?>