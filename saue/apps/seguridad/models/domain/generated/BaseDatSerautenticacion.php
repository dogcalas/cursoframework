<?php

abstract class BaseDatSerautenticacion extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.dat_serautenticacion');
        $this->hasColumn('idservidor', 'numeric', null, array ('notnull' => false,'primary' => true));
        $this->hasColumn('tservidor', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('usuario', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('clave', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('basedn', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('puerto', 'numeric', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('ssl', 'numeric', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('tsl', 'numeric', null, array ('notnull' => true,'primary' => false));
    }


}

