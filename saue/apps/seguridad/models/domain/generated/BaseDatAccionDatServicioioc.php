<?php

abstract class BaseDatAccionDatServicioioc extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.dat_accion_dat_servicioioc');
        $this->hasColumn('idservicio', 'numeric', null, array ('notnull' => false,'primary' => true));
        $this->hasColumn('idaccion', 'numeric', null, array ('notnull' => false,'primary' => true));
    }


}

