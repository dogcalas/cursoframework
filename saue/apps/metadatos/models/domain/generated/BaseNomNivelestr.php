<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNomNivelestr extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_estructuracomp.nom_nivelestr');
        $this->hasColumn('idnivelestr', 'integer', 2, array('unsigned' => false, 'notnull' => true, 'primary' => true));
        $this->hasColumn('abrevnivelestr', 'string', 15, array('fixed' => false, 'notnull' => false, 'primary' => false));
        $this->hasColumn('dennivelestr', 'string', 35, array('fixed' => false, 'notnull' => false, 'primary' => false));
        $this->hasColumn('orden', 'integer', 2, array('unsigned' => false, 'notnull' => false, 'primary' => false));
        $this->hasColumn('fechaini', 'date', null, array('notnull' => false, 'primary' => false));
        $this->hasColumn('fechafin', 'date', null, array('notnull' => false, 'primary' => false));
    }

    public function setUp()
    {
        parent::setUp();
    }

}
