<?php 
abstract class BaseNodeConnection extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_workflow.node_connection'); 
       $this->hasColumn('incoming_node_id', 'numeric', null, array('notnull' => true, 'primary' => true)); 
       $this->hasColumn('outgoing_node_id', 'numeric', null, array('notnull' => true, 'primary' => true)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase 
?>

