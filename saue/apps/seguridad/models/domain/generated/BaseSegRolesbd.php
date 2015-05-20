<?php

/*
 *Roles de bases de dato.
 *
 * @package Acaxia
 * @copyright UCID-ERP Cuba
 * @author Darien Garc�a Tejo
 * @version 2.0-0
 */
abstract class BaseSegRolesbd extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('mod_seguridad.seg_rolesbd');
    $this->hasColumn('idrolesbd', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => true,
  'sequence' => 'mod_seguridad.sec_segrolesbd',
));

    $this->hasColumn('idservidor', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => false,
));

    $this->hasColumn('idgestor', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => false,
));

    $this->hasColumn('nombrerol', 'string', null, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => true,
  'primary' => false,
));
    $this->hasColumn('passw', 'string', null, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => true,
  'primary' => false,
));
  }


}