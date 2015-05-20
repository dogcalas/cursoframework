<?php 
abstract class BaseWorkflow extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    {                                                                                                             
       $this->setTableName('mod_workflow.workflow'); 
       $this->hasColumn('workflow_id', 'numeric', null, array('notnull' => true, 'primary' => true/*, 'sequence' =>'workflow_workflow_id'*/)); 
       $this->hasColumn('workflow_created', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('workflow_name', 'character varying', 32 , array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('workflow_version', 'numeric', null, array('notnull' => true, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase 
?>

