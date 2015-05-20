<?php
class ezcWorkflowNodeAutomatic extends ezcWorkflowNode
{
	public $service_dir = null;   //atributo agregado para especificar la direccion del servicio que debe levantarse
	//public $variable_in = null;
	
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
    
    public function set_direccion($_direccion)
    {
		$this->service_dir = $_direccion;
	}
	public function get_direccion()
	{
		return $this->service_dir;
	}
/*
	public function get_variable_in()
    {
		$variable = ezcWorkflowDatabaseUtil::unserialize( $this->variable_in);
		return $variable;
	}
	public function set_variable_out($variable_out)
    {
		$this->variable_out = ezcWorkflowDatabaseUtil::serialize($variable_out);
	}
*/
	
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
		$ioc = ZendExt_IoC::getInstance();
		$ioc->$this->service_dir;
		
		$this->activateNode( $execution, $this->outNodes[0] );
		return parent::execute( $execution );
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
