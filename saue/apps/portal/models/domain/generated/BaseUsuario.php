<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUsuario extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('usuario');
    $this->hasColumn('idusuario', 'integer', 4, array('unsigned' => false, 'notnull' => true, 'primary' => true));
    $this->hasColumn('nombre', 'string', null, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('papell', 'string', null, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('sapell', 'string', null, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('alias', 'string', null, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('idioma', 'string', 2, array('fixed' => false, 'notnull' => false, 'default' => '\'es\'::character varying', 'primary' => false));
    $this->hasColumn('tema', 'string', null, array('fixed' => false, 'notnull' => false, 'default' => '\'default\'::character varying', 'primary' => false));
    $this->hasColumn('portal', 'string', null, array('fixed' => false, 'notnull' => true, 'default' => '\'standararbol\'::character varying', 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
  }

}