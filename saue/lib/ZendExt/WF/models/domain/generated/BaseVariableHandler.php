<?php 
abstract class BaseVariableHandler extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_workflow.variable_handler'); 
       $this->hasColumn('class', 'character varying', 255 , array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('workflow_id', 'numeric', null, array('notnull' => true, 'primary' => true)); 
       $this->hasColumn('variable', 'character varying', 255 , array('notnull' => true, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase 
?>

