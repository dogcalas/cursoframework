<?php

class ZendExt_WF_WFExecute_ExecuteWorkFlow implements ezcWorkflowExecutionListener {

    private $xmppHost;
    private $hostName;
    
    public function __construct() {
        if (!$this->initialized) {
            $this->initialize();
        }
        $this->xmppHost = $_SERVER['SERVER_ADDR'];
        $this->hostName = gethostname();
    }

    private $ModelDomainGeneratedAddr;
    private $ModelDomainAddr;
    private $ModelBusinessAddr;
    private $initialized = false;

    private function initialize() {
        $documentRootAddr = $_SERVER['DOCUMENT_ROOT'];

        $preffix = substr($documentRootAddr, 0, strrpos($documentRootAddr, 'web'));
        $this->ModelDomainGeneratedAddr = $preffix . 'lib/ZendExt/WF/models/domain/generated';
        $this->ModelDomainAddr = $preffix . 'lib/ZendExt/WF/models/domain';
        $this->ModelBusinessAddr = $preffix . 'lib/ZendExt/WF/models/bussines';

        Doctrine::loadModels(array($this->ModelDomainGeneratedAddr));
        Doctrine::loadModels(array($this->ModelDomainAddr));
        Doctrine::loadModels(array($this->ModelBusinessAddr));
        
        $this->initialized = TRUE;
    }

    public function start_Execution($wfName = 'Prueba') {
        ///***Operaciones en la BD!!***///
        $definition = new ezcWorkflowDatabaseDoctrineDefinitionStorage();
        $workflow = $definition->loadByName($wfName);
        $execution = new ezcWorkflowDatabaseExecution();
        $execution->workflow = $workflow;
        try {
            return $execution->start();
        } catch (Exception $e) {
            echo 'Execution Start() Error: ';
            print($e->getMessage());
        }
    }

    public function executeWorkflowById($workflowId) {
        if ($this->initialized === FALSE) {
            $this->initialize();
        }
        $definition = new ezcWorkflowDatabaseDoctrineDefinitionStorage();
        $workflow = $definition->loadById($workflowId);

        $execution = new ezcWorkflowDatabaseExecution();

        $execution->workflow = $workflow;

        $execution->addListener($this);
        
        try {
            return $execution->start();
        } catch (Exception $e) {
            echo 'Execution Start Error (executeWorkflowById ExecuteWorkflow.php): ';
            print($e->getMessage());
        }
    }

    public function resume_Execution(Array $inputData) {
        try {
            /*
             * formatear la entrada de la siguiente manera
             * ['executionId'] = 'id de ejecucion'
             * ['workflowId'] = 'id de workflow'
             */
            $executionId = $inputData['executionId'];

            if (!is_int($executionId)) {
                if ($this->isValidInt($executionId)) {
                    $executionId = intval($executionId);
                    $inputData['executionId'] = $executionId;
                }
            }

            $execution = new ezcWorkflowDatabaseExecution($executionId);
            $execution->resume($inputData);
        } catch (Exception $e) {
            echo 'Execution Resume() Error: ';
            print($e->getMessage());
        }
    }

    /*
     * No recuerdo que es lo que tengo que hacer para evitar esto
     * demasiado codigo he tenido que escribir, por dios, comprende
     */

    private function isValidInt($intAsString) {
        if (gettype($intAsString) === 'string') {
            for ($i = 0; $i < strlen($intAsString); $i++) {
                if($intAsString[$i] < '0' && $intAsString[$i] > '9'){
                    print_r('ExecuteWorkflow fn:isValidInt');
                    return false;
                }
            }
            return true;
        }
    }

    public function suspend_Execution($idExecution) {
        try {
            $execution = new ezcWorkflowDatabaseExecution($idExecution);
            $execution->suspend();
        } catch (Exception $e) {
            echo 'Execution Suspend Error (suspend_Execution ExecuteWorkflow.php): ';
            print($e->getMessage());
        }
    }

    public function cancel_Execution($idExecution, ezcWorkflowNode $node) {
        try {
            $execution = new ezcWorkflowDatabaseExecution($idExecution);
            $execution->cancel($node);
        } catch (Exception $e) {
            echo 'Execution Cancel Error (cancel_Execution ExecuteWorkflow.php): ';
            print($e->getMessage());
        }
    }

    public function end_Execution($idExecution, ezcWorkflowNode $node) {
        try {
            $execution = new ezcWorkflowDatabaseExecution($idExecution);
            $execution->end($node);
        } catch (Exception $e) {
            echo 'Execution End Error (end_Execution ExecuteWorkflow.php): ';
            print($e->getMessage());
        }
    }

    public function notify($message, $type = ezcWorkflowExecutionListener::INFO) {
        $typeOfMsg = gettype($message);
        switch ($typeOfMsg) {
            case 'object':
                if ($message instanceof ezcWorkflowExecution) {
                    if ($message->isSuspended()) {
                        $this->doAsStatedWhenSuspended($message);
                    }
                }
                break;
            default:
                break;
        }
    }

    private function doAsStatedWhenSuspended(ezcWorkflowExecution $execution) {
        $executionId = $execution->getId();
        $executionState = ExecutionState::Buscar($executionId); //func. toArray() called

        /*
         * suponiendo que solo haya un nodo input desactivado
         */
        $nodeId = null;
        if (array_key_exists('node_id', $executionState)) {
            $nodeId = $executionState['node_id'];
        }
        $waitingForVariables = $execution->getWaitingFor();

        //Lista de variables que este nodo debe proporcionar
        $variables = array();

        foreach ($waitingForVariables as $key => $variable) {
            $variableName = $key;
            /*
             * variable tiene el formato:
             * [node] => nodeId,
             * [condition] => $condition
             * 
             * donde node es el id del nodo que esta esperando a la
             * variable $variableName y que esta sea de tipo
             * condition. 
             * 
             */
            if ($nodeId === $variable['node']) {
                /*
                 * Hasta este momento, lo que se ha hecho es verificar
                 * que el flujo de trabajo esta detenido porque necesita
                 * una entrada por parte del usuario para continuar, y esa
                 * entrada la debe proporcionar una actividad (nodo) que debe
                 * ser identificado y ha sido identificado en este punto, ya que
                 * executionState tiene como atributos verificables y verificados
                 * idExecution y node_id, a saber, la instancia de ejecucion y el
                 * nodo que demanda la entrada de datos.
                 */

                /*
                 * Adicionamos esta variable a la lista de variables
                 * que este nodo debe proporcionar
                 */
                $variables[$variableName] = $variable;
            }
        }

        //No debe ser necesario verificar, pero por si acaso...
        //Este nodo debe proporcionar variables?
        $process = count($variables) !== 0 ? TRUE : false;

        if ($process) {
            $this->doNodeProcessing($nodeId, $variables, $execution);
        }
    }

    private function doNodeProcessing($nodeId, $variables, ezcWorkflowExecution $execution) {
        //Obtener el nodo que esta suspendiendo la ejecucion
        $node = Node::Buscar($nodeId);

        //Obtenemos la clase a la que pertenece
        $nodeClass = $node->node_class;

        //Obtenemos la configuracion
        $nodeConfiguration = $node->node_configuration;

        //deserializamos la configuracion
        $unserializedConfig = ezcWorkflowDatabaseUtil::unserialize($nodeConfiguration);

        //la mostramos
        //instanciamos
        $_node = new $nodeClass($unserializedConfig);

        /*
         * No todas las configuraciones son iguales, o no al
         * menos desde que se han modificados las clases para
         * adaptarlas a este componente, por lo tanto, las tratamos
         * en funcion de la instancia de clase concreta a la que 
         * pertenece.  
         */

        //todas las funciones empiezan con process
        $funcPreffix = 'process';

        //y terminan con el nombre de la clase
        $fullFuncName = $funcPreffix . $nodeClass;

        //obtendriamos p.ej.: processezcWorkflowUserTask
        $this->$fullFuncName($_node, $variables, $execution);
    }

    private function processezcWorkflowZendActionTask(ezcWorkflowZendActionTask $zendactionTaskNode, $variables, ezcWorkflowExecution $execution) {
        $configuration = $zendactionTaskNode->getConfiguration();

        $zendaction = $zendactionTaskNode->getZendAction();
        
        $idrol = $zendactionTaskNode->getIdRol();

        $systemId = $zendactionTaskNode->getSystemId();
        $controller = $zendactionTaskNode->getController();
        $action = $zendactionTaskNode->getAction();
        
        $idsistema = $zendactionTaskNode->getIdSistema();
        $idfuncionalidad = $zendactionTaskNode->getIdFuncionalidad();
        $denominacion = $zendactionTaskNode->getDenominacion();

        $_configuration = array_diff_key($configuration, array('zendaction' => '', 'idrol' => '', 'SystemId' => '', 'Controller' => '', 'Action' => '', 'IdSistema' => '', 'IdFuncionalidad' => '', 'Denominacion' => ''));

        /*
         * configuration y variables deben tener los mismos valores...
         */

        //probamos que asi sea
        $diff = array_diff_key($_configuration, $variables);
        $error = count($diff) !== 0 ? true : false;
        if (!$error) {
            $forRequesting = $this->prepareVariablesForRequest($zendactionTaskNode, $_configuration);
            //$action = $this->buildURL($action); //*****Implementar
            $forRequesting['data']['zendaction'] = $zendaction;

            $forRequesting['data']['workflowControlVars'] = array('workflowId' => $execution->__get('workflow')->__get('id'), 'executionId' => $execution->getId());
            $forRequesting['data']['zendActionVars'] = array('ZendAction' => '../../../'.$zendaction,'SystemId' => $systemId, 'controller' => $controller, 'accion' => $action, 'IdSistema' => $idsistema, 'IdFuncionalidad' => $idfuncionalidad, 'Denominacion' => $denominacion);
            $this->sendDataRequest(json_encode($forRequesting));
        } else {
            die('errors ocurred');
        }
    }

    private function buildURL($action) {
        //Aqui trabajo con las variables del servidor... y devuelvo la url como cadena?
        print_r($action);
        die('lolo');
    }

    private function processezcWorkflowUserTask(ezcWorkflowUserTask $userTaskNode, $variables) {
        $configuration = $userTaskNode->getConfiguration();
        /*
         * $configuration es un array, pero dado que todo se ha tenido que 
         * modificar, esta configuracion agrego cosas que no debe tener...
         * ezcWorkflowUserTask agrego a su configuracion (for persistence
         * purpose only) la accion del marco de trabajo que se debera invocar(dir)
         * y el usuario o rol que debe ejecutar esta tarea
         * 
         */

        $dir = $userTaskNode->getZendAction();
        $idrol = $userTaskNode->getIdRol();

        $_configuration = array_diff_key($configuration, array('direccion' => '', 'idrol' => ''));

        /*
         * configuration y variables deben tener los mismos valores...
         */

        //probamos que asi sea
        $diff = array_diff_key($_configuration, $variables);
        $error = count($diff) !== 0 ? true : false;

        if (!$error) {
            $jsonEncoded = $this->prepareVariablesForRequest($userTaskNode, $_configuration);
            $this->sendDataRequest($jsonEncoded);
        }
    }

    private function prepareVariablesForRequest($node, $variables) {
        //El tipo de nodo..., que no se pa que lo puse, pero bue...
        $nodeClass = get_class($node);

        $result = array("Type" => '', "data" => null);

        //$data = array('zendaction' => null, 'variables' => array());
        $_data = array();
        
        foreach ($variables as $variableName => $variableType) {
            $_data[] = array($variableName, $variableType->toString());
        }
        switch ($nodeClass) {
            case 'ezcWorkflowUserTask': {
                    $result['Type'] = 'UserInput';
                }break;
            case 'ezcWorkflowZendActionTask': {
                    $result['Type'] = 'ZendAction';
                }break;
        }
        $result['data']['variables'] = $_data;
        return $result;
    }

    private function sendDataRequest($jsonEncoded) {
        /*
         * send notification... somehow
         * 
         * malo malo esto que viene a continuacion
         */
        $xmpp = new ZendExt_Xmpp($this->xmppHost, 5222, 'workflow', 'workflow', $this->hostName, $this->hostName);
        try {
            $xmpp->sendNotification('claudia', $jsonEncoded);
        } catch (Exception $exc) {
            print_r($exc);
            die;
        }
    }

}

?>
