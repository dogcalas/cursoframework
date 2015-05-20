<?php

abstract class BaseDatConexion extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_traza.dat_conexion');
        $this->hasColumn('nombre', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('host', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('usuario', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('puerto', 'integer', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('contrasenna', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('bd', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('id', 'decimal', null, array('ntype' => 'numeric', 'alltypes'
        => array(0 => 'decimal',), 'notnull' => true, 'primary' => true, 'sequence' => 'mod_traza.dat_conexion_id_conexion',
        ));
    }


}

