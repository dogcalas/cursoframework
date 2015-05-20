<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
abstract class BaseAutomaticTask extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_workflow.automatic_task'); 
       $this->hasColumn('node_id', 'numeric', null, array('notnull' => true, 'primary' => true)); 
       $this->hasColumn('service_dir', 'character varying', 255 , array('notnull' => true, 'primary' => false));
       //$this->hasColumn('workflow_id', 'numeric', null, array('notnull' => true, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase

