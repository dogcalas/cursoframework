<?php

class NomPropDesktop extends BaseNomPropDesktop
{

    public function setUp()
    {
        parent :: setUp ();
    }

	
	
	static public function cargarTemaDesktop($id,$returnId)
	{
		if(!$returnId){
			$query = new Doctrine_Query ();		
			$result = $query ->select('*')->from('NomPropDesktop t')->where("t.idtema = ?",array($id))
			->execute();	
			return $result; 
		}else{
			$query = new Doctrine_Query ();		
			$result = $query ->select('*')->from('NomPropDesktop t')->where("t.idtema = ?",array($id))
			->execute();	
			return $result[0]->get('iddesktop');    
		}       				 
	}
	
	
	
    
	
}

