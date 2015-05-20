<?php 
class TaskList extends BaseTaskList
 { 
	public function setUp() 
    {   parent::setUp(); 
         $this->hasOne('Execution', array('local'=>'execution_id','foreign'=>'execution_id')); 
         $this->hasOne('Node', array('local'=>'node_id','foreign'=>'node_id')); 
         $this->hasOne('Workflow', array('local'=>'workflow_id','foreign'=>'workflow_id')); 
    } 
 
	public function GetLLave() 
    { 
          $query = new Doctrine_Query(); 
          $result = $query ->select("MAX('rol_id')")->from('TaskList')->execute(); 
          $arr = $result->toArray(); 
          return (is_array($arr) ? $arr[0]['MAX'] + 1 : 1); 

    } 
 
	public function GetTodos() 
    { 
          $query = new Doctrine_Query(); 
          $result = $query->from('TaskList')->execute (); 
          return $result->toArray(); 

    } 
    public function Buscar($action,$rol)
    {//	print_r($action.'  ');
		//print_r($rol);
		//print_r($rol);
		//print_r($action);die;
		$query= new Doctrine_Query;
		$datos= $query ->from('TaskList t')
						->where('t.action_name=?',$action)
						->addWhere('t.rol_id=?',$rol)							
						->execute() ->toArray();		
		//print_r($datos);die;
		return $datos;
	}
	
	public function BuscarObj($idnodo,$idexecution)
	{
		
		$query= new Doctrine_Query;
		$datos= $query ->from('TaskList t')
						->where('t.node_id=?',$idnodo)
						->addwhere('t.execution_id=?', $idexecution)
						->execute();
		
		return $datos;
	}
	public function BuscarObjDoctrine($wfId,$node_id,$execution_id)
	{
		//print_r("Llega aki id wf: ". $wfId. ",  id node: ".$node_id.", id ejecucion: ". $execution_id);
		//$datos = Doctrine::getTable('TaskList')->find($wfId); 
		$query= new Doctrine_Query;
		$datos= $query 	->from('TaskList t')
						->where('t.workflow_id =?', $wfId)
						->addWhere('t.node_id = ?', $node_id)
						->addWhere('t.execution_id = ?', $execution_id)
						->execute();
		//print_r("Sale");
		//print_r($datos);die;
		return $datos;
	}
	public function BuscarArray($wfId,$node_id,$execution_id)
	{
		//print_r("Llega aki id wf: ". $wfId. ",  id node: ".$node_id.", id ejecucion: ". $execution_id);
		//$datos = Doctrine::getTable('TaskList')->find($wfId); 
		$query= new Doctrine_Query;
		$datos= $query 	->from('TaskList t')
						->where('t.workflow_id =?', $wfId)
						->addWhere('t.node_id = ?', $node_id)
						->addWhere('t.execution_id = ?', $execution_id)
						->execute()
						->toArray();
		//print_r("Sale");
		//print_r($datos);die;
		return $datos;
	}
 
 
}//fin clase


