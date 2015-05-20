<?php

class NomPropComercialModel extends ZendExt_Model
{

public function NomPropComercialModel()
	{
	   		parent::ZendExt_Model();       
	    }
	    
	function insertartema($tema)
    {
	      $tema->save();
	    }
	    
    function modificartema($tema)
	{ 
	  $tema->save();
	}
		
    function eliminartema($tema)
	{ 
	       	$tema->delete();
	}
}

