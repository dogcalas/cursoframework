<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
class NodeConnectionModel extends ZendExt_Model 
 { 
   public function init() 
    { 
        parent::ZendExt_Model(); 
    } 
 
   public function Guardar($NodeConnection) 
    { 
            $NodeConnection->save();
    } 
 
   public function Eliminar($NodeConnection) 
    { 
            $NodeConnection->delete();
    } 
 }

