<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
class ExecutionModel extends ZendExt_Model 
 { 
   public function init() 
    { 
        parent::ZendExt_Model(); 
    } 
 
   public function Guardar($Execution) 
    { 
            $Execution->save();
    } 
 
   public function Eliminar($Execution) 
    { 
            $Execution->delete();
    } 
 }

