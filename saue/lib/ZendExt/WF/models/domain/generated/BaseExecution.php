<?php 
abstract class BaseExecution extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_workflow.execution'); 
       $this->hasColumn('execution_id', 'numeric', null, array('notnull' => true, 'primary' => true/*, 'sequence' => 'execution_execution_id'*/)); 
       $this->hasColumn('workflow_id', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('execution_next_thread_id', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('execution_parent', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('execution_started', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('execution_threads', 'text', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('execution_variables', 'text', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('execution_waiting_for', 'text', null, array('notnull' => true, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase 
?>

