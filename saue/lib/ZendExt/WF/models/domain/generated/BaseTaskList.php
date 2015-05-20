<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
abstract class BaseTaskList extends Doctrine_Record 
 { 
	public function setTableDefinition() 
    { 
       $this->setTableName('mod_workflow.task_list'); 
       $this->hasColumn('rol_id', 'numeric', 20, array('notnull' => true, 'primary' => true)); 
       $this->hasColumn('workflow_id', 'numeric', 10, array('notnull' => true, 'primary' => true)); 
       $this->hasColumn('action_name', 'character varying', 255, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('node_id', 'numeric', 10, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('execution_id', 'numeric', 10, array('notnull' => true, 'primary' => true));
    } 
 
	public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase

