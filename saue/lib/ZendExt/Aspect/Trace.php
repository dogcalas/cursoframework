<?php

/**
 * ZendExt_Aspect_Trace
 *
 * Integracion de trazas
 *
 * @author: Elianys Hurtado && Aquiles Maso
 * @autor: René R. Bauta
 * @copyright UCID-ERP Cuba
 * @package: ZendExt
 * @version 1.5-0
 */
class ZendExt_Aspect_Trace implements ZendExt_Aspect_ISinglenton
{

    private $start_script_runtime = 0;
    private $end_script_runtime = 0;
    private $memory;

    /**
     * Constructor de la clase, es privado para impedir que pueda
     * ser instanciado y de esta forma garantizar que la instancia
     * sea un singlenton
     *
     * @return void
     */
    private function __construct()
    {

    }

    static public function getInstance()
    {
        static $instance;
        if (!isset($instance))
            $instance = new self ();
        return $instance;
    }

    /**
     * Salva de las trazas de inicio de accion
     *
     */
    public function beginTraceAction()
    {
        $this->script_runtime();
        $ip_host = $this->devolverIp();
        $frontController = Zend_Controller_Front::getInstance();
        $controller = $frontController->getRequest()->getControllerName();
        $action = $frontController->getRequest()->getActionName();
        $register = Zend_Registry::getInstance();
        $user = $register->session->perfil['usuario'];
        $global = ZendExt_GlobalConcept::getInstance();
        $idestructura = $global->Estructura->idestructura;
        $moduleReference = $register->config->module_reference;
        $pEstructura = $global->Estructura->denominacion;
        $idRol = $register->session->idrol;
        $pRol = $register->session->rol;
        $idDominio = $register->session->perfil['iddominio'];
        $pDominio = $register->session->perfil['dominio'];
        if ($this->VerifTraza($moduleReference)) {
            $beginTrace = new ZendExt_Trace_Container_Action(false, true, $moduleReference, $controller, $action, $ip_host, $idRol, $idDominio, $user, $idestructura, $pRol, $pDominio, $pEstructura);
            $instance = ZendExt_Trace :: getInstance();
            $instance->handle($beginTrace);
            $this->memory = memory_get_usage();
        }
    }

    /**
     * Salva de las trazas de fin de accion
     *
     */
    public function endTraceAction()
    {
        $register = Zend_Registry::getInstance();
        $usuario = $register->session->perfil['usuario'];
        $ip_host = $this->devolverIp();
        $frontController = Zend_Controller_Front::getInstance();
        $action = $frontController->getRequest()->getActionName();
        $controller = $frontController->getRequest()->getControllerName();
        $this->script_runtime();
        $exectime = $this->end_script_runtime;
        $global = ZendExt_GlobalConcept::getInstance();
        $idestructura = $global->Estructura->idestructura;
        $moduleReference = $register->config->module_reference;
        $pEstructura = $global->Estructura->denominacion;
        $idRol = $register->session->idrol;
        $pRol = $register->session->rol;
        $idDominio = $register->session->perfil['iddominio'];
        $pDominio = $register->session->perfil['dominio'];
        if ($this->VerifTraza($moduleReference)) {
            $memory = (memory_get_usage() - $this->memory) / 1048576;
            $endTrace = new ZendExt_Trace_Container_Performance($exectime, $memory, false, false, $moduleReference, $controller, $action, $ip_host, $idRol, $idDominio, $usuario, $idestructura, $pRol, $pDominio, $pEstructura);
            $instance = ZendExt_Trace :: getInstance();
            $instance->handle($endTrace);
        }
    }

    /**
     * Salva las trazas de fallo de la accion
     *
     */
    public function failedTraceAction(Exception $e = null)
    {
        if (!($e instanceof ZendExt_Exception))
            $e = new ZendExt_Exception('NOCONTROLLED', $e);
        $this->start_script_runtime = 0;
        $this->end_script_runtime = 0;
        $ip_host = $this->devolverIp();
        $global = ZendExt_GlobalConcept::getInstance();
        $frontController = Zend_Controller_Front::getInstance();
        $request = $frontController->getRequest();
        $controller = "";
        $action = "";
        if (isset($request)) {
            $controller = $request->getControllerName();
            $action = $request->getActionName();
        }

        $register = Zend_Registry::getInstance();
        $user = $register->session->perfil['usuario'];
        $moduleReference = $register->config->module_reference;
        $pEstructura = $global->Estructura->denominacion;
        $idestructura = $global->Estructura->idestructura;
        $idRol = $register->session->idrol;
        $pRol = $register->session->rol;
        $idDominio = $register->session->perfil['iddominio'];
        $pDominio = $register->session->perfil['dominio'];
        if ($this->VerifTraza($moduleReference)) {
            $failedTrace = new ZendExt_Trace_Container_Action(true, false, $moduleReference, $controller, $action, $ip_host, $idRol, $idDominio, $user, $idestructura, $pRol, $pDominio, $pEstructura);

            /*$codeExc = $e->getIdException();
            $typeExc = $e->getType();
            $langExc = $global->Perfil->idioma;
            $msgExc = $e->getMessage();
            $descExc = $e->getDescription();
            $traceExc = $e->getTraceAsString();
            $exceptionTrace = new ZendExt_Trace_Container_Exception($codeExc, $typeExc, $langExc, $msgExc, $descExc, $traceExc, $ip_host, $idRol, $idDominio, $user, $idestructura, $pRol, $pDominio, $pEstructura);
            //print_r($exceptionTrace);die;*/
            $instance = ZendExt_Trace :: getInstance();
            $instance->handle($failedTrace);
            $this->exceptionTraceAction($e);
            //$instance->handle($exceptionTrace);
        }
    }

    /**
     * Funcion para el calculo del tiempo de ejecucion de la accion
     */
    private function script_runtime($round = 20)
    {
        //Check to see if the global is already set
        if ($this->start_script_runtime != 0) {
            //The global was set. So, get the current microtime and explode it into an array.
            list($msec, $sec) = explode(" ", microtime());
            $this->end_script_runtime = round(($sec + $msec) - $this->start_script_runtime, $round);
        } else {
            // The global was not set. Create it!
            list($msec, $sec) = explode(" ", microtime());
            $this->start_script_runtime = $sec + $msec;
        }
    }

    /**
     * Salva las trazas de excepciones
     *
     * @param ZendExt_Exception $exception
     */
    public function exceptionTraceAction($exception)
    {
        $code = $exception->getIdException();
        $type = $exception->getType();
        $ip_host = $this->devolverIp();
        $register = Zend_Registry::getInstance();
        $languaje = $register->session->perfil['idioma'];
        $user = $register->session->perfil['usuario'];
        $mensaje = $exception->getMessage();
        $descripcion = (string)$exception->getDescription();
        $global = ZendExt_GlobalConcept::getInstance();
        $idestructura = $global->Estructura->idestructura;
        $moduleReference = $register->get(config)->module_reference;
        $log = $exception->getInnerException();
        $pEstructura = $global->Estructura->denominacion;
        $idRol = $register->session->idrol;
        $pRol = $register->session->rol;
        $idDominio = $register->session->perfil['iddominio'];
        $pDominio = $register->session->perfil['dominio'];
        if ($this->VerifTraza($moduleReference)) {
            $exceptionTrace = new ZendExt_Trace_Container_Exception($code, $type, $languaje, $mensaje, $descripcion, $log, $ip_host, $idRol, $idDominio, $user, $idestructura, $pRol, $pDominio, $pEstructura);

            $instance = ZendExt_Trace :: getInstance();
            $instance->handle($exceptionTrace);
        }
    }

    public function failedTraceIoC($exception, $class, $method, $targetComponent)
    {
        $sourceComponent = Zend_Registry::get('config')->module_name;
        $register = Zend_Registry::getInstance();
        $usuario = $register->session->perfil['usuario'];
        $global = ZendExt_GlobalConcept::getInstance();
        $ip_host = $this->devolverIp();
        $pIdCommonStructure = $global->Estructura->idestructura;
        $request = Zend_Controller_Front::getInstance()->getRequest();
        if ($request) {
            $action = $request->getActionName();
            $controller = $request->getControllerName();
        } else {
            $action = 'framework';
            $controller = 'framework';
        }
        $code = $exception->getIdException();
        $mensaje = $exception->getMessage();
        $descripcion = (string)$exception->getDescription();
        $log = (string)$exception->getInnerException();
        $pEstructura = $global->Estructura->denominacion;
        $idRol = $register->session->idrol;
        $pRol = $register->session->rol;
        $idDominio = $register->session->perfil['iddominio'];
        $pDominio = $register->session->perfil['dominio'];
        //if($this->VerifTraza($moduleReference)) {
        $integracion = new ZendExt_Trace_Container_IoCException($code, $mensaje, $descripcion, $sourceComponent, $targetComponent, $class, $method, $action, $log, $controller, $ip_host, $idRol, $idDominio, $usuario, $pIdCommonStructure, $pRol, $pDominio, $pEstructura);
        //print_r($integracion);die;
        $instance = ZendExt_Trace :: getInstance();
        $instance->handle($integracion);
        //}
    }

    public function beginTraceIoC($targetComponent, $class, $method, $iocType)
    {
        $sourceComponent = Zend_Registry::get('config')->module_name;
        $register = Zend_Registry::getInstance();
        $usuario = $register->session->perfil['usuario'];
        $global = ZendExt_GlobalConcept::getInstance();
        $ip_host = $this->devolverIp();
        $pIdCommonStructure = $global->Estructura->idestructura;
        $pEstructura = $global->Estructura->denominacion;
        $idRol = $register->session->idrol;
        $pRol = $register->session->rol;
        $idDominio = $register->session->perfil['iddominio'];
        $pDominio = $register->session->perfil['dominio'];
        $request = Zend_Controller_Front::getInstance()->getRequest();

        if ($request) {
            $action = $request->getActionName();
            $controller = $request->getControllerName();
        } else {
            $action = 'framework';
            $controller = 'framework';
        }

        $moduleReference = $register->get(config)->module_reference;
        if ($this->VerifTraza($moduleReference)) {
            if ($iocType == 'ioc') {
                $integracion = new ZendExt_Trace_Container_IoC(false, $sourceComponent, $targetComponent, $action, $class, $method, $ip_host, $idRol, $idDominio, $usuario, $pIdCommonStructure, $pRol, $pDominio, $pEstructura);
            } else {
                $integracion = new ZendExt_Trace_Container_IoC(true, $sourceComponent, $targetComponent, $action, $class, $method, $ip_host, $idRol, $idDominio, $usuario, $pIdCommonStructure, $pRol, $pDominio, $pEstructura);
            }
            $instance = ZendExt_Trace :: getInstance();
            $instance->handle($integracion);
        }
    }

    public function beginTraceBundle($sourceBundle, $targetBundle, $serviceImpl, $method)
    {
        $register = Zend_Registry::getInstance();
        $usuario = $register->session->perfil['usuario'];
        $global = ZendExt_GlobalConcept::getInstance();
        $ip_host = $this->devolverIp();
        $pIdCommonStructure = $global->Estructura->idestructura;
        $pEstructura = $global->Estructura->denominacion;
        $idRol = $register->session->idrol;
        $pRol = $register->session->rol;
        $idDominio = $register->session->perfil['iddominio'];
        $pDominio = $register->session->perfil['dominio'];
        $request = Zend_Controller_Front::getInstance()->getRequest();
        if ($request) {
            $action = $request->getActionName();
            $controller = $request->getControllerName();
        } else {
            $action = 'framework';
            $controller = 'framework';
        }
        $integracion = new ZendExt_Trace_Container_IoC(2, $sourceBundle, $targetBundle, $action, $serviceImpl, $method, $ip_host, $idRol, $idDominio, $usuario, $pIdCommonStructure, $pRol, $pDominio, $pEstructura);
        //echo'<pre33>';print_r($integracion);die;
        $instance = ZendExt_Trace :: getInstance();
        $instance->handle($integracion);
    }

    private function devolverIp()
    {
        if ($_SERVER) {
            if ($_SERVER['HTTP_X_FORWARDED_FOR'])
                $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else {
                if ($_SERVER['HTTP_CLIENT_IP'])
                    $realip = $_SERVER['HTTP_CLIENT_IP'];
                else
                    $realip = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR"))
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            elseif (getenv("HTTP_CLIENT_IP"))
                $realip = getenv("HTTP_CLIENT_IP");
            else
                $realip = getenv("REMOTE_ADDR");
        }
        return $realip;
    }

    private function VerifTraza($reference)
    {
        $flag = true;
        if ($reference == 'traza' || $reference == 'portal')
            $flag = false;
        return $flag;
    }

}
