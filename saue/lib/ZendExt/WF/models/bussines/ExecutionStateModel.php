<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
class ExecutionStateModel extends ZendExt_Model 
 { 
   public function init() 
    { 
        parent::ZendExt_Model(); 
    } 
 
   public function Guardar($ExecutionState) 
    { 
            $ExecutionState->save();
    } 
 
   public function Eliminar($ExecutionState) 
    { 
            $ExecutionState->delete();
    } 
 }

