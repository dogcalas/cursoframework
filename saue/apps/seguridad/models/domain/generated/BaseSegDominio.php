<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien García Tejo
 * @author Julio Cesar García Mosquera  
 * @version 1.0-0
 */
abstract class BaseSegDominio extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('mod_seguridad.seg_dominio');
    $this->hasColumn('iddominio', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => true,
));
    $this->hasColumn('cadena', 'string', null, array (
  'ntype' => 'text',
  'alltypes' => 
  array (
    0 => 'string',
    1 => 'clob',
  ),
  'fixed' => false,
  'notnull' => false,
  'primary' => false,
));
  }


}