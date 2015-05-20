<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
class AsociationModel extends ZendExt_Model 
 { 
   public function init() 
    { 
        parent::ZendExt_Model(); 
    } 
 
   public function Guardar($Asociation) 
    { 
            $Asociation->save();
    } 
 
   public function Eliminar($Asociation) 
    { 
            $Asociation->delete();
    } 
    
    public function Buscar_Field_Act($act_name, $field_name)
    {
		return Asociation::Buscar_Field_Act($act_name, $field_name);
	}
	
/*
	public function create_asociation($asociation)
	{
		
	}
*/
 }

