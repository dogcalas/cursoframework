<?php

abstract class BaseDatServicioioc extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.dat_servicioioc');
        $this->hasColumn('subsistema', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('denominacion', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('idservicio', 'numeric', null, array ('notnull' => true,'primary' => true,'sequence' => 'mod_seguridad.sec_datservicioioc'));
    }


}

