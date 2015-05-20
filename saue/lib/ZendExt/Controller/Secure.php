<?php

/**
 * ZendExt_Controller_Secure
 * Controlador de acciones personalizado e integrado a seguridad
 * 
 * @author Omar Antonio Diaz Pena
 * @package ZendExt
 * @subpackage ZendExt_Controller
 * @copyright UCID-ERP Cuba
 * @version 1.0-0
 */
class ZendExt_Controller_Secure extends Zend_Controller_Action {

    /**
     * Conexion a la BD
     * 
     * @var Doctrine_Conexion
     */
    protected $conn;
    /**
     * Integrador IoC
     */
    protected $integrator;
    /**
     * GlobalConcept
     */
    protected $global;
    /**
     * Nomencladores cargados
     * 
     * @var array of ZendExt_FastResponse_NomLoader
     */
    protected $nomencladores;
    /**
     * Tejedor de aspectos asociados a una accion
     * 
     * @var ZendExt_Weaver 
     */
    private $actionWeaver;
    /**
     * Modulo al cual pertenece el controlador
     * 
     * @var string
     */
    protected $module;
    /**
     * Administrador de transacciones
     * 
     * @var ZendExt_Aspect_TransactionManager
     */
    protected $transactionManager;
    /**
     * Administrador de componentes
     * 
     * @var ZendExt_Component_FactoryProxy
     */
    protected $component;
    
    //yriverog
    protected $isWorkflowTask = false;

    /**
     * Inicializa el controlador
     * 
     * @return void
     */
    public function init($initconn = true) {
        //Inicializo la conexion
        if ($initconn)
            $this->initConnection();

        //Obtengo la instancia del integrador
        $this->integrator = ZendExt_IoC::getInstance();

        //Obtengo la instancia del integrador interno
        $this->pIntegrator = ZendExt_IoC_Inter::getInstance();

        //Obtengo la instancia de component
        $this->component = new ZendExt_Component_FactoryProxy($this);

        //Inicializo el global concept	
        $this->global = ZendExt_GlobalConcept::getInstance();

        //Para evitar que las acciones intente renderear la vista automaticamente
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->removeHelper('viewRenderer');
        Zend_Controller_Front::getInstance()->setParam('noViewRenderer', true);

        //Obtengo una instancia del registro.
        $registro = Zend_Registry::getInstance();

        //Obtengo el idioma y el tema
        $idioma = $this->global->Perfil->idioma;
        $tema = $this->global->Perfil->tema;

        //Obteniendo la direccion de ExtJS y UCID
        $this->view->dir_extjs = $registro->config->idioma->$idioma->extjs_path;
        $this->view->dir_ucid = $registro->config->ucid_path;
        $this->view->dir_ext_ccs = $registro->config->extjs_themes_path . $tema . '/';
    }

    /**
     * Obtiene el perfil del usuario
     * 
     * @throws ZendExt_Exception - dispara excepcion declarada en el xml de excepciones
     * @return array - devuelve los datos del usuario y de su perfil
     */
    public function getPerfil() {
        return $this->global->Perfil;
    }
/**
     * Renderiza la vista pasandole variables en un arreglo
     * 
     */
    public function bootstrapRender($array, $script=null){
        $view   = $this->initView();
        foreach ($array as $key => $value) {
           $view->$key=$value;
        }
        if(!$script)
            $script = $this->getViewScript();
        $this->getResponse()->appendBody(
            $view->render($script),
            $name
        );

    }
    /**
     * Imprime las etiquetas para una vista en formato json
     * 
     * @throws ZendExt_Exception - dispara excepcion declarada en el xml de excepciones
     * @return void
     */
    public function cargaretiquetasAction() {
        $controller = $this->_request->getControllerName();
        $vista = $this->_request->getPost('vista');
        if (!$vista)
            $vista = $controller;
        $perfil = $this->getPerfil();

        $modulo_path = Zend_Registry::getInstance()->config->modulo_path;
        $file = "{$modulo_path}views/idioma/{$perfil->idioma}/$controller/$vista.json";
        if (file_exists($file)) {
            if (is_readable($file))
                echo file_get_contents($file);
            else
                throw new ZendExt_Exception('E011');
        }
        else
            throw new ZendExt_Exception('E010');
    }

    /**
     * Imprime un mensaje en formato json
     * 
     * @param string $msg - mensaje
     * @return void
     */
    protected function showMessage($msg) {
        echo "{'codMsg':1,'mensaje': '$msg'}";
    }

    /**
     * Cierra la session del usuario al salir del portal
     * 
     * @return void
     */
    public function closeportalAction() {
        $registro = Zend_Registry::getInstance();
        $registro->session->unsetAll();
        $registro->session->close = true;
        echo json_encode(array('close' => true));
    }

    /**
     * Precondicion que se dispara antes de executarse la accion
     * 
     * @return void
     */
    public function preDispatch() {  
        $this->checkWorkflowTask($this->getRequest());
        $this->initActionWeaver();
        if (isset($this->actionWeaver)) {
            $this->actionWeaver->preAction();
        }
    }

    /**
     * Postcondicion que se dispara despues de executarse la accion
     * 
     * @return void
     */
    public function postDispatch() {
        if ($this->isWorkflowTask) {
            if ($this->getResponse()->getHttpResponseCode() == 200) { 
                $action = $this->getRequest()->getParam('accion');
                if ($action === $this->getRequest()->getActionName()) {
                    $wfe = new ZendExt_WF_WFExecute_ExecuteWorkFlow();
                    $workflowId = $this->getRequest()->getParam('workflowId');
                    $executionId = $this->getRequest()->getParam('executionId');
                    $wfe->resume_Execution(array('executionId' => $executionId, 'workflowId' => $workflowId));
                    $this->isWorkflowTask = false;
                }  
            }
        }
        if (isset($this->actionWeaver)) {
            $this->actionWeaver->postAction();
        }
    }

    /**
     * Inicializa el tejedor de la accion, para que los aspectos 
     * asociados a la accion sean disparados
     * 
     * @return void
     */
    private function initActionWeaver() {
        if (!$this->module)
            $this->setModule();
        $controller = ucfirst($this->_request->getControllerName()) . 'Controller';
        $action = $this->_request->getActionName() . 'Action';
        $weaver = ZendExt_Weaver::getInstance();
        //$this->actionWeaver = $weaver->{$this->module}->$controller->$action;
        $module_reference = Zend_Registry::get('config')->module_reference;
        $arrModuleRef = explode('/', $module_reference);
        foreach ($arrModuleRef as $arrFolder) {
            $weaver = $weaver->$arrFolder;
        }
        $this->actionWeaver = $weaver->$controller->$action->init();
    }

    /**
     * Inicializa el modulo al cual pertenece el controlador
     * 
     * @return void
     */
    public function setModule($module = null) {
        if (isset($module))
            $this->module = $module;
        elseif (!$this->module)
            $this->module = Zend_Registry::get('config')->module_name;
    }

    /**
     * Ejecuta o despacha la accion
     *
     * @param string $action - Nombre de la accion
     * @return void
     */
    public function dispatch($action) {
        try {
            // Notify helpers of action preDispatch state
            $this->_helper->notifyPreDispatch();
            $this->preDispatch();
            if ($this->getRequest()->isDispatched()) {
                // preDispatch() didn't change the action, so we can continue                
                $this->$action();                     
                $this->postDispatch();
            }
            // whats actually important here is that this action controller is
            // shutting down, regardless of dispatching; notify the helpers of this
            // state
            $this->_helper->notifyPostDispatch();
        } catch (Exception $e) {
            $this->failedDispatch($e);
        }
    }
    
    private function checkWorkflowTask(Zend_Controller_Request_Abstract $_request) {
        if($_request->getParam('isWorkflowTask')){
            $this->isWorkflowTask = true;
        }
    }

    /**
     * Se ejecuta cuando ocurre un error durante la ejecucion de la accion
     * 
     * @return void
     */
    public function failedDispatch($e) {
        if (isset($this->actionWeaver))
            $this->actionWeaver->failedAction($e);
    }

    /**
     * Inicializa la conexion del controlador
     * 
     * @param string $module - Modulo al que pertenece el controlador
     * @return void
     */
    public function initConnection($module = null) {
        $this->setModule($module);
        $transactionManager = ZendExt_Aspect_TransactionManager::getInstance();
        $this->conn = $transactionManager->openConections($this->module);
    }

    /**
     * Inicializa el administrador de transacciones
     * 
     * @return void
     */
    public function initTransactionManager() {
        $this->transactionManager = ZendExt_Aspect_TransactionManager::getInstance();
    }

    public function cargaraccionesAction() {
        $idfuncionalidad = $this->_request->getPost('idfuncionalidad');
        $global = ZendExt_GlobalConcept::getInstance();
        $identidad = $global->Estructura->idestructura;
        $certificado = Zend_Registry::get('session')->certificado;
        $integrator = ZendExt_IoC::getInstance();
        $acciones = $integrator->seguridad->ObtenerAcciones($certificado, $idfuncionalidad, $identidad);
        if ($acciones)
            echo json_encode($acciones);
        else
            echo '[]';
    }

}
