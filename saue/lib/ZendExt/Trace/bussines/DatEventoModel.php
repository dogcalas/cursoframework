<?php

class DatEventoModel extends ZendExt_Model
{
public function DatEventoModel()
	{
	   		parent::ZendExt_Model();       
	    }
	    
	    function Insertar($evento)
    {
	      $evento->save();
	    }
	    
    function Modificar($evento)
	{ 
	  $evento->save();
	}
		
    function Eliminar($evento)
	{ 
		$evento->delete();
	}

}

