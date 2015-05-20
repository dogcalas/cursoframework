<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
abstract class BaseDatRelacion extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('dat_relacion'); 
       $this->hasColumn('campo_destino', 'numeric', null, array('notnull' => true, 'primary' => true)); 
       $this->hasColumn('campo_origen', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('eliminar_cascada', 'numeric', null, array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('actualizar_cascada', 'numeric', null, array('notnull' => false, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase

