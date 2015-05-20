<?php

class NomTipoconexModel extends ZendExt_Model {

    public function NomTipoconexModel() {
        parent::ZendExt_Model();
    }

    function getidServidorPorIP($host) {
        $idServidor = DatServidor::getidServidorPorIP($host);
        return $idServidor;
    }

    function getIdPorDenominacionPuerto($gestor, $port) {
        $idgestor = DatGestor::getIdPorDenominacionPuerto($gestor, $port);
        return $idgestor;
    }

    function loadRoleBD($idServidor, $idgestor) {
        $rolConex = SegRolesbd::loadRoleBD($idServidor, $idgestor);
        return $rolConex;
    }

    function AsignarPermisosNuevosRolesATraza($NewTipoConexion) {
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $RSA = new ZendExt_RSA_Facade();
        $nombreRoles = SegRol::ObtenerNombreRoles();
        $contador = 0;
        $RolesBD = array();
        foreach ($nombreRoles as $roles) {
            $RolesBD[$contador] = "rol_" . $roles['denominacion'] . "_acaxia$NewTipoConexion";
            $contador++;
        }
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->modulesconfig;
        $DOM_XML_Modules = new DOMDocument();
        $contentfile = file_get_contents($dirconfigConection);
        $DOM_XML_Modules->loadXML($contentfile);
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
        foreach ($RolesBD as $rol) {
            $Pgsql->AsignarPermisosTraza($gestor, $usuario, $pass, $host, $bd, $port, $rol);
        }
    }

    static function CargarGridTipoConexionesAction() {
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
        return $result;
    }

    static function AdicionarConexion($tipo, $descripcion, $denominacion, $tipoConex) {
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
        $root = NomTipoconexModel::getElementByID($DOM_XML_Conex, "0");
        $root->appendChild($conexion);
        $DOM_XML_Conex->formatOutput = TRUE;
        $DOM_XML_Conex->save($dirconfigConection);
    }

    static function EliminarConexion($tipo) {
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->configConection;
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
        }
    }

    function TipoConexion($DOM_XML_Conex) {
        $elements = $this->getElementsByAttr($DOM_XML_Conex, "seleccion", "true");
        $element = $elements->item(0);
        return $element->getAttribute('tipo');
    }

    static function ModificarConexion($DOM_XML_Conex, $dirconfigConection, $OldTipo, $tipo, $descripcion, $denominacion, $ConexionesEstaticas) {
        $ListConexion = NomTipoconexModel::getElementsByAttr($DOM_XML_Conex, "tipo", $ConexionesEstaticas[$OldTipo]);
        $conexion = $ListConexion->item(0);
        $conexion->setAttribute('denominacion', $denominacion);
        $conexion->setAttribute('tipo', $ConexionesEstaticas[$tipo]);
        $conexion->setAttribute('descripcion', $descripcion);
        if ($OldTipo != $tipo)
            NomTipoconexModel::renameElement($conexion, $tipo);
        $DOM_XML_Conex->formatOutput = TRUE;
        $DOM_XML_Conex->save($dirconfigConection);
    }

    function getElementsByAttr($DOM, $nameAtrr, $value) {
        $xpath = new DOMXpath($DOM);
        $elements = $xpath->query("//*[@$nameAtrr='$value']");
        if ($elements->length > 0) {
            return $elements;
        }
        return false;
    }

    function renameElement($element, $newName) {
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

    static function AllSubsistemasFathersHasConnections($DOM_XML_Modules) {
        $sistemasPadres = DatSistema::obtenerSistemasP();
        foreach ($sistemasPadres as $SistemaPadre) {
            $idsistema = $SistemaPadre['idsistema'];
            if (NomTipoconexModel::getElementByID($DOM_XML_Modules, $idsistema) == false)
                return false;
        }
        return true;
    }

    static function EliminarRoles($OldTipoConexion, $NewTipoConexion) {
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $roles_servidores_gestores_bd = SegRolDatServidorDatGestor::getRoles_Servidores_Gestores_BD();
        if ($OldTipoConexion == 3)
            $usuario_servidores_gestores = SegUsuarioDatServidorDatGestor::getUsuario_Servidores_Gestores_BD();
        $ArrayOrganizado = $Pgsql->Unir__R_S_G_B_con_U_S_G($roles_servidores_gestores_bd, $usuario_servidores_gestores, $OldTipoConexion);

        $Pgsql->CrearConsultasEliminarRoles($ArrayOrganizado, $NewTipoConexion, $OldTipoConexion);
    }

    public function getElementByID($DOM, $id) {
        $xpath = new DOMXpath($DOM);
        $elements = $xpath->query("//*[@id='$id']");
        if ($elements->length > 0) {
            return $elements->item(0);
        }
        return false;
    }

    static function ActivarDesactivarRoles($DOM_XML_Modules, $activate) {
        $AllSistemas = DatSistema::getsistemas();
        $ParamsForConnectionAndRolToActivate = array();
        $PgSql = new ZendExt_Db_Role_Pgsql();
        $RSA = new ZendExt_RSA_Facade();

        foreach ($AllSistemas as $Sistema) {
            $idsistema = $Sistema->idsistema;
            $Element = NomTipoconexModel::getElementByID($DOM_XML_Modules, $idsistema . "conn");
            if ($Element != false) {
                $host = $Element->getAttribute('host');
                $gestor = $Element->getAttribute('gestor');
                $port = $Element->getAttribute('port');
                $usuario = $Element->getAttribute('usuario');
                $bd = $Element->getAttribute('bd');

                if ($ParamsForConnectionAndRolToActivate[$host] == "") {
                    $idServidor = NomTipoconexModel::getidServidorPorIP($host);
                    $idServidor = $idServidor[0]['id'];
                    $idgestor = NomTipoconexModel::getIdPorDenominacionPuerto($gestor, $port);
                    $idgestor = $idgestor[0]['idgestor'];
                    $rolConex = NomTipoconexModel::loadRoleBD($idServidor, $idgestor);
                    $RolName = $rolConex[0]->nombrerol;
                    $RolPassw = $rolConex[0]->passw;
                    $RolPassw = $RSA->decrypt($RolPassw);

                    $ParamsForConnectionAndRolToActivate[$host][$gestor][$port][$usuario] = true;
                    $PgSql->VerificarDisponibilidadServidorBD(array(array('servidor' => $host, 'gestor' => $gestor, 'puerto' => $port, 'bd' => $bd, 'user' => $RolName, 'pass' => $RolPassw)));
                    $PgSql->ActivarDesactivarRole($host, $gestor, $port, $RolName, $RolPassw, $usuario, $activate);
                } else if ($ParamsForConnectionAndRolToActivate[$host][$gestor] == "") {

                    $idServidor = NomTipoconexModel::getidServidorPorIP($host);
                    $idServidor = $idServidor[0]['id'];
                    $idgestor = NomTipoconexModel::getIdPorDenominacionPuerto($gestor, $port);
                    $idgestor = $idgestor[0]['idgestor'];
                    $rolConex = NomTipoconexModel::loadRoleBD($idServidor, $idgestor);
                    $RolName = $rolConex[0]->nombrerol;
                    $RolPassw = $rolConex[0]->passw;
                    $RolPassw = $RSA->decrypt($RolPassw);

                    $ParamsForConnectionAndRolToActivate[$host][$gestor][$port][$usuario] = true;
                    $PgSql->VerificarDisponibilidadServidorBD(array(array('servidor' => $host, 'gestor' => $gestor, 'puerto' => $port, 'bd' => $bd, 'user' => $RolName, 'pass' => $RolPassw)));
                    $PgSql->ActivarDesactivarRole($host, $gestor, $port, $RolName, $RolPassw, $usuario, $activate);
                } else if ($ParamsForConnectionAndRolToActivate[$host][$gestor][$port] == "") {

                    $idServidor = NomTipoconexModel::getidServidorPorIP($host);
                    $idServidor = $idServidor[0]['id'];
                    $idgestor = NomTipoconexModel::getIdPorDenominacionPuerto($gestor, $port);
                    $idgestor = $idgestor[0]['idgestor'];
                    $rolConex = NomTipoconexModel::loadRoleBD($idServidor, $idgestor);
                    $RolName = $rolConex[0]->nombrerol;
                    $RolPassw = $rolConex[0]->passw;
                    $RolPassw = $RSA->decrypt($RolPassw);

                    $ParamsForConnectionAndRolToActivate[$host][$gestor][$port][$usuario] = true;
                    $PgSql->VerificarDisponibilidadServidorBD(array(array('servidor' => $host, 'gestor' => $gestor, 'puerto' => $port, 'bd' => $bd, 'user' => $RolName, 'pass' => $RolPassw)));
                    $PgSql->ActivarDesactivarRole($host, $gestor, $port, $RolName, $RolPassw, $usuario, $activate);
                } else if ($ParamsForConnectionAndRolToActivate[$host][$gestor][$port][$usuario] == "") {

                    $idServidor = NomTipoconexModel::getidServidorPorIP($host);
                    $idServidor = $idServidor[0]['id'];
                    $idgestor = NomTipoconexModel::getIdPorDenominacionPuerto($gestor, $port);
                    $idgestor = $idgestor[0]['idgestor'];
                    $rolConex = NomTipoconexModel::loadRoleBD($idServidor, $idgestor);
                    $RolName = $rolConex[0]->nombrerol;
                    $RolPassw = $rolConex[0]->passw;
                    $RolPassw = $RSA->decrypt($RolPassw);
                    $ParamsForConnectionAndRolToActivate[$host][$gestor][$port][$usuario] = true;
                    $PgSql->VerificarDisponibilidadServidorBD(array(array('servidor' => $host, 'gestor' => $gestor, 'puerto' => $port, 'bd' => $bd, 'user' => $RolName, 'pass' => $RolPassw)));
                    $PgSql->ActivarDesactivarRole($host, $gestor, $port, $RolName, $RolPassw, $usuario, $activate);
                }
            }
        }
    }

    static function ActivarDesactivarRole($DOM_XML_Modules, $flag) {
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $RSA = new ZendExt_RSA_Facade();
        $element = NomTipoconexModel::getElementByID($DOM_XML_Modules, "0conn");
        $host = $element->getAttribute('host');
        $gestor = $element->getAttribute('gestor');
        $port = $element->getAttribute('port');
        $rol = $element->getAttribute('usuario');

        $idServidor = NomTipoconexModel::getidServidorPorIP($host);
        $idServidor = $idServidor[0]['id'];
        $idgestor = NomTipoconexModel::getIdPorDenominacionPuerto($gestor, $port);
        $idgestor = $idgestor[0]['idgestor'];
        $rolConex = NomTipoconexModel::loadRoleBD($idServidor, $idgestor);
        $usuario = $rolConex[0]->nombrerol;
        $RolPassw = $rolConex[0]->passw;
        $Pgsql->ActivarDesactivarRole($host, $gestor, $port, $usuario, $RolPassw, $rol, $flag);
    }

    private function ObtenerEsquemas() {
        $esquemas = DatEsquema::cargarnomesquemas();
        return $esquemas;
    }

    private function ObtenerRolesXML() {
        $contador = 0;
        $AllSistemas = DatSistema::getsistemas();
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->modulesconfig;
        $DOM_XML_Modules = new DOMDocument();
        $contentfile = file_get_contents($dirconfigConection);
        $DOM_XML_Modules->loadXML($contentfile);
        foreach ($AllSistemas as $Sistema) {
            $idsistema = $Sistema->idsistema;
            $Element = $this->getElementByID($DOM_XML_Modules, $idsistema . "conn");
            if ($Element != false) {
                $roles[$contador] = $Element->getAttribute('usuario');
            }
            $contador++;
        }
        return $roles;
    }

    public function EliminarPermisosAEsquemas($host, $gestor, $port, $usuario, $pass, $bd) {
        $esquemas = $this->ObtenerEsquemas();
        $Pgsql = new ZendExt_Db_Role_Pgsql();
        $roles = $this->ObtenerRolesXML();
        $Pgsql->EliminarPermisosARolSobreObjetos($host, $gestor, $port, $usuario, $pass, $esquemas, $bd, $roles);
    }

    public function VerificarEsquemas() {
        $AllSistemas = DatSistema::getsistemas();
        $registry = Zend_Registry::getInstance();
        $dirconfigConection = $registry->config->xml->modulesconfig;
        $DOM_XML_Modules = new DOMDocument();
        $contentfile = file_get_contents($dirconfigConection);
        $DOM_XML_Modules->loadXML($contentfile);
        foreach ($AllSistemas as $Sistema) {
            $idsistema = $Sistema->idsistema;
            $Element = $this->getElementByID($DOM_XML_Modules, $idsistema . "conn");
            if ($Element != false) {
                if ($Element->getAttribute('esquema') == "") {
                    return false;
                }
            }
        }
        return true;
    }

}
