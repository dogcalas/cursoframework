<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNomCampoestruc extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_estructuracomp.nom_campoestruc');
        $this->hasColumn('idcampo', 'integer', 8, array('unsigned' => false, 'notnull' => true, 'primary' => true));
        $this->hasColumn('idnomeav', 'integer', 8, array('unsigned' => false, 'notnull' => false, 'primary' => false));
        $this->hasColumn('nombre', 'string', 255, array('fixed' => false, 'notnull' => true, 'primary' => false));
        $this->hasColumn('tipo', 'string', 255, array('fixed' => false, 'notnull' => true, 'primary' => false));
        $this->hasColumn('longitud', 'integer', 4, array('unsigned' => false, 'notnull' => true, 'primary' => false));
        $this->hasColumn('nombre_mostrar', 'string', 255, array('fixed' => false, 'notnull' => true, 'primary' => false));
        $this->hasColumn('regex', 'string', 255, array('fixed' => false, 'notnull' => true, 'primary' => false));
        $this->hasColumn('visible', 'bit', 1, array('fixed' => false, 'notnull' => true, 'primary' => false));
        $this->hasColumn('descripcion', 'string', 255, array('fixed' => false, 'notnull' => true, 'primary' => false));
        $this->hasColumn('tipocampo', 'string', 255, array('fixed' => false, 'notnull' => true, 'primary' => false));

    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('NomNomencladoreavestruc', array('local' => 'idnomeav', 'foreign' => 'idnomeav'));
        $this->hasMany('NomValorestruc', array('local' => 'idcampo', 'foreign' => 'idcampo'));
        $this->hasMany('NomFilaestruc', array('local' => 'idcampo', 'foreign' => 'idcampo', 'refClass' => 'NomValorestruc'));

    }

}
