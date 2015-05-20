<?php

class IndexController extends ZendExt_Controller_Secure
{

    public function init()
    {
        $this->model = new PortalModel();
        parent::init();
    }

    private function shorty($str)
    {
        $arrStr = explode(" ", $str);
        $cadtotal = "";
        foreach ($arrStr as $cad) {
            if (ctype_upper(substr($cad, 0, 1))) {
                $cadtotal .= substr($cad, 0, 1);
            }
        }
        return $cadtotal;
    }

    private function estaEndominios($iddomio, $arraydominios)
    {
        foreach ($arraydominios as $domain) {
            if ($domain['identidad'] == $iddomio)
                return true;
        }
        return false;
    }

    public function cargardominioAction()
    {
        $security = ZendExt_Aspect_Security_Sgis::getInstance();
        $dominios = $security->getDomain(0);
        $idusuario = $this->global->Perfil->idusuario;

        //   $dominiosuser = $this->integrator->seguridad->dameUsuarioEntidad($idusuario);

        $arr = array();

        foreach ($dominios as $domain) {
            //  if ($this->estaEndominios($domain['id'], $dominiosuser)) {
            $temp['name'] = $domain['text'];
            $temp['fullName'] = $domain['text'];
            $temp['url'] = "views/images/index/facultad.png";
            $temp['size'] = 2828;
            $temp['lastmod'] = 1303810973000;
            $temp['id'] = $domain['id'];

            $arr[] = $temp;
            $temp = null;
            //  }
        }
        if (count($dominios))
            echo json_encode($arr);
        else
            echo json_encode(array());
    }

    public function loadPreviewAction()
    {

        $nombre = $this->_request->getPost('node');
        $arr = array();
        $temp['name'] = $nombre;
        $temp['url'] = "views/images/index/facultad.png";
        $temp['size'] = 28;
        $temp['lastmod'] = 1303810973000;
        $arr[] = $temp;
        $temp = null;

        echo json_encode($arr);
    }

    public function entraralsistemaAction()
    {
        $register = Zend_Registry::getInstance();
        $tipoacceso = $this->_request->getPost('tipoacceso');
        if ($tipoacceso == 'nuevo' && !$register->session->close) {
            $register->session->close = true;
            echo json_encode(array('reload' => false));
        } else {
            $_SERVER['HTTP_X_REQUESTED_WITH'] = 1;
            $identidad = $this->_request->getPost('dominio');

//            $seguridad = $this->component->seguridadInterface;
//            $access = $seguridad->verifyAccessEntity($register->session->certificado, $identidad);
            $access = $this->integrator->seguridad->VerificarAccesoEntidad($register->session->certificado, $identidad);
            // $access = $this->component->seguridadInterface->verifyAccessEntity($register->session->certificado, $identidad);
            if (!$access)
                throw new ZendExt_Exception('EP009');
            if (!isset($register->session->entidad) || $identidad != $register->session->entidad->idestructura) {
                $estructura = $this->integrator->metadatos->DameEstructura($identidad);
                $register->session->entidad = $estructura[0];
                $cacheObj = ZendExt_Cache::getInstance();
                $cacheData = $cacheObj->load(session_id());
                $cacheData->entidad = $estructura[0];
                $cacheObj->save($cacheData, session_id(), 25200);
                $register->session->idestructura = $identidad;
                $register->session->idrol = $access['idrol'];
                $register->session->rol = $access['denominacion'];
            }
            echo json_encode(array('reload' => true));
        }
    }

    protected function getUsuario()
    {
        $registro = Zend_Registry::getInstance();
        $alias = $registro->session->usuario;
        $usuario = $this->model->buscarUsuarioByAlias($alias);
        if ($usuario) {
            $usrArray = $usuario->toArray();
            $registro->session->perfil = $usrArray[0];
            return $usrArray[0];
        } else
            throw new ZendExt_Exception('EP001');
    }

    public function indexAction()
    {
        $perfil = $this->getPerfil();
        if ($perfil->tema) {
            //$this->view->dir_ext_ccs = $this->view->dir_ext_ccs . $perfil->tema . '/';
            $identidad = $this->global->Perfil->accesodirecto;
            $this->view->accesodirecto = $identidad;
            if ($identidad != 0) {
                $register = Zend_Registry::getInstance();
                $access = $this->integrator->seguridad->VerificarAccesoEntidad($register->session->certificado, $identidad);
                if (!$access)
                    throw new ZendExt_Exception('EP009');
                if (!isset($register->session->entidad) || $identidad != $register->session->entidad->idestructura) {
                    $metadatos = $this->component->estructuraInterface;
                    $estructura = $metadatos->DameEstructura($identidad);
                    $register->session->entidad = $estructura[0];
                    $cacheObj = ZendExt_Cache::getInstance();
                    $cacheData = $cacheObj->load(session_id());
                    $cacheData->entidad = $estructura[0];
                    $cacheObj->save($cacheData, session_id(), 25200);
                    $register->session->idestructura = $identidad;
                }
                $register->session->idrol = $access['idrol'];
                $register->session->rol = $access['denominacion'];
            } else {

                $register = Zend_Registry::getInstance();
                $idestructura = $this->global->Perfil->idestructuracomun;

                $access = $this->integrator->seguridad->VerificarAccesoEntidad($register->session->certificado, $idestructura);

                $register->session->idrol = $access['idrol'];
                $register->session->rol = $access['denominacion'];
            }
            //$dir_fich = str_replace('//','/',$_SERVER['DOCUMENT_ROOT'] . $this->view->dir_ext_ccs);
            //if (file_exists($dir_fich))
            $this->render();
            //else
            //throw new ZendExt_Exception('EP003');
        } else
            throw new ZendExt_Exception('EP002');
    }

    public function closeportalAction()
    {
        $cache = ZendExt_Cache :: getInstance();
        $cache->clean();
        $registro = Zend_Registry::getInstance();
        $registro->session->close = true;
        $seguridad = $this->component->seguridadInterface;
        $seguridad->deleteUserCertificates($this->global->Perfil->idusuario);
        header("Location:../");
    }

}
