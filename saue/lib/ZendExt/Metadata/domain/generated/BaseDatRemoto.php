<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseDatRemoto extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('dat_remoto');
    $this->hasColumn('idcomponente', 'decimal', null, array('notnull' => false, 'primary' => true));
    $this->hasColumn('campo_denominacion', 'string', 50, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('campo_origen', 'decimal', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('campo_destino', 'decimal', null, array('notnull' => false, 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
  }

}
