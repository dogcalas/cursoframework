<?php

abstract class BaseDatProcess extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_traza.dat_process');
        $this->hasColumn('esquemas', 'ARRAY', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('tablas', 'ARRAY', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('idconexion', 'integer', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('fuentedatos', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('descripcion', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('nombre', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('instancia', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('activado', 'numeric', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('version', 'numeric', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('modificarversion', 'numeric', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('validado', 'numeric', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('idproceso', 'decimal', null, array('ntype' => 'numeric', 'alltypes'
        => array(0 => 'decimal',), 'notnull' => true, 'primary' => true, 'sequence' => 'mod_traza.dat_process_id_proceso',
        ));
    }


}

