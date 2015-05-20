<?php

abstract class BaseHisProceso extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_traza.his_proceso');
        $this->hasColumn('idtraza', 'numeric', null, array('notnull' => false, 'primary' => true));
        $this->hasColumn('idobjeto', 'numeric', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('instancia', 'numeric', null, array('notnull' => true, 'primary' => false));
    }


}

