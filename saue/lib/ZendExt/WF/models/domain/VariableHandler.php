<?php 
class VariableHandler extends BaseVariableHandler
 { 
   public function setUp() 
    { 
      parent::setUp();
	   $this->hasOne("WorkFlow",array('local'=>'workflow_id','foreign'=>'workflow_id'));
    } 
 

   public function GetLLave() 
    { 
         /* $query = new Doctrine_Query (); 
          $result = $query ->select('MAX('class')')->from('VariableHandler')->execute(); 
          $arr = $result->toArray(); 
          return (is_array($arr) ? $arr[0]['MAX'] + 1 : 1); */

    } 
 
   public function Buscar($class) 
    { 
          $temp = $this->conn->getTable('VariableHandler')->find($class); 
           return $temp->toArray(); 

    } 
 

   public function GetTodos() 
    { 
          $query = new Doctrine_Query (); 
          $result = $query->from('VariableHandler')->execute (); 
          return $result->toArray(); 

    } 
 
   public function GetPorLimite($limite = 10,$inicio = 0) 
    { 
          $query = new Doctrine_Query (); 
          $result = $query->from('VariableHandler')->limit($limite = 10)->offset($inicio = 0)->execute (); 
          return $result->toArray(); 

    }

	public static function getVariables($idWorkFlow)
	{
	$query=Doctrine_Query::create();
	  $datos=$query->select('v.variable,v.class')
					->from('VariableHandler v')
					->where('v.workflow_id=?',$idWorkFlow)
				    ->execute()
					->toArray();
	
	return $datos;
	
	
	
	
	}
 
}//fin clase 
?>


