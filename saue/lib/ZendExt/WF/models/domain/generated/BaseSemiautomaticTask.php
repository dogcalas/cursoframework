<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
abstract class BaseSemiautomaticTask extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_workflow.semiautic_task'); 
       $this->hasColumn('node_id', 'numeric', null, array('notnull' => true, 'primary' => true)); 
       $this->hasColumn('action_id', 'character varying', 255, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('rol_id', 'bigint', null, array('notnull' => true, 'primary' => false));
       $this->hasColumn('active', 'boolean', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('xpdl_id', 'character varying', 255 , array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('variable_in', 'character varying', 9999999, array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('variable_out', 'character varying', 9999999, array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('name', 'character varying', 255 , array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('asociate_field', 'character varying', 255 , array('notnull' => false, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase

