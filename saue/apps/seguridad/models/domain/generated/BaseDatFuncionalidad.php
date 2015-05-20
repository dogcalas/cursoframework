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
abstract class BaseDatFuncionalidad extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('mod_seguridad.dat_funcionalidad');
    $this->hasColumn('idfuncionalidad', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => true,
  'sequence' => 'mod_seguridad.sec_datfuncionalidad',
));

    $this->hasColumn('index', 'integer', 4, array (
  'ntype' => 'int4',
  'alltypes' => 
  array (
    0 => 'integer',
  ),
  'unsigned' => false,
  'notnull' => false,
  'default' => '0',
  'primary' => false,
));

    $this->hasColumn('referencia', 'string', 255, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => false,
  'primary' => false,
));

    $this->hasColumn('denominacion', 'string', null, array (
  'ntype' => 'text',
  'alltypes' => 
  array (
    0 => 'string',
    1 => 'clob',
  ),
  'fixed' => false,
  'notnull' => true,
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
    $this->hasColumn('idsistema', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
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
  }


}