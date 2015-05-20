<?php

abstract class BaseDatServicioObjetobd extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.dat_servicio_objetobd');
        $this->hasColumn('idservicio', 'numeric', null, array ('notnull' => false,'primary' => true));
        $this->hasColumn('idobjetobd', 'numeric', null, array ('notnull' => false,'primary' => true));
        $this->hasColumn('privilegios', 'character varying', null, array ('notnull' => false,'primary' => false));
        
        
    }


}

