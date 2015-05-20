<?php

class DatObjetobdModel extends ZendExt_Model
{
                public function DatObjetobdModel()
		{
			parent::ZendExt_Model();
		}
        
		public function insertar($Objeto)
		{   
	       	 	$Objeto->save();
		}
        
		public function modificar($objetobd)
		{
                
	       	 	$objetobd->save();
                        
		}
        
		public function eliminar($instance)
		{ 
	       	 	$instance->delete();
		}

}

