<?php

class DatConexionModel extends ZendExt_Model
{
public function DatConexionModel()
	{
	   		parent::ZendExt_Model();       
	    }
	    
	    function Insertar($conexion)
    {
	      $conexion->save();
	    }
	    
    function Modificar($conexion)
	{ 
	  $conexion->replace();
	}
		
    function Eliminar($conexion)
	{ 
		$conexion->delete();
	}

}

