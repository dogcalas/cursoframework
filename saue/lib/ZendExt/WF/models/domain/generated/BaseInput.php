<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
abstract class BaseInput extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_workflow.input'); 
       $this->hasColumn('node_id', 'numeric', null, array('notnull' => true, 'primary' => true)); 
       $this->hasColumn('service_dir', 'character varying', 255 , array('notnull' => true, 'primary' => false));
       $this->hasColumn('name', 'character varying', 255 , array('notnull' => true, 'primary' => false));
       $this->hasColumn('condition', 'character varying', 255 , array('notnull' => true, 'primary' => false));
       //$this->hasColumn('workflow_id', 'numeric', null, array('notnull' => true, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase

