<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
class AutomaticTaskModel extends ZendExt_Model 
 { 
   public function init() 
    { 
        parent::ZendExt_Model(); 
    } 
 
   public function Guardar($AutomaticTask) 
    { 
            $AutomaticTask->save();
    } 
 
   public function Eliminar($AutomaticTask) 
    { 
            $AutomaticTask->delete();
    } 
 }

