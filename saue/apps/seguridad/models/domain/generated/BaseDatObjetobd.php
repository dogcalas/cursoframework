<?php

abstract class BaseDatObjetobd extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.dat_objetobd');
        $this->hasColumn('objeto', 'character varying', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('idobjeto', 'numeric', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('idobjetobd', 'numeric', null, array ('notnull' => false,'primary' => true,'sequence' => 'mod_seguridad.sec_datobjetobd'));
        $this->hasColumn('idesquema', 'numeric', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('idbd', 'numeric', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('idgestor', 'numeric', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('idservidor', 'numeric', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('idrolesbd', 'numeric', null, array ('notnull' => false,'primary' => false));
    }


}

