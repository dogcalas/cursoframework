<?php

abstract class BaseDatAccionDatObjetobd extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.dat_accion_dat_objetobd');
        $this->hasColumn('idobjetobd', 'numeric', null, array ('notnull' => true,'primary' => true));
        $this->hasColumn('idaccion', 'numeric', null, array ('notnull' => true,'primary' => true));
        $this->hasColumn('privilegios', 'character varying', null, array ('notnull' => false,'primary' => false));
    }


}

