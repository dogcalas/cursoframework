<?php

abstract class BaseSegRolDatServidorDatGestor extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.seg_rol_dat_servidor_dat_gestor');
        $this->hasColumn('idrol', 'numeric', null, array ('notnull' => 'false','primary' => 'true'));
        $this->hasColumn('idservidor', 'numeric', null, array ('notnull' => 'false','primary' => 'true'));
        $this->hasColumn('idgestor', 'numeric', null, array ('notnull' => 'false','primary' => 'true'));
        $this->hasColumn('idbd', 'numeric', null, array ('notnull' => 'false','primary' => 'true'));
        $this->hasColumn('idrolbd', 'numeric', null, array ('notnull' => 'false','primary' => 'true'));
        $this->hasColumn('denominacion', 'character varying', null, array('notnull' => 'false', 'primary' => 'false'));
    }


}

