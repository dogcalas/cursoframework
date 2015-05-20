<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
class NodeModel extends ZendExt_Model 
 { 
   public function init() 
    { 
        parent::ZendExt_Model(); 
    } 
 
   public function Guardar($Node) 
    { 
            $Node->save();
    } 
 
   public function Eliminar($Node) 
    { 
            $Node->delete();
    } 
 }

