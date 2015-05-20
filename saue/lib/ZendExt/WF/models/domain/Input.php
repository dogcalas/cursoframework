<?php 
class Input extends BaseInput
 { 
	public function setUp() 
    {   parent::setUp(); 
         $this->hasOne('Node', array('local'=>'node_id','foreign'=>'node_id')); 

    } 
 
	public function GetLLave() 
    { 
          $query = new Doctrine_Query(); 
          $result = $query ->select("MAX('node_id')")->from('Input')->execute(); 
          $arr = $result->toArray(); 
          return (is_array($arr) ? $arr[0]['MAX'] + 1 : 1); 

    } 
 
	public function GetTodos() 
    { 
          $query = new Doctrine_Query(); 
          $result = $query->from('Input')->execute (); 
          return $result->toArray(); 

    }

    public static function get_Input($id)
	{
		$query = Doctrine_Query::create();
		$datos = $query	->from('Input i')
						->where('i.node_id=?',$id)
						->execute()
						->toArray();
		return $datos;
	}
	
	public function getVariableName($node_id)
    {
		$query = Doctrine_Query::create();
		$datos = $query	->select('i.name')
						->from('Input i')
						->where('i.node_id=?',$node_id)
						->execute()
						->toArray();
		return $datos;
	}
	
	public function getVariableCondition($node_id)
    {
		$query = Doctrine_Query::create();
		$datos = $query	->select('i.condition')
						->from('Input i')
						->where('i.node_id=?',$node_id)
						->execute()
						->toArray();
		return $datos;
	}
 
 
}//fin clase


