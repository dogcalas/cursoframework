<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
class TablaSimbolosModel extends ZendExt_Model 
 { 
   public function init() 
    { 
        parent::ZendExt_Model(); 
    } 
 
   public function Guardar($TablaSimbolos) 
    { 
            $TablaSimbolos->save();
    } 
 
   public function Eliminar($TablaSimbolos) 
    { 
            $TablaSimbolos->delete();
    } 
 }

