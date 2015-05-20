<?php

class SegRolDatServidorDatGestorModel extends ZendExt_Model
{

    public function SegRolDatServidorDatGestorModel(){
	parent::ZendExt_Model();
   }
   
    public function insertar($rol_ser_gest){ 
        
        $rol_ser_gest->save();
        
 
    }
    public function modificar($rol_ser_gest){ 
        
        $rol_ser_gest->save();
        
 
    }
    public function eliminar($instance)
    { 
        
   $instance->delete();
   }

}

