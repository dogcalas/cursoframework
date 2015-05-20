<?php

class NomPropDesktopModel extends ZendExt_Model
{

public function NomPropDesktopModel()
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

