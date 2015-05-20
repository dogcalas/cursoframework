<?php

/**
 * File containing the ezcWorkflowDatabaseDefinitionStorage class.
 *
 * @package WorkflowDatabaseTiein
 * @version 1.3.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Workflow definition storage handler that saves and loads workflow
 * definitions to and from a database.
 *
 * @package WorkflowDatabaseTiein
 * @version 1.3.1
 */
class ezcWorkflowDatabaseDoctrineDefinitionStorage implements ezcWorkflowDefinitionStorage {

    /**
     * ezcDbHandler instance to be used.
     *
     * @var ezcDbHandler
     */
    //  protected $db;
    /*
     * yriverog
     */
    private $ModelDomainGeneratedAddr;// = "../../../lib/ZendExt/WF/models/domain/generated";
    private $ModelDomainAddr;// = "../../../lib/ZendExt/WF/models/domain";
    private $ModelBusinessAddr;// = "../../../lib/ZendExt/WF/models/bussines";
    private $initialized = false;
    
    public function __construct() {
        if (!$this->initialized) {
            $this->initialize();
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
        
        $this->initialized = TRUE;
    }

    /**
     * Load a workflow definition by ID.
     *
     * Providing the name of the workflow that is to be loaded as the
     * optional second parameter saves a database query.
     *
     * @param  int $workflowId
     * @param  string  $workflowName
     * @param  int $workflowVersion
     * @return ezcWorkflow
     * @throws ezcWorkflowDefinitionStorageException
     * @throws ezcDbException
     */
    public function loadById($workflowId, $workflowName = '', $workflowVersion = 0) {
        if (empty($workflowName) || $workflowVersion == 0) {
            $datos = WorkFlow::getWorkFlowIdWorkFlow($workflowId);
            if (isset($datos[0])) {
                $workflowName = $datos[0]['workflow_name'];
                $workflowVersion = $datos[0]['workflow_version'];
            } else {
                throw new ezcWorkflowDefinitionStorageException('Could not load workflow definition.');
            }
        }
        // Query the database for the nodes of the workflow to be loaded.
        $resultNodos = Node::getNodos($workflowId);
        $nodes = array();
        $minode = $resultNodos[0];
        // Create node objects.
        foreach ($resultNodos as $node) {
            $configuration = ezcWorkflowDatabaseUtil::unserialize($node['node_configuration'], null);
            if (is_null($configuration)) {
                $configuration = ezcWorkflowUtil::getDefaultConfiguration($node['node_class']);
            }

            $nodes[$node['node_id']] = new $node['node_class']($configuration);
            $nodes[$node['node_id']]->setId($node['node_id']);
            if ($node['node_class'] == 'ezcWorkflowNodeAutomatic') {
                $automaticTask = AutomaticTask::get_Automatic_Task($node['node_id']);
                $nodes[$node['node_id']]->set_direccion($automaticTask[0]['service_dir']);
            } else if ($node['node_class'] == 'ezcWorkflowNodeSemiautomatic') {
                $semiautomaticTask = SemiautomaticTask::get_Semiautomatic_Task($node['node_id']);
                $nodes[$node['node_id']]->set_action($semiautomaticTask[0]['action_id']);
                $nodes[$node['node_id']]->set_id($semiautomaticTask[0]['rol_id']);
                $nodes[$node['node_id']]->set_active($semiautomaticTask[0]['active']);
                $nodes[$node['node_id']]->set_xpdl_id($semiautomaticTask[0]['xpdl_id']);
                $nodes[$node['node_id']]->set_variable_in($semiautomaticTask[0]['variable_in']);
                $nodes[$node['node_id']]->set_variable_out($semiautomaticTask[0]['variable_out']);
                $nodes[$node['node_id']]->set_name($semiautomaticTask[0]['name']);
                $nodes[$node['node_id']]->set_asociate_field($semiautomaticTask[0]['asociate_field']);
            } else if ($node['node_class'] == 'ezcWorkflowNodeInput') {
                $input = Input::get_Input($node['node_id']);
                $nodes[$node['node_id']]->set_Name($input[0]['name']);
                $nodes[$node['node_id']]->set_Condition($input[0]['condition']);
                $nodes[$node['node_id']]->set_direccion($input[0]['service_dir']);
            }
            if ($nodes[$node['node_id']] instanceof ezcWorkflowNodeFinally && !isset($finallyNode)) {
                $finallyNode = $nodes[$node['node_id']];
            } else if ($nodes[$node['node_id']] instanceof ezcWorkflowNodeEnd && !isset($defaultEndNode)) {
                $defaultEndNode = $nodes[$node['node_id']];
            } else if ($nodes[$node['node_id']] instanceof ezcWorkflowNodeStart && !isset($startNode)) {
                $startNode = $nodes[$node['node_id']];
            }
        }

        if (!isset($startNode) || !isset($defaultEndNode)) {
            throw new ezcWorkflowDefinitionStorageException('Could not load workflow definition.');
        }

        try {
            //--Connect node objects.--//
            $connections = NodeConnection::obtenerNodos($workflowId);
        } catch (Doctrine_Exception $e) {
            echo "<pre> Node Conection in ezcWorkflowDatabaseDoctrineDefinitionStorage";
            print_r($e->getMessage());
            die;
        }

        foreach ($connections as $connection) {
            $nodes[$connection['incoming_node_id']]->addOutNode($nodes[$connection['outgoing_node_id']]);
        }
        if (!isset($finallyNode) || count($finallyNode->getInNodes()) > 0) {
            $finallyNode = null;
        }


        //--Create workflow object and add the node objects to it.--//
        $workflow = new ezcWorkflow($workflowName, $startNode, $defaultEndNode, $finallyNode);
        $workflow->definitionStorage = $this;
        $workflow->id = (int) $workflowId;
        $workflow->version = (int) $workflowVersion;


        //--Query the database for the variable handlers.--//
        $resultVariables = VariableHandler::getVariables($workflowId);
        $nodes = array();
        if ($resultVariables !== false) {
            foreach ($resultVariables as $variableHandler) {
                $workflow->addVariableHandler($variableHandler['variable'], $variableHandler['class']);
            }
        }       

        //-*Verify the loaded workflow.--/
        $workflow->verify();
        return $workflow;
    }

    /**
     * Load a workflow definition by name.
     *
     * @param  string  $workflowName
     * @param  int $workflowVersion
     * @return ezcWorkflow
     * @throws ezcWorkflowDefinitionStorageException
     * @throws ezcDbException
     */
    public function loadByName($workflowName, $workflowVersion = 0) {
        // Load the current version of the workflow.
        if ($workflowVersion == 0) {
            $workflowVersion = $this->getCurrentVersionNumber($workflowName);
        }

        // Query for the workflow_id.
        $workFlowId = WorkFlow::getWorkFlowId($workflowName, $workflowVersion);
        if (isset($workFlowId)) {
            return $this->loadById(
                    $workFlowId, $workflowName, $workflowVersion);
        } else {
            throw new ezcWorkflowDefinitionStorageException('Could not load workflow definition.');
        }
    }

    /**
     * Save a workflow definition to the database.
     *
     * @param  ezcWorkflow $workflow
     * @throws ezcWorkflowDefinitionStorageException
     * @throws ezcDbException
     */
    public function save(ezcWorkflow $workflow) {
        try {
            //----Verify the workflow.----//
            $workflow->verify();

            //----Calculate new version number.----//
            //$workflowVersion = $this->getCurrentVersionNumber($workflow->name) + 1;

            if ($this->initialized === FALSE) {
                $this->initialize();
            }

            $workflowdb = new Workflow();

            $wfid = $workflow->__get('id');

            $workflowdb->workflow_id = $wfid;
            $workflowdb->workflow_name = $workflow->name;
            $workflowdb->workflow_version = $workflow->version;
            $workflowdb->workflow_created = time();


            $workModel = new WorkflowModel();
            $workModel->Guardar($workflowdb);

            $workflow->definitionStorage = $this;

            //----Write node table rows.----//
            $nodes = $workflow->nodes;
            $keys = array_keys($nodes);

            $numNodes = count($nodes);
            for ($i = 0; $i < $numNodes; $i++) {
                $id = $keys[$i];
                $node = $nodes[$id];

                $nodedb = new Node();

                $nodeId = $node->getId();
                $_nodeConfig = $node->getConfiguration();
                $nodeConfig = ezcWorkflowDatabaseUtil::serialize($_nodeConfig);

                $nodedb->workflow_id = $wfid;
                $nodedb->node_id = $nodeId;
                $nodedb->node_class = get_class($node);
                $nodedb->node_configuration = $nodeConfig;

                $nodedb->save();
            }

            //Connect node table rows.
            for ($i = 0; $i < $numNodes; $i++) {
                $id = $keys[$i];
                $node = $nodes[$id];

                foreach ($node->getOutNodes() as $outNode) {
                    //$getid = $node->getId();
                    $nodeConectionDb = new NodeConnection();
                    $nodeConectionDb->incoming_node_id = (int) $node->getId();
                    $nodeConectionDb->outgoing_node_id = (int) $outNode->getId();
                    $nodeConectionDb->save();
                }
            }
            /* foreach ($workflow->getVariableHandlers() as $variable => $class) {
              $variableHandler = new VariableHandler();
              $variableHandler->workflow_id = (int) $idwf;
              $variableHandler->variable = $variable;
              $variableHandler->class = $class;
              $variableHandler->save();
              } */
        } catch (Exception $exc) {
            $transactionManager = ZendExt_Aspect_TransactionManager::getInstance();
            $transactionManager->rollbackTransactions($exc);
            throw $exc;
        }
    }

    /**
     * Returns the current version number for a given workflow name.
     *
     * @param  string $workflowName
     * @return int
     * @throws ezcDbException
     */
    protected function getCurrentVersionNumber($workflowName) {
        $version = Workflow::getCurrentVersion($workflowName);
        return $version;
    }

    /*
     * deberia retornar un entero
     * working purpose only
     */

    private function generateId() {
        //hasta de 10 digitos el id
        $length = rand(1, 9);
        $intAsString = '';
        while (strlen($intAsString) <= $length) {
            $rand = chr(rand(48, 57));
            $intAsString .= $rand;
        }
        return intval($intAsString);
    }

}

?>
