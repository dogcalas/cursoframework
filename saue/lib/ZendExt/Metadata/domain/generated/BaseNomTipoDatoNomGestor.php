<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
abstract class BaseNomTipoDatoNomGestor extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('nom_tipo_dato_nom_gestor'); 
       $this->hasColumn('idtipodatogestor', 'numeric', null, array('notnull' => true, 'primary' => true, 'sequence' => 'mod_metadata.sec_nom_tipo_dato_nom_gestor')); 
       $this->hasColumn('nombre', 'character varying', 50 , array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('idtipodato', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('idgestor', 'numeric', null, array('notnull' => true, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase

