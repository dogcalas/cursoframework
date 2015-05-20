<?php
class ezcWorkflowNodeSemiautomatic extends ezcWorkflowNode
{
	public $action_id = null;
	public $rol_id =  -1;
	public $active = false;
	public $xpdl_id = null;
	public $variable_in = null;
	public $variable_out = null;
	public $name = null;
	public $asociate_field = null;
	
	public function __construct( $configuration )
    {
        if ( is_string( $configuration ) )
        {
            $configuration = array( 'class' => $configuration );
        }

        if ( !isset( $configuration['arguments'] ) )
        {
            $configuration['arguments'] = array();
        }
		
        parent::__construct( $configuration );
    }
    
    public function set_action($action)
    {
		$this->action_id = $action;
	}
	
	public function get_action()
	{
		return $this->action_id;
	}
	public function set_id($id)
    {
		$this->rol_id = $id;
	}
	public function get_id()
	{
		return $this->rol_id;
	}
	public function set_active($active)
    {
		$this->active = $active;
	}
	public function get_active()
	{
		return $this->name;
	}
	public function set_xpdl_id($xpdl_id)
    {
		$this->xpdl_id = $xpdl_id;
	}
	public function get_xpdl_id()
	{
		return $this->xpdl_id;
	}
	public function set_variable_in($variable_in)
    {	
		$this->variable_in = ezcWorkflowDatabaseUtil::serialize($variable_in); 
	}
	public function get_variable_in()
    {
		$variable = ezcWorkflowDatabaseUtil::unserialize( $this->variable_in);
		return $variable;
	}
	public function set_variable_out($variable_out)
    {
		$this->variable_out = ezcWorkflowDatabaseUtil::serialize($variable_out);
	}
	public function get_variable_out()
    {
		$variable = ezcWorkflowDatabaseUtil::unserialize($this->variable_out);
	}
	public function set_name($name)
    {
		$this->name = $name;
	}
	public function get_name()
    {
		return $this->name;
	}
	public function set_asociate_field($asociate_field)
    {
		$this->asociate_field = $asociate_field;
	}
	public function get_asociate_field()
    {
		return $this->asociate_field;
	}
	
	/**
     * Executes this node by creating the service object and calling its execute() method.
     *
     * If the service object returns true, the output node will be activated.
     * If the service node returns false the workflow will be suspended
     * unless there are other activated nodes. An action node suspended this way
     * will be executed again the next time the workflow is resumed.
     *
     * @param ezcWorkflowExecution $execution
     * @return boolean true when the node finished execution,
     *                 and false otherwise
     * @ignore
     */
    public function execute( ezcWorkflowExecution $execution )
    {
		//echo ' Entra en el execute de Semiautomatica ';
		$wfId = $execution->__get('workflow')->__get('id');
		$wfName = $execution->__get('workflow')->__get('name');
		$wfVersion = $execution->__get('workflow')->__get('version');
		$taskId = $this->getId();
		$taskName = $this->xpdl_id;
		$taskType = "Semiautomatic_Task ";
		$execution_id = $execution->getId();
		
		$execute = new ZendExt_WF_WFExecute_Execute();
		
		$wfTrace = new ZendExt_Trace_WFTraceRegister();
		
		$this->active = $execute->checkExecutionState($wfId,$taskId,$execution_id);
		
        if($this->active)
        {
			//print_r(" ..Active true.. ");
			// Execution of the Service Object has finished.
			if ( $finished !== false )
			{
				$execute->cambiarToFalse((int)$this->getId());
				
				$execute->eliminar_TaskList($wfId, $this->getId(), $execution_id);
				
				$taskState = "Resume";
				$wfTrace -> tasksTraceAction($wfId,$wfName,$wfVersion,$taskId,$taskName,$taskState,$taskType);
				
				//----Activo el siguiente nodo----///
				$this->activateNode( $execution, $this->outNodes[0] );
				return parent::execute( $execution );
			}
			// Execution of the Service Object has not finished.
			else
			{
				return false;
			}
		}
		else
		{
			//print_r(" .. Active false .. ");
			$execution->suspend( $this );
			
			$execute->guardar_TasKList($wfId, $this->action_id, $this->getId(), $this->rol_id, $execution_id);

			if($this->variable_out != null)
			{
				//print_r()
				$datos['action_id']=$this->get_action();
				$separado= explode ("/",$this->variable_out);
				$datos['system']=$separado[0];
				$datos['object']=$separado[1];				
				$datos['field']=$separado[2];
				//$datos['value']='';
				$datos['node_id']=$this->getId();
				$datos['execution_id']=$execution_id;
				$datos['workflow_id']=$wfId;
				$datos['asociate']=false;
				//$datos['asociate']=$this->variable_in;
				echo 'antes de crear';
				//ZendExt_WF_WFExecute_Record::create_record($datos);
			}
			
			$taskState = "Suspend";
			$wfTrace -> tasksTraceAction($wfId,$wfName,$wfVersion,$taskId,$taskName,$taskState,$taskType);
			
		}
    }
    
    /**
     * Returns a textual representation of this node.
     *
     * @return string
     * @ignore
     */
    public function __toString()
    {
        try
        {
            $buffer = (string)$this->createObject();
        }
        catch ( ezcBaseAutoloadException $e )
        {
            return 'Class not found.';
        }
        catch ( ezcWorkflowExecutionException $e )
        {
            return $e->getMessage();
        }

        return $buffer;
    }

    /**
     * Returns the service object as specified by the configuration.
     *
     * @return ezcWorkflowServiceObject
     */
    protected function createObject()
    {
        if ( !class_exists( $this->configuration['class'] ) )
        {
            throw new ezcWorkflowExecutionException(
              sprintf(
                'Class "%s" not found.',
                $this->configuration['class']
              )
            );
        }

        $class = new ReflectionClass( $this->configuration['class'] );

        if ( !$class->implementsInterface( 'ezcWorkflowServiceObject' ) )
        {
            throw new ezcWorkflowExecutionException(
              sprintf(
                'Class "%s" does not implement the ezcWorkflowServiceObject interface.',
                $this->configuration['class']
              )
            );
        }

        if ( !empty( $this->configuration['arguments'] ) )
        {
            return $class->newInstanceArgs( $this->configuration['arguments'] );
        }
        else
        {
            return $class->newInstance();
        }
    }
}
?>
