<?php


abstract class BasePgType extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('pg_type');
    $this->hasColumn('oid', 'integer', null, array('type' => 'integer','length' => 4,'unsigned' => false,'notnull' => true,'primary' => true,));
    $this->hasColumn('typname', 'string', null, array('type' => 'string','notnull' => true,'primary' => false,));
    $this->hasColumn('typnamespace', 'blob', null, array('notnull' => true, 'primary' => false));
    $this->hasColumn('typowner', 'blob', null, array('notnull' => true, 'primary' => false));
    $this->hasColumn('typlen', 'integer', null, array('notnull' => true, 'primary' => false));
    $this->hasColumn('typbyval', 'boolean', 1, array('notnull' => true, 'primary' => false));
    $this->hasColumn('typtype', 'string', null, array('notnull' => true, 'primary' => false));
    $this->hasColumn('typisdefined', 'boolean', 1, array('notnull' => true, 'primary' => false));
    $this->hasColumn('typdelim', 'string', 1, array('notnull' => true, 'primary' => false));
    $this->hasColumn('typrelid', 'blob', null, array('notnull' => true, 'primary' => false));
    $this->hasColumn('typelem', 'blob', null, array('notnull' => true, 'primary' => false));
    $this->hasColumn('typarray', 'blob', null, array('notnull' => true, 'primary' => false));
    $this->hasColumn('typinput', 'string', null, array('notnull' => true, 'primary' => false));
    $this->hasColumn('typoutput', 'string', null, array('notnull' => true, 'primary' => false));
    $this->hasColumn('typreceive', 'string', null, array('notnull' => true, 'primary' => false));
    $this->hasColumn('typsend', 'string', null, array('notnull' => true, 'primary' => false));
    $this->hasColumn('typmodin', 'string', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('typmodout', 'string', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('typanalyze', 'string', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('typalign', 'string', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('typstorage', 'string', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('typnotnull', 'boolean', 1, array('notnull' => false, 'primary' => false));
    $this->hasColumn('typbasetype', 'blob', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('typtypmod', 'integer', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('typndims', 'integer', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('typdefaultbin', 'string', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('typdefault', 'string', null, array('notnull' => false, 'primary' => false));

  }

  public function setUp()
  {
    parent::setUp();
  }

}
