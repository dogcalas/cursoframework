<?php 
class Asociation extends BaseAsociation
 { 
	public function setUp() 
    {   parent::setUp();
    
    } 
    public static function Buscar_Field_Act($act_name, $field_name)
    {
		$query= Doctrine_Query::create();
		$datos= $query 	->from('Asociation a')
						->where('a.xpdl_id=?', $act_name)
						->addWhere('a.field=?', $field_name)
						->execute()
						->toArray();
		//print_r($datos);die;
		return $datos;
	}
	
 
/*
	public function GetLLave() 
    { 
          $query = new Doctrine_Query(); 
          $result = $query ->select("MAX('node_id')")->from('Asociation')->execute(); 
          $arr = $result->toArray(); 
          return (is_array($arr) ? $arr[0]['MAX'] + 1 : 1); 

    } 
 
	public function GetTodos() 
    { 
          $query = new Doctrine_Query(); 
          $result = $query->from('Asociation')->execute (); 
          return $result->toArray(); 

    } 
    
    public static function get_Automatic_Task($id)
	{
		$query = Doctrine_Query::create();
		$datos = $query	->from('Asociation a')
						->where('a.node_id=?',$id)
						->execute()
						->toArray();
	
		return $datos;
	}
*/
 
 
}//fin clase


