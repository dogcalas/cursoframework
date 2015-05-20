<?php 
class VariableRecord extends BaseVariableRecord
 { 
    public function setUp() 
    {   parent::setUp(); 
         $this->hasOne('Execution', array('local'=>'execution_id','foreign'=>'execution_id')); 
         $this->hasOne('Node', array('local'=>'node_id','foreign'=>'node_id')); 
         $this->hasOne('Workflow', array('local'=>'workflow_id','foreign'=>'workflow_id')); 
    } 
 

    public static function Buscar($variable_record_id) 
    { 
		$query = Doctrine_Query::create(); 
        $result = $query->from('VariableRecord vr')
						->where('vr.variable_record_id = ?',$variable_record_id)
						->execute (); 
        return $result->toArray();
    } 
    
    public static function Buscar_Variable_Record_Node($node_id,$execution_id) 
    { 
		
		$query = Doctrine_Query::create(); 
        $result = $query->from('VariableRecord vr')
						->where(" vr.node_id = $node_id")
						->addWhere('vr.execution_id=?', $execution_id)
						->execute (); 
        return $result->toArray();
        //print_r("salio domin");die;
    } 
    
    public static function Buscar_Variable_Record($variable_record_id) 
    { 
		$query = Doctrine_Query::create(); 
        $result = $query->from('VariableRecord vr')
						->where('vr.variable_record_id = ?',$variable_record_id)
						->execute (); 
          return $result;
    } 
 

    public function GetTodos() 
    { 
          $query = Doctrine_Query::create(); 
          $result = $query->from('VariableRecord')->execute (); 
          return $result->toArray(); 

    } 
 
}//fin clase


