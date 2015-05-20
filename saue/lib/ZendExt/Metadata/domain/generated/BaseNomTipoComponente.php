<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
abstract class BaseNomTipoComponente extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('nom_tipo_componente'); 
       $this->hasColumn('idtipocomponente', 'numeric', null, array('notnull' => true, 'primary' => true, 'sequence' => 'mod_metadata.sec_nom_tipo_componente')); 
       $this->hasColumn('tabla', 'character varying', 50 , array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('tipo_componente', 'character varying', 50 , array('notnull' => false, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase

