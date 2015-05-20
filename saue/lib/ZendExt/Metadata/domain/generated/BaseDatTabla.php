<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
abstract class BaseDatTabla extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('dat_tabla'); 
       $this->hasColumn('idtabla', 'numeric', null, array('notnull' => true, 'primary' => true, 'sequence' => 'mod_metadata.sec_dat_tabla')); 
       $this->hasColumn('esquema', 'character varying', 50 , array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('tabla', 'character varying', 100 , array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('arbol', 'numeric', null, array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('importada', 'numeric', null, array('notnull' => false, 'primary' => false));
       $this->hasColumn('alias', 'character varying', 50 , array('notnull' => true, 'primary' => false));
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase

