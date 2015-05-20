<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNomTipoComponenteCompuesto extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('nom_tipo_componente_compuesto');
    $this->hasColumn('idtipo_componente_compuesto', 'decimal', null, array('notnull' => true, 'primary' => true, 'sequence' => 'sec_tipo_componente_compuesto'));
    $this->hasColumn('denominacion', 'string', 50, array('fixed' => false, 'notnull' => true, 'primary' => false));
    $this->hasColumn('tabla', 'string', 50, array('fixed' => false, 'notnull' => true, 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
  }

}