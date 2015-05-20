<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
abstract class BaseAsociation extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_workflow.asociation'); 
       $this->hasColumn('asociation_id', 'numeric', null, array('notnull' => true, 'primary' => true, 'sequence' => 'asociation_asociation_id')); 
       $this->hasColumn('workflow_id', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('xpdl_id', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('value', 'character varying', 255, array('notnull' => true, 'primary' => false));
       $this->hasColumn('execution_id', 'numeric', 10, array('notnull' => true, 'primary' => false));
       $this->hasColumn('field', 'character varying', 255, array('notnull' => true, 'primary' => false));
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase

