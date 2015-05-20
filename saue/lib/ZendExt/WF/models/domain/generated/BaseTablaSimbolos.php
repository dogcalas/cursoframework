<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
abstract class BaseTablaSimbolos extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_workflow.tabla_simbolos'); 
       $this->hasColumn('id', 'ARRAY', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('in_node', 'ARRAY', null, array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('out_node', 'ARRAY', null, array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('split_type', 'ARRAY', null, array('notnull' => false, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase

