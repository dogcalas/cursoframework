<?php 
class NodeConnection extends BaseNodeConnection
 { 
   public function setUp() 
    { 
      $this->hasOne("Node",array('local'=>'incoming_node_id','foreign'=>'node_id'));
	  parent::setUp();
	   
    } 
 

   public function GetTodos() 
    { 
          $query = new Doctrine_Query (); 
          $result = $query->from('NodeConnection')->execute (); 
          return $result->toArray(); 

    } 
 
   public function GetPorLimite($limite = 10,$inicio = 0) 
    { 
          $query = new Doctrine_Query (); 
          $result = $query->from('NodeConnection')->limit($limite = 10)->offset($inicio = 0)->execute (); 
          return $result->toArray(); 

    } 
	
	public static function obtenerNodos($workFlowId)
	{
	  $query=Doctrine_Query::create();
	  $datos=$query->select('n.incoming_node_id,n.outgoing_node_id')
					->from('NodeConnection n,n.Node node,node.Workflow w')
					->where('w.workflow_id=?',$workFlowId)
				    ->execute()
					->toArray();
	
	return $datos;
	
	
	
	
	}
 
}//fin clase 
?>


