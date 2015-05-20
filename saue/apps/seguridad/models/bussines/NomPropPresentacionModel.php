<?php

class NomPropPresentacionModel extends ZendExt_Model
{
public function NomPropPresentacionModel()
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

