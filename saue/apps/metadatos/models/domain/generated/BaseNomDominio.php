<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNomDominio extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_estructuracomp.nom_dominio');
        $this->hasColumn('iddominio', 'integer', 8, array('unsigned' => false, 'notnull' => true, 'primary' => true, 'sequence' => 'sec_nomdominio'));
        $this->hasColumn('denominacion', 'string', 255, array('fixed' => false, 'notnull' => false, 'primary' => false));
        $this->hasColumn('descripcion', 'string', 250, array('fixed' => false, 'notnull' => false, 'primary' => false));
        $this->hasColumn('dominio', 'integer', 400, array('fixed' => false, 'notnull' => false, 'primary' => false));
        $this->hasColumn('dominiostring', 'string', null, array('ntype' => 'text', 'alltypes' => array(0 => 'string', 1 => 'clob',), 'fixed' => false, 'notnull' => false, 'primary' => false,));
        $this->hasColumn('seguridad', 'integer', 4, array('ntype' => 'int4', 'alltypes' => array(0 => 'integer'), 'unsigned' => false, 'notnull' => false, 'default' => '0', 'primary' => false,));
        $this->hasColumn('idpadre', 'integer', 8, array('unsigned' => false, 'notnull' => true, 'primary' => false));
        $this->hasColumn('lft', 'decimal', null, array('notnull' => false, 'primary' => false));
        $this->hasColumn('rgt', 'decimal', null, array('notnull' => false, 'primary' => false));
    }

    public function setUp()
    {
        parent::setUp();
    }

}
