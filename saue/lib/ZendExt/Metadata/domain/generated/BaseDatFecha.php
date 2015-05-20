<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
abstract class BaseDatFecha extends DatComponente 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('dat_fecha'); 
       $this->hasColumn('fecha_inicio', 'date', null, array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('fecha_fin', 'date', null, array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('idcomponente', 'numeric', null, array('notnull' => true, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase

