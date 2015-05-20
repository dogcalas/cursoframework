<?php

class NomPropComercial extends BaseNomPropComercial
{

    public function setUp()
    {
        parent :: setUp ();
    }
	
	
	
	static public function cargarTemaComercial($id,$returnId)
	{
		if(!$returnId){
			$query = new Doctrine_Query ();		
			$result = $query ->select('*')->from('NomPropComercial t')->where("t.idtema = ?",array($id))
							
							 ->execute();	
			return $result;
		}else{
			$query = new Doctrine_Query ();		
	    $result = $query ->select('*')->from('NomPropComercial t')->where("t.idtema = ?",array($id))
	    ->execute();	
	    return $result[0]->get('idcomercial');   
			
			
		}        				 
	}
	
	
   

}

