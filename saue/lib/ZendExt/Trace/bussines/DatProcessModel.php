<?php

class DatProcessModel extends ZendExt_Model
{
public function DatProcessModel()
	{
	   		parent::ZendExt_Model();       
	    }
	    
	    function Insertar($proceso)
    {
	      $proceso->save();
	    }
	    
    function Modificar($proceso)
	{ 
	  $proceso->save();
	}
		
    function Eliminar($proceso)
	{ 
		$proceso->delete();
	}

}

