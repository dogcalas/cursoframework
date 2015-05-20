<?php

/**
 * File containing the ezcWorkflowDatabaseExecution class.
 *
 * @package WorkflowDatabaseTiein
 * @version 1.3.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Workflow executer that suspends and resumes workflow
 * execution states to and from a database.
 *
 * @package WorkflowDatabaseTiein
 * @version 1.3.1
 */
class ezcWorkflowDatabaseExecution extends ezcWorkflowExecution {
    /**
     * ezcDbHandler instance to be used.
     *
     * @var ezcDbHandler
     */
    //protected $db;

    /**
     * Flag that indicates whether the execution has been loaded.
     *
     * @var boolean
     */
    protected $loaded = false;

    /**
     * Container to hold the properties
     *
     * @var array(string=>mixed)
     */
    protected $properties = array(
        'definitionStorage' => null,
        'workflow' => null,
        'options' => null
    );

    /*
     * yriverog
     */
    private $ModelDomainGeneratedAddr;// = "../../../lib/ZendExt/WF/models/domain/generated";
    private $ModelDomainAddr;// = "../../../lib/ZendExt/WF/models/domain";
    private $ModelBusinessAddr;// = "../../../lib/ZendExt/WF/models/bussines";
    private $initialized = false;
    private $connection = null;

    /**
     * Construct a new database execution.
     *
     * This constructor is a tie-in.
     *
     * @param  ezcDbHandler $db
     * @param  int          $executionId
     * @throws ezcWorkflowExecutionException
     */
    public function __construct($executionId = null) {
        if ($executionId !== null && !is_int($executionId)) {
            throw new ezcWorkflowExecutionException('$executionId must be an integer.');
        }

        $this->properties['definitionStorage'] = new ezcWorkflowDatabaseDoctrineDefinitionStorage();

        if (!$this->initialized) {
            $this->initialize();
        }

        /*
         * Quien implemento esto, supongo que dio por sentado
         * que toda instancia de ejecucion tiene asociado
         * un workflow.... supongo
         * 
         * Otra cosa: no recuerdo si lo hice yo
         */
        if (is_int($executionId)) {
            $this->loadExecution($executionId);
        }
    }

    /*
     * yriverog
     */

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

    /**
     * Property get access.
     *
     * @param string $propertyName
     * @return mixed
     * @throws ezcBasePropertyNotFoundException
     *         If the given property could not be found.
     * @ignore
     */
    public function __get($propertyName) {
        switch ($propertyName) {
            case 'definitionStorage':
            case 'workflow':
                return $this->properties[$propertyName];
        }

        throw new ezcBasePropertyNotFoundException($propertyName);
    }

    /**
     * Property set access.
     *
     * @param string $propertyName
     * @param string $propertyValue
     * @throws ezcBasePropertyNotFoundException
     *         If the given property could not be found.
     * @throws ezcBaseValueException
     *         If the value for the property options is not an ezcWorkflowDatabaseOptions object.
     * @ignore
     */
    public function __set($propertyName, $propertyValue) {
        switch ($propertyName) {
            case 'definitionStorage':
            case 'workflow':
                return parent::__set($propertyName, $propertyValue);
            default:
                throw new ezcBasePropertyNotFoundException($propertyName);
        }
        $this->properties[$propertyName] = $propertyValue;
    }

    /**
     * Property isset access.
     *
     * @param string $propertyName
     * @return bool
     * @ignore
     */
    public function __isset($propertyName) {
        switch ($propertyName) {
            case 'definitionStorage':
            case 'workflow':
                //case 'options':
                return true;
        }

        return false;
    }

    /**
     * Start workflow execution.
     *
     * @param  int $parentId
     * @throws ezcDbException
     */
    protected function doStart($parentId) {
        $execution = new Execution();

        $execution->workflow_id = (int) $this->workflow->id;
        $execution->execution_parent = (int) $parentId;
        $execution->execution_started = time();
        $execution->execution_variables = ezcWorkflowDatabaseUtil::serialize($this->variables);
        $execution->execution_waiting_for = ezcWorkflowDatabaseUtil::serialize($this->waitingFor);
        $execution->execution_threads = ezcWorkflowDatabaseUtil::serialize($this->threads);
        $execution->execution_next_thread_id = (int) $this->nextThreadId;
        
        $execution->save();
        
        //esto no lo hice yo, que feo esta....
        $this->id = Execution::obtenerUltimoId();
    }

    /**
     * Suspend workflow execution.
     *
     * @throws ezcDbException
     */
    protected function doSuspend() {
        //$execution = new Execution();
        $execution = Execution::Buscar($this->id);

        $execution->execution_variables = ezcWorkflowDatabaseUtil::serialize($this->variables);
        $execution->execution_waiting_for = ezcWorkflowDatabaseUtil::serialize($this->waitingFor);
        $execution->execution_threads = ezcWorkflowDatabaseUtil::serialize($this->threads);
        $execution->execution_next_thread_id = (int) $this->nextThreadId;
        
        $execution->save();
        
        foreach ($this->activatedNodes as $node) {
            $execution_state = new ExecutionState();
            $execution_state->execution_id = $this->id;
            $execution_state->node_id = (int) $node->getId();
            $execution_state->node_state = ezcWorkflowDatabaseUtil::serialize($node->getState());
            $execution_state->node_activated_from = ezcWorkflowDatabaseUtil::serialize($node->getActivatedFrom());
            $execution_state->node_thread_id = (int) $node->getThreadId();
            $execution_state->save();
        }
    }

    /**
     * Resume workflow execution.
     *
     * @throws ezcDbException
     */
    protected function doResume() {
        $this->cleanupTable('execution_state');
    }

    /**
     * End workflow execution.
     *
     * @throws ezcDbException
     */
    protected function doEnd() {
        $this->cleanupTable('execution');
        $this->cleanupTable('execution_state');
    }

    /**
     * Returns a new execution object for a sub workflow.
     *
     * @param  int $id
     * @return ezcWorkflowExecution
     */
    protected function doGetSubExecution($id = null) {
        return new ezcWorkflowDatabaseExecution($this->db, $id);
    }

    /**
     * Cleanup execution / execution_state tables.
     *
     * @param  string $tableName
     * @throws ezcDbException
     */
    protected function cleanupTable($tableName) {

        if ($tableName == 'execution') {
            Execution::eliminarExecution($this->id);
        } else if ($tableName == 'execution_state') {
            ExecutionState::eliminarExecutionState($this->id);
        }
        else
            die("execution");
    }

    /**
     * Load execution state.
     *
     * @param int $executionId  ID of the execution to load.
     * @throws ezcWorkflowExecutionException
     */
    protected function loadExecution($executionId) { 
        $result = Execution::getDatos($executionId);  
        if ($result === false || empty($result)) {
            throw new ezcWorkflowExecutionException(
                    'Could not load execution state.'
            );
        }
        $this->id = $executionId;
        $this->nextThreadId = $result[0]['execution_next_thread_id'];
        $this->threads = ezcWorkflowDatabaseUtil::unserialize($result[0]['execution_threads']);
        $this->variables = ezcWorkflowDatabaseUtil::unserialize($result[0]['execution_variables']);
        $this->waitingFor = ezcWorkflowDatabaseUtil::unserialize($result[0]['execution_waiting_for']);
        $workflowId = $result[0]['workflow_id'];
        $this->workflow = $this->properties['definitionStorage']->loadById($workflowId);


        $result = ExecutionState::getDatos($executionId);

        $activatedNodes = array();

        foreach ($result as $row) {
            $activatedNodes[$row['node_id']] = array('state' => $row['node_state'], 'activated_from' => $row['node_activated_from'],
                'thread_id' => $row['node_thread_id']
            );
        }

        foreach ($this->workflow->nodes as $node) {
            $nodeId = $node->getId();

            if (isset($activatedNodes[$nodeId])) {
                $node->setActivationState(ezcWorkflowNode::WAITING_FOR_EXECUTION);
                $node->setThreadId($activatedNodes[$nodeId]['thread_id']);
                $node->setState(ezcWorkflowDatabaseUtil::unserialize($activatedNodes[$nodeId]['state'], null));
                $node->setActivatedFrom(ezcWorkflowDatabaseUtil::unserialize($activatedNodes[$nodeId]['activated_from']));

                $this->activate($node, false);
            }
        }

        $this->cancelled = false;
        $this->ended = false;
        $this->loaded = true;
        $this->resumed = false;
        $this->suspended = true;
    }

}

?>
