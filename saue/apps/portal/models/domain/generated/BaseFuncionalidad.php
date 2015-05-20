<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseFuncionalidad extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('funcionalidad');
    $this->hasColumn('idfuncionalidad', 'integer', 4, array('unsigned' => false, 'notnull' => true, 'primary' => true));
    $this->hasColumn('idmodulo', 'integer', 4, array('unsigned' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('denominacion', 'string', null, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('icono', 'string', null, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('referencia', 'string', null, array('fixed' => false, 'notnull' => false, 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
  }

}
