<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseSegUsuarioNomDominio extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('mod_seguridad.seg_usuario_nom_dominio');
    $this->hasColumn('idusuario', 'decimal', null, array('notnull' => true, 'primary' => true));
    $this->hasColumn('iddominio', 'decimal', null, array('notnull' => true, 'primary' => true));
  }

  public function setUp()
  {
    parent::setUp();
  }

}