<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
abstract class BaseDatCampoNumerico extends DatComponente 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('dat_campo_numerico'); 
       $this->hasColumn('limite_superior', 'numeric', null, array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('idcomponente', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('limite_inferior', 'numeric', null, array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('decimal', 'numeric', null, array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('precision', 'numeric', null, array('notnull' => false, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase

