<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */
abstract class BaseNomFila extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('mod_seguridad.nom_fila');
    $this->hasColumn('idfila', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => true,
  'sequence' => 'mod_seguridad.sec_nomfila',
));
    $this->hasColumn('idusuario', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => false,
));
  }


}