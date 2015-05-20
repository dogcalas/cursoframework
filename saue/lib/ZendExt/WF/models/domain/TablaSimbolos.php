<?php 
class TablaSimbolos extends BaseTablaSimbolos
 { 
   public function setUp() 
    { 
       parent::setUp();
    } 
 
   public function GetLLave() 
    { 

    } 
 
   public function GetTodos() 
    { 
          $query = new Doctrine_Query(); 
          $result = $query->from('TablaSimbolos')->execute (); 
          return $result->toArray(); 

    } 
 
}//fin clase


