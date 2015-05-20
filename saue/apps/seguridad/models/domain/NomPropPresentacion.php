<?php

class NomPropPresentacion extends BaseNomPropPresentacion
{

    public function setUp()
    {
        parent :: setUp ();
    }


	static public function cargarTemaPresentacion($id,$returnId)
	{
		if(!$returnId){
			$query = new Doctrine_Query ();		
			$result = $query ->select('*')->from('NomPropPresentacion t')->where("t.idtema = ?",array($id))
							
							 ->execute();	
			return $result; 
		}else{
			$query = new Doctrine_Query ();		
			$result = $query ->select('*')->from('NomPropPresentacion t')->where("t.idtema = ?",array($id))
			->execute();	
			return $result[0]->get('idpresentacion');
		}   				 
	}
	
    
	
    
	
   
}

