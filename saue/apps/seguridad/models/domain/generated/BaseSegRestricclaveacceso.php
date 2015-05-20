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
abstract class BaseSegRestricclaveacceso extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('mod_seguridad.seg_restricclaveacceso');
    $this->hasColumn('idrestricclaveacceso', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => true,
  'sequence' => 'mod_seguridad.sec_segrestricclaveacceso',
));

    $this->hasColumn('diascaducidad', 'integer', 4, array (
  'ntype' => 'int4',
  'alltypes' => 
  array (
    0 => 'integer',
  ),
  'unsigned' => false,
  'notnull' => true,
  'primary' => false,
));

    $this->hasColumn('numerica', 'boolean', 1, array (
  'ntype' => 'bool',
  'alltypes' => 
  array (
    0 => 'boolean',
  ),
  'notnull' => false,
  'primary' => false,
));

    $this->hasColumn('alfabetica', 'boolean', 1, array (
  'ntype' => 'bool',
  'alltypes' => 
  array (
    0 => 'boolean',
  ),
  'notnull' => false,
  'primary' => false,
));

    $this->hasColumn('signos', 'boolean', 1, array (
  'ntype' => 'bool',
  'alltypes' => 
  array (
    0 => 'boolean',
  ),
  'notnull' => false,
  'primary' => false,
));
    $this->hasColumn('minimocaracteres', 'integer', 4, array (
  'ntype' => 'int4',
  'alltypes' => 
  array (
    0 => 'integer',
  ),
  'unsigned' => false,
  'notnull' => true,
  'primary' => false,
));
$this->hasColumn('canthistorico', 'integer', 4, array (
  'ntype' => 'int4',
  'alltypes' => 
  array (
    0 => 'integer',
  ),
  'unsigned' => false,
  'notnull' => true,
  'primary' => false,
));

  }


}
