<?php 
abstract class BaseNode extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_workflow.node'); 
       $this->hasColumn('node_id', 'numeric', null, array('notnull' => true, 'primary' => true/*, 'sequence' => 'node'*/)); 
       $this->hasColumn('node_class', 'character varying', 255 , array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('node_configuration', 'text', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('workflow_id', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       
       //esto lo puse yo... yriverog
       $this->hasColumn('idrol', 'integer', null, array('notnull' => false, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase 
?>

