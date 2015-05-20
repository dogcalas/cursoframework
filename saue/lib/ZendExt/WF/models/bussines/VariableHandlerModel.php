<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
class VariableHandlerModel extends ZendExt_Model 
 { 
   public function init() 
    { 
        parent::ZendExt_Model(); 
    } 
 
   public function Guardar($VariableHandler) 
    { 
            $VariableHandler->save();
    } 
 
   public function Eliminar($VariableHandler) 
    { 
            $VariableHandler->delete();
    } 
 }

