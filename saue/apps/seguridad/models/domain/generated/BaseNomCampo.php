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
abstract class BaseNomCampo extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('mod_seguridad.nom_campo');
    $this->hasColumn('idcampo', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => true,
  'sequence' => 'mod_seguridad.sec_nomcampo',
));

    $this->hasColumn('tipo', 'string', 250, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => true,
  'primary' => false,
));

    $this->hasColumn('nombre', 'string', 250, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => true,
  'primary' => false,
));

    $this->hasColumn('nombreamostrar', 'string', 250, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => false,
  'primary' => false,
));

    $this->hasColumn('longitud', 'integer', 4, array (
  'ntype' => 'int4',
  'alltypes' => 
  array (
    0 => 'integer',
  ),
  'unsigned' => false,
  'notnull' => false,
  'primary' => false,
));

    $this->hasColumn('visible', 'boolean', 1, array (
  'ntype' => 'bool',
  'alltypes' => 
  array (
    0 => 'boolean',
  ),
  'notnull' => false,
  'primary' => false,
));

    $this->hasColumn('descripcion', 'string', 250, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => false,
  'primary' => false,
));

    $this->hasColumn('tipocampo', 'string', 250, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => true,
  'primary' => false,
));
    $this->hasColumn('idexpresiones', 'decimal', null, array (
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