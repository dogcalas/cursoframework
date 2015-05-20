<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
abstract class BaseDatCampo extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('dat_campo'); 
       $this->hasColumn('idcampo', 'numeric', null, array('notnull' => true, 'primary' => true, 'sequence' => 'mod_metadata.sec_dat_campo')); 
       $this->hasColumn('longitud', 'numeric', null, array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('denominacion', 'character varying', 50 , array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('permite_nulo', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('secuencia', 'numeric', null, array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('idtipodatogestor', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('llave_primaria', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('idtabla', 'numeric', null, array('notnull' => true, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase

