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
abstract class BaseDatSistema extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('mod_seguridad.dat_sistema');
    $this->hasColumn('idsistema', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => true,
  'sequence' => 'mod_seguridad.sec_datsistema',
));

   $this->hasColumn('idpadre', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => false,

));

    $this->hasColumn('denominacion', 'string', 255, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => true,
  'primary' => false,
));

    $this->hasColumn('icono', 'string', 30, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => false,
  'primary' => false,
));

    $this->hasColumn('abreviatura', 'string', 255, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => false,
  'primary' => false,
));
    $this->hasColumn('descripcion', 'string', 255, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => false,
  'primary' => false,
));

$this->hasColumn('externa', 'string', 20, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => false,
  'primary' => false,
));

$this->hasColumn('iddominio', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => false,
));
$this->hasColumn('idservidor', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => false,
  'primary' => false,
));
$this->hasColumn('lft', 'decimal', null, array('notnull' => false, 'primary' => false));
$this->hasColumn('rgt', 'decimal', null, array('notnull' => false, 'primary' => false));
$this->hasColumn('index', 'integer', null, array('notnull' => true, 'primary' => false));
}
}
