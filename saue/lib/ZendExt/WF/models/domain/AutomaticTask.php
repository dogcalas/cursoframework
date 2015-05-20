<?php 
class AutomaticTask extends BaseAutomaticTask
 { 
	public function setUp() 
    {   parent::setUp(); 
         $this->hasOne('Node', array('local'=>'node_id','foreign'=>'node_id')); 

    } 
 
	public function GetLLave() 
    { 
          $query = new Doctrine_Query(); 
          $result = $query ->select("MAX('node_id')")->from('AutomaticTask')->execute(); 
          $arr = $result->toArray(); 
          return (is_array($arr) ? $arr[0]['MAX'] + 1 : 1); 

    } 
 
	public function GetTodos() 
    { 
          $query = new Doctrine_Query(); 
          $result = $query->from('AutomaticTask')->execute (); 
          return $result->toArray(); 

    } 
    
    public static function get_Automatic_Task($id)
	{
		$query = Doctrine_Query::create();
		$datos = $query	->from('AutomaticTask a')
						->where('a.node_id=?',$id)
						->execute()
						->toArray();
	
		return $datos;
	}
 
 
}//fin clase


