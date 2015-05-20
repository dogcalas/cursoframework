<?php

include_once 'IndexController.php';

class PortalController extends IndexController {

    public function indexAction() {
        //Se redirecciona hacia la pagina de inicio del portal
        header("Location:../");
    }

    public function portalAction() {
        $perfil = $this->getPerfil();
        if ($perfil->tema) {
            //$this->view->dir_ext_ccs = $this->view->dir_extjs.'temas/'.$perfil->tema.'/';
            //$dir_fich = str_replace('//','/',$_SERVER['DOCUMENT_ROOT'] . $this->view->dir_ext_ccs);
            //if (file_exists($dir_fich))
            $this->render();
            //else
            //throw new ZendExt_Exception('EP003');
        }
        else
            throw new ZendExt_Exception('EP002');
    }

    public function cargarperfilAction() {
        $perfil = $this->getPerfil();
        $perfil->entidad = $this->global->Estructura->denominacion;
        echo json_encode($perfil);
    }

    public function cargarsistemasAction() {
        $session = Zend_Registry::get('session');
        $certificate = $session->certificado;
        $idestructura = $this->global->Estructura->idestructura;
        $seguridad = ZendExt_Component_Component::getComponent($this, 'seguridadInterface');
        $systemsArray = $seguridad->getSystems($certificate, $idestructura);
        if (count($systemsArray))
            echo json_encode(array('menu' => $systemsArray));
        else
            throw new ZendExt_Exception('EP007');
    }

    public function cargarconceptosAction() {
        $perfil = $this->getPerfil();
        //Temporal
        header("Content-type:text/xml");
        echo file_get_contents("comun/recursos/xml/conceptos_{$perfil->idioma}.xml");
    }

    public function cargarmodfuncAction() {
        $session = Zend_Registry::get('session');
        $certificate = $session->certificado;
        $idestructura = $this->global->Estructura->idestructura;
        $idsistema = $this->_request->getPost('node');
        $seguridad = ZendExt_Component_Component::getComponent($this, 'seguridadInterface');
        $systemsArray = $seguridad->getSystemsFunctions($certificate, $idsistema, $idestructura);
        if (count($systemsArray))
            echo json_encode($systemsArray);
        else
            echo json_encode($systemsArray = array());
    }

    public function cargardesktopAction() {

        $register = Zend_Registry::getInstance();
        $certifcate = $register->session->certificado;
        $idestructura = $this->global->Estructura->idestructura;
        $array = $this->integrator->seguridad->ObtenerSistemasFuncionalidadesDesktopModulos($certifcate, $idestructura);
        //No muestra las funcionalidades del atrium
        $newArray = array();
        foreach($array as $val){
            if($val['text'] != "Atrium"){
                $newArray[]=$val;
            }
        }

        if (count($newArray))
            echo json_encode($newArray);
        else
            throw new ZendExt_Exception('EP007');
    }

    public function json_encode_desktop($modulofunc, $padre) {
        echo '[';
        $ini = true;
        foreach ($modulofunc as $key => $mdf) {
            if ($padre || $mdf instanceof Funcionalidad || (!$padre && $mdf->idmodulo != $mdf->idmodpadre)) {
                if ($ini) {
                    if ($mdf->icono)
                        echo "{icono:'{$this->view->dir_ext_ccs}images/iconos/{$mdf->icono}.png'";
                    else
                        echo "{icono:'{$this->view->dir_ext_ccs}images/iconos/funcionalidad.png'";
                    $ini = false;
                }
                else {
                    if ($mdf->icono)
                        echo ",{icono:'{$this->view->dir_ext_ccs}images/iconos/{$mdf->icono}.png'";
                    else
                        echo ",{icono:'{$this->view->dir_ext_ccs}images/iconos/funcionalidad.png'";
                }
                if ($mdf instanceof Funcionalidad) {
                    echo ",id:'{$mdf->idfuncionalidad}'";
                    echo ",text:'{$mdf->denominacion}'";
                    if ($mdf->referencia)
                        echo ",referencia:'..\/..\/..\/{$mdf->referencia}'";
                    else
                        echo ",referencia:''";
                }
                else {
                    echo ",id:'{$mdf->idmodulo}'";
                    echo ",text:'{$mdf->nombre}'";
                    if ($mdf->Funcionalidad->count()) {
                        echo ",menu:";
                        $this->json_encode_desktop($mdf->Funcionalidad, 0);
                    } elseif ($mdf->Hijos->count()) {
                        echo ",menu:";
                        $this->json_encode_desktop($mdf->Hijos, 0);
                    }
                    else
                        echo ",menu:[]";
                }
                echo "}";
            }
        }
        echo ']';
    }

    public function cargardesktopmoduloAction() {
        $session = Zend_Registry::get('session');
        $certifcate = $session->certificado;
        $idestructura = $this->global->Estructura->idestructura;
        $seguridad = ZendExt_Component_Component::getComponent($this, 'seguridadInterface');
        $systemsArray = $seguridad->getSystemsDesktopModules($certifcate, $idestructura);
        if (count($systemsArray))
            echo json_encode($systemsArray);
        else
            throw new ZendExt_Exception('EP008');
    }

    public function json_encode_desktopmodulo($modulofunc, $padre) {
        echo '[';
        $ini = true;
        foreach ($modulofunc as $key => $mdf) {
            if ($padre || (!$padre && $mdf->idmodulo != $mdf->idmodpadre)) {
                if ($ini) {
                    if ($mdf->icono)
                        echo "{icono:'{$this->view->dir_ext_ccs}images/iconos/{$mdf->icono}.png'";
                    else
                        echo "{icono:'{$this->view->dir_ext_ccs}images/iconos/funcionalidad.png'";
                    $ini = false;
                }
                else {
                    if ($mdf->icono)
                        echo ",{icono:'{$this->view->dir_ext_ccs}images/iconos/{$mdf->icono}.png'";
                    else
                        echo ",{icono:'{$this->view->dir_ext_ccs}images/iconos/funcionalidad.png'";
                }
                echo ",id:'{$mdf->idmodulo}'";
                echo ",text:'{$mdf->nombre}'";
                if ($mdf->Hijos->count() && !($mdf->Hijos->count() == 1 && $mdf->idmodulo == $mdf->idmodpadre)) {
                    echo ",menu:";
                    $this->json_encode_desktopmodulo($mdf->Hijos, 0);
                }
                echo "}";
            }
        }
        echo ']';
    }

    public function closeportalAction() {
        $registro = Zend_Registry::getInstance();
        $registro->session->close = true;
        //$sigis=ZendExt_Aspect_Security_Sgis::getInstance();
        //$sigis->deleteSessionFile();                        
        // $seguridad = ZendExt_Component_Component::getComponent($this, 'seguridadInterface');
        $this->integrator->seguridad->eliminarCertificadosUsuario($this->global->Perfil->idusuario);
        echo json_encode(array('close' => true));
    }

    public function geticonmenuAction() {
        header('Content-type: image/gif');
        $img = imagecreatefromgif("{$this->view->dir_ext_ccs}images/iconos/{$_GET['icon']}.png");
        imagegif($img);
    }

    public function cargardatostabpanelAction() {
        $idestructura = $this->global->Estructura->idestructura;
        $entidadconfigurada = true; //$this->integrator->parametros->EntidadConfigurada($idestructura);
        if ($entidadconfigurada)
            echo '{"success": 1}';
        else
            header('Location: ../../comun/recursos/xml/carga_inicial.xml');
    }

    public function dameserviciocargainicialAction() {
        $idServ = $_GET['id'];
        if ($idServ) {
            switch ($idServ) {
                case 1: print_r($this->integrator->parametros->Bienvenida());
                    die;
                    break;
                case 2: $this->integrator->parametros->Ejercicio();
                    break;
                case 3: $this->integrator->parametros->Cierre();
                    $this->integrator->parametros->Fecha();
                    break;
                case 4: $this->integrator->parametros->MonedaC();
                    break;
                case 5: $this->integrator->parametros->MonedaE();
                    break;
                case 6: $this->integrator->parametros->Finalizar();
                    break;
            }
        }
    }

    public function cambiarpasswordAction() {
        $this->render('cambiarpassword');
    }

    public function nuevopasswordAction() {
        $oldpass = $this->_request->getPost('oldpass');
        $newpass = $this->_request->getPost('contrasenap');
        $res = $this->integrator->seguridad->CambiarContrasenna($this->global->Perfil->usuario, $oldpass, $newpass);
        if ($res)
            echo json_encode('{success: true}');
        else
            throw new ZendExt_Exception('SEGPASS01');
    }

}
