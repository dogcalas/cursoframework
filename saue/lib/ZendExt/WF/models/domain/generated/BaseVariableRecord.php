<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
abstract class BaseVariableRecord extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_workflow.variable_record'); 
       $this->hasColumn('variable_record_id', 'numeric', null, array('notnull' => true, 'primary' => true, 'sequence' => 'variable_record_id')); 
       $this->hasColumn('workflow_id', 'numeric', null, array('notnull' => true, 'primary' => false));         
       $this->hasColumn('field', 'character varying', 255 , array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('object', 'character varying', 255 , array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('system', 'character varying', 255 , array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('value', 'character varying', 255 , array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('action_id', 'character varying', 255, array('notnull' => true, 'primary' => false));
       $this->hasColumn('node_id', 'numeric', null, array('notnull' => true, 'primary' => false));
       $this->hasColumn('execution_id', 'numeric', null, array('notnull' => true, 'primary' => false));
       $this->hasColumn('asociate', 'boolean', null, array('notnull' => true, 'primary' => false));
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase

