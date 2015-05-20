<?php

abstract class BaseDatRegistroProceso extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_traza.dat_registro_proceso');
        $this->hasColumn('id_registro', 'numeric', null, array('notnull' => true, 'primary' => true));
        $this->hasColumn('fecha', 'date', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('id_proceso', 'numeric', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('accion', 'character varying', null, array('notnull' => true, 'primary' => false));

    }


}

