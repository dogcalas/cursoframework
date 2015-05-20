<?php

class ZendExt_WF_WFTranslatoreZC_XPDLParserController {

    private $workflowNodes = array();
    //optionally...
    private $activities = array();
    private $transitions = null;

    /*
     * added by me
     */
    private $wfpdManager;
    private $ModelDomainGeneratedAddr; // = "../../../lib/ZendExt/WF/models/domain/generated";
    private $ModelDomainAddr; // = "../../../lib/ZendExt/WF/models/domain";
    private $ModelBusinessAddr; // = "../../../lib/ZendExt/WF/models/bussines";
    private $objPackage;
    private $dataFields;
    //27/8/2013
    private $initialized = false;

    //quien hizo esto?
    public function init() {
        parent::init();
    }

    public function __construct($_objPackage) {
        $this->objPackage = $_objPackage;

        if (!$this->initialized) {
            $this->initialize();
        }
    }

    private function addWorkflowNode($_workflowNode) {
        if (!array_key_exists($_workflowNode->getId(), $this->workflowNodes)) {
            $this->workflowNodes[$_workflowNode->getId()] = $_workflowNode;
            return TRUE;
        } else {
            throw new Exception('Ya existe el nodo.');
        }
    }

    private function initialize() {
        $documentRootAddr = $_SERVER['DOCUMENT_ROOT'];

        $preffix = substr($documentRootAddr, 0, strrpos($documentRootAddr, 'web'));
        $this->ModelDomainGeneratedAddr = $preffix . 'lib/ZendExt/WF/models/domain/generated';
        $this->ModelDomainAddr = $preffix . 'lib/ZendExt/WF/models/domain';
        $this->ModelBusinessAddr = $preffix . 'lib/ZendExt/WF/models/bussines';

        Doctrine::loadModels(array($this->ModelDomainGeneratedAddr));
        Doctrine::loadModels(array($this->ModelDomainAddr));
        Doctrine::loadModels(array($this->ModelBusinessAddr));

        //27/8/2013.... next 3 lines are not needed?
        $this->initialized = TRUE;
    }

    public function parse() {
        $this->queryPackage();
    }

    public function get_workflow() {
        return $this->workflow;
    }

    public function set_workflow($work) {
        $this->workflow = $work;
    }

    private function queryPackage() {
        /*
         * Ignore package-related information
         */
        $workflowProcessDefinitions = $this->objPackage->getWorkflowProcesses();
        $this->dataFields = $this->objPackage->getDataFields()->clonar();
        $this->queryWorkflowProcessDefinitions($workflowProcessDefinitions);
    }

    private function queryWorkflowProcessDefinitions($workflowProcessDefinitions) {

        /*
         * Assuming there is only one workflow process definition
         */
        $workflowProcessDefinition = NULL;
        for ($i = 0; $i < $workflowProcessDefinitions->count(); $i++) {
            $workflowProcessDefinition = $workflowProcessDefinitions->get($i);
            $this->buildWorkflowProcessDefinition($workflowProcessDefinition);
            break;
        }
    }

    private function buildWorkflowProcessDefinition($workflowprocessdefinition) {
        $startNode = NULL;
        $this->queryWorkflowProcessDefinition($workflowprocessdefinition);

        if ($this->wfpdManager->hasStartEvent()) {
            $startEvent = $this->wfpdManager->getStartEvent();

            $startNode = $this->findNode($startEvent->getId());
            //Eliminar este nodo startNode de la lista de nodos this->workflowNodes?
            /* $arrayKeys = array_keys($this->workflowNodes);
              $metNodeIndex = array_search($startNode, $arrayKeys);
              array_splice($this->workflowNodes, $metNodeIndex, 1);

              $this->createOutgoingTransitions($startEvent, $startNode->getId()); */
        } else {
            $startActivities = $this->wfpdManager->getStartActivities();
            if (count($startActivities) > 1) {
                $startNode = $this->createParallelSplitNode(NULL, NULL);
                $this->createOutgoingTransitions($startActivities, $startNode->getId());
            } else {
                $startNode = $this->workflowNodes[$startActivities[0]->getId()];
            }
        }

        $workflow = new ezcWorkflow($workflowprocessdefinition->getName(), $startNode);
        $workflow->__set('id', $workflowprocessdefinition->getId());

        /*
         * All workflow nodes are connected through transitions
         * 
         * P: set of nodes
         * T: transitions between nodes(P ^ T = empty)
         */
        for ($i = 0; $i < $this->transitions->count(); $i++) {
            $transition = $this->transitions->get($i);
            $from = $transition->getFrom();
            $to = $transition->getTo();
            $nodeFrom = $this->findNode($from);
            $nodeTo = $this->findNode($to);
            $nodeFrom->addOutNode($nodeTo);
        }
        //mas adelante
        /* $unconnectedNodes = $this->findOutgoingNodesUnconnected($workflow);
          foreach ($unconnectedNodes as $unconnectedNode) {
          if ($unconnectedNode instanceof ezcWorkflowNode) {
          $unconnectedNode->addOutNode(new ezcWorkflowNodeEnd());
          }
          } */

        $db = new ezcWorkflowDatabaseDoctrineDefinitionStorage();
        try {
            $db->save($workflow);
        } catch (Exception $exc) {
            throw $exc;
        }



        /*
         * Asumamos que la primera actividad en la lista de nodos
         * es la primera actividad en la lista de actividades de 
         * la definicion de procesos
         */

        /* $workflow->startNode->addOutNode($this->workflowNodes[0]);

          $execute = new ezcWorkflowExecutionNonInteractive();
          $execute->__set('workflow',$workflow);
          $execute->start();

          $workflow = new ezcWorkflow('lolo');
          $userServiceObject = new ezcWorkflowUserTask();

          $actionNode = new ezcWorkflowNodeAction($userServiceObject);
          $workflow->startNode->addOutNode($actionNode);

          $execute = new ezcWorkflowExecutionNonInteractive();
          $execute->__set('workflow',$workflow);

          $execute->start(); */
    }

    /*
     * Encuentra los nodos que no tienen flujos de secuencia saliente...
     * En principio, todas las definiciones de flujos de trabajo deben 
     * tener un unico evento de inicio y (a diferencia de lo que se define
     * en BPMN) un unico evento de fin. Los nodos que no tienen salidas, se
     * le deben agregar eventos de fin?????
     */

    private function findOutgoingNodesUnconnected(ezcWorkflow $workflow) {
        /*
         * Los nodos que no tienen salida
         */
        $_unconnectedNodes = array();
        foreach ($workflow->nodes as $workflowNode) {
            if ($workflowNode instanceof ezcWorkflowNode) {
                $outgoingNodes = $workflowNode->getOutNodes();
                if (count($outgoingNodes) === 0) {
                    $_unconnectedNodes[] = $workflowNode;
                }
            }
        }
        /*
         * Asumiendo que al terminar de armar el workflow, los nodos que 
         * no estan conectados deben ser conectados a nodos de fin...
         * 
         * Mas adelante se deben hacer correcciones que permitan no asumir
         * estas suposiciones como ciertas y absolutas
         */
        return $_unconnectedNodes;
    }

    private function createOutgoingTransitions($targetActivities, $sourceNodeId) {
        if (is_array($targetActivities)) {
            foreach ($targetActivities as $activity) {
                $transition = new ZendExt_WF_WFObject_Entidades_Transition(NULL);
                $transitionId = $this->generateRandomId();
                $transition->setId($transitionId);
                $transition->setTo($activity->getId());
                $transition->setFrom($sourceNodeId);

                $this->addTransition($transition);
            }
        } else {
            $targetActivities = array($targetActivities);
            $this->createOutgoingTransitions($targetActivities, $sourceNodeId);
        }
    }

    private function findNode($nodeId) {
        $result = null;
        if (array_key_exists($nodeId, $this->workflowNodes)) {
            $result = $this->workflowNodes[$nodeId];
        }
        return $result;
    }

    private function findConnectingTransition($sourceNodeId) {
        $transitions = array();
        for ($i = 0; $i < $this->transitions->count(); $i++) {
            $transition = $this->transitions->get($i);
            if ($transition->getFrom() === $sourceNodeId) {
                $transitions[] = $transition;
            }
        }
        return $transitions;
    }

    private function findOutgoingNodes($transitionId) {
        $nodes = array();
        for ($i = 0; $i < $this->transitions->count(); $i++) {
            $transition = $this->transitions->get($i);
            if ($transition->getId() === $transitionId) {
                $nodeId = $transition->getTo();
                if (array_key_exists($nodeId, $this->workflowNodes)) {
                    $nodes[] = $this->workflowNodes[$nodeId];
                }
            }
        }
        return $nodes;
    }

    private function queryWorkflowProcessDefinition($workflowProcessDefinition) {
        $this->wfpdManager = new ZendExt_WF_WFObject_Managers_WFDManager();
        $this->wfpdManager->addWorkflowProcessDefinition($workflowProcessDefinition);

        $activities = $this->wfpdManager->getActivities();
        $this->transitions = $this->wfpdManager->getTransitions()->clonar();


        $this->activities = $activities;
        //$this->transitions = $transitions;

        /*
         * Parece que ahora podemos empezar a usar lo que antes habia hecho
         */

        $this->processActivities($activities);
    }

    private function processActivities($_activities) {
        //$actividades = array();
        for ($i = 0; $i < $_activities->count(); $i++) {
            $_activity = $_activities->get($i);
            $this->parseActivity($_activity);
        }

        /*
         * Probando...
         */
        /* $workflow = new ezcWorkflow('lolo');
          $userServiceObject = new ezcWorkflowUserTask();

          $actionNode = new ezcWorkflowNodeAction($userServiceObject);
          $workflow->startNode->addOutNode($actionNode);

          $execute = new ezcWorkflowExecutionNonInteractive();
          $execute->__set('workflow',$workflow);

          $execute->start(); */
    }

    private function parseActivity($_activity) {
        /*
         * the type of activity
         */
        $_activityType = $_activity->getActivityType()->getSelectedItem();
        $_activityTypeToString = $_activityType->getTagName();

        /*
         * the type of activity should be parsed
         * in order to get the information each 
         * case contains
         */
        $funcPreffix = 'parse';
        $fullFuncName = $funcPreffix . $_activityTypeToString;

        /*
         * calling the parser function
         */
        $activity = $this->$fullFuncName($_activityType);

        /*
         * Setting common attribs values to activities.
         * 
         * For debugging purpose only, one attrib is being tested.
         */
        $id = $_activity->getId();
        $activity->setId($id);

        /*
         * Save this node, which is a workflowNode
         * so to say.
         */
        $this->addWorkflowNode($activity);

        /*
         * activities may also have transition restrictions, such as
         * splitting and/or joining
         */
        //$_transitionRestrictions = $_activity->getTransitionRestrictions()->isEmpty() === TRUE ? NULL : $_activity->getTransitionRestrictions();
        $_transitionRestrictions = $this->wfpdManager->getTransitionRestrictions($id);
        if ($_transitionRestrictions !== NULL) {
            for ($i = 0; $i < $_transitionRestrictions->count(); $i++) {
                $_transitionRestriction = $_transitionRestrictions->get($i);
                if (!$_transitionRestriction->isNullSplit()) {

                    $split = $_transitionRestriction->getSplit();
                    $_transitions = $this->wfpdManager->getTransitionsById($split->getTransitionRefs());

                    $transitionsChecking = $this->doTransitionsChecking($_transitions);
                    $addedNode = NULL;
                    switch ($transitionsChecking) {
                        case 0:
                            $addedNode = $this->createParallelSplitNode($_activity);
                            break;
                        case 1:
                            $addedNode = $this->createExclusiveChoiceNode();
                            break;
                        case 2:
                            $addedNode = $this->createExclusiveChoiceNode(TRUE);
                            break;
                        default:
                            break;
                    }

                    foreach ($_transitions as $_transition) {
                        $thisTransition = $this->transitions->findById($_transition->getId());
                        $thisTransition->setFrom($addedNode->getId());
                    }
                }
                if (!$_transitionRestriction->isNullJoin()) {
                    $_transitions = $this->wfpdManager->findTransitionsToNode($_activity->getId());
                }
            }
        }
    }

    private function generateRandomId() {
        $genId = '';
        for ($i = 0; $i < 30; $i++) {
            $randCharType = rand(0, 2);
            /*
             * 0: numbers
             * 1: lowercase letters
             * 2: uppercase letters
             */
            switch ($randCharType) {
                case 0:
                    $genId = $genId . (string) rand(0, 9);
                    break;
                case 1:
                    $genId = $genId . chr(rand(97, 122));
                    break;
                case 2:
                    $genId = $genId . chr(rand(65, 90));
                    break;
            }
        }
        return $genId;
    }

    private function addTransition($_transition) {
        if ($this->transitions->findById($_transition->getId()) === NULL) {
            $this->transitions->add($_transition);
            return TRUE;
        }
        return FALSE;
    }

    private function createParallelSplitNode($source, $splitNode = NULL) {
        $parallelSplit = new ezcWorkflowNodeParallelSplit();
        if ($splitNode === NULL) {
            /*
             * Revisar bien esto...
             */
            $id = $this->generateRandomId();
            $parallelSplit->setId($id);

            /*
             * if splitnode == null, then it should be created 
             * and added as a new activity. The transition that
             * link this node from the source node should also
             * be created since there is no transition that links them
             */
            $transition = new ZendExt_WF_WFObject_Entidades_Transition(NULL);

            $transitionId = $this->generateRandomId();
            $transition->setId($transitionId);
            $transition->setTo($id);

            if ($source !== NULL) {
                $transition->setFrom($source->getId());
            }

            $this->addTransition($transition);
        } else {
            $parallelSplit->setId($splitNode->getId());
        }
        $this->addWorkflowNode($parallelSplit);
        return $parallelSplit;
    }

    private function createExclusiveChoiceNode($default = FALSE) {
        if (isset($default)) {
            print_r('default transition has been set.');
        }
    }

    private function doTransitionsChecking($_transitions) {
        /*
         * $_transitions is an array
         */
        $counterDefault = 0;
        $counterExpression = 0;
        $counterNone = 0;

        $failed = FALSE;

        foreach ($_transitions as $_transition) {
            switch ($_transition->getConditionType()) {
                case 'NONE':
                    $counterNone++;
                    break;
                case 'CONDITION':
                    $counterExpression++;
                    break;
                case 'OTHERWISE':
                    $counterDefault++;
                    break;
                default:
                    $failed = TRUE;
                    break;
            }
            if ($failed === TRUE) {
                throw new Exception('Condition Type is invalid.');
            }
        }
        /*
         * Agreement:
         * 0: all transitions are condition_type = None;
         * 1: all transitions are condition_type = Condition;
         * 2: transitions are conditional and one by default
         */
        $countTransitions = count($_transitions);
        if ($countTransitions === $counterNone) {
            return 0;
        }
        if ($countTransitions === $counterExpression) {
            return 1;
        }
        if ($counterNone > 0 && ($counterExpression != 0 || $counterDefault != 0)) {
            throw new Exception('Las transiciones o bien son todas None o todas Expression');
        } elseif ($counterDefault > 1) {
            throw new Exception('Solo puede haber una transicion por defecto.');
        } elseif ($counterExpression + $counterDefault !== $countTransitions) {
            throw new Exception('Las transiciones tienen que ser todas condicionales y solo una por defecto.');
        } else {
            return 2;
        }
    }

    private function parseImplementation($_implementation) {
        $returnObject = NULL;
        /*
         * Implementation may be one of the following:
         * [No|Tool|Task|Subflow|Reference].
         * All of them are BPMN objects.
         */
        $_implementationType = $_implementation->getSelectedItem();

        /*
         * getting only the name of the type of implementation,
         * this is, 'No', 'Tool',...,
         */
        $_implementationTypeToString = $_implementationType->getTagName();

        /*
         * switching each concrete case for handling
         */
        switch ($_implementationTypeToString) {
            case 'No':
                break;
            case 'Tool':
                break;
            case 'Task': {
                    $returnObject = $this->parseTask($_implementationType);
                }
                break;
            case 'Subflow':
                break;
            case 'Reference':
                break;
            default:
                break;
        }

        return $returnObject;
    }

    private function parseRoute($route) {
        print_r('parseRoute');
    }

    private function parseEvent($event) {
        $eventType = $event->getEventType()->getSelectedItem();
        $eventTypeAsString = $eventType->getTagName();
        $returnResult = NULL;
        /*
         * Ignorando el trigger de cualquier evento de inicio, intermedio o de fin
         */
        switch ($eventTypeAsString) {
            case 'StartEvent':
                $returnResult = new ezcWorkflowNodeStart();
                break;
            case 'IntermediateEvent':
                break;
            case 'EndEvent':
                $returnResult = new ezcWorkflowNodeEnd();
                break;
        }
        return $returnResult;
    }

    private function parseTask($task) {
        $returnObject = NULL;
        /*
         * Tasks may be one of the following:
         * [TaskUser|TaskManual|TaskService|...]
         * All of them are BPMN objects.
         */
        $taskType = $task->getTaskType()->getSelectedItem();

        /*
         * getting only the name of the type of task,
         * this is, 'TaskUser', 'TaskManual',...,
         */
        $taskTypeToString = $taskType->getTagName();
        /*
         * switching each concrete case for handling
         */
        switch ($taskTypeToString) {
            case 'TaskService': {
                    
                }
                break;
            case 'TaskIOCService': {
                    /*
                     * check again, just in case
                     */
                    if ($taskType instanceof ZendExt_WF_WFObject_Entidades_TaskIOCService) {
                        $iocServiceObject = new ezcWorkflowIOCServiceTask();
                        $returnObject = new ezcWorkflowNodeAction($iocServiceObject);
                    } else {
                        throw new Exception('Unknown...');
                    }
                }
                break;
            case 'TaskZendAction': {
                    $taskZendActionConfig = $this->buildZendActionTaskConfig($taskType);
                    $returnObject = new ezcWorkflowZendActionTask($taskZendActionConfig);
                }
                break;
            case 'TaskReceive': {
                    
                }
                break;
            case 'TaskManual': {
                    
                }
                break;
            case 'TaskReference': {
                    
                }
                break;
            case 'TaskScript': {
                    
                }
                break;
            case 'TaskSend': {
                    
                }
                break;
            case 'TaskUser': {
                    //Esta en vez de TaskZendAction
                    $taskUserConfig = $this->buildUserTaskConfig($taskType);
                    $returnObject = new ezcWorkflowUserTask($taskUserConfig);
                }
                break;
            case 'TaskApplication': {
                    
                }
                break;
            default:
                break;
        }
        return $returnObject;
    }

    private function buildUserTaskConfig($_taskType) {
        $_inputVariables = $_taskType->getVariables()->getOutputVariables();
        $configuration = array();
        //Basic Types are: int, string, float, boolean,...
        /*
         * ezcWorkflowConditions are:
         * 
         * ezcWorkflowConditionIsInteger,
         * ezcWorkflowConditionIsString,
         * ezcWorkflowConditionIsFloat,
         * ezcWorkflowConditionIsBool
         * 
         * respectively 
         */

        foreach ($_inputVariables as $inputVariable) {
            //Instances of ZendExt_WF_WFObject_Entidades_VariableEntrada
            $type = $inputVariable->getTipo();
            $condition = null;
            switch ($type) {
                case 'string':
                    $condition = new ezcWorkflowConditionIsString();
                    break;
                case 'integer':
                    $condition = new ezcWorkflowConditionIsInteger();
                    break;
                case 'decimal':
                    $condition = new ezcWorkflowConditionIsFloat();
                    break;
            }
            $variableName = $inputVariable->getCampo();
            $configuration[$variableName] = $condition;
        }

        $configuration['direccion'] = $_taskType->getZendAction();
        $configuration['idrol'] = $_taskType->getIdRol();
        return $configuration;
    }

    private function buildZendActionTaskConfig($_taskType) {
        $_configuracion = array(
            'zendaction' => $_taskType->getZendAction(),
            'idrol' => $_taskType->getIdRol(),
            'SystemId' => $_taskType->getSystemId(),
            'Controller' => $_taskType->getController(),
            'Action' => $_taskType->getAction(),
            'IdSistema' => $_taskType->getIdSistema(),
            'IdFuncionalidad' => $_taskType->getIdFuncionalidad(),
            'Denominacion' => $_taskType->getDenominacion(),
        );
        $variablesConfiguration = array(
            'workflowId' => new ezcWorkflowConditionIsInteger(),
            'executionId' => new ezcWorkflowConditionIsInteger()
        );

        $configuracion = array_merge($_configuracion, $variablesConfiguration);
        return $configuracion;
    }

}
?>

