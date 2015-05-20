<?php 
abstract class BaseExecutionState extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_workflow.execution_state'); 
       $this->hasColumn('execution_id', 'numeric', null, array('notnull' => true, 'primary' => true)); 
       $this->hasColumn('node_id', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('node_activated_from', 'text', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('node_state', 'text', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('node_thread_id', 'numeric', null, array('notnull' => true, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase 
?>

