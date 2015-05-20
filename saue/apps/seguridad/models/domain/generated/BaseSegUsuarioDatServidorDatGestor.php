<?php

abstract class BaseSegUsuarioDatServidorDatGestor extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.seg_usuario_dat_servidor_dat_gestor');
        $this->hasColumn('idusuario', 'numeric', null, array ('notnull' => 'false','primary' => 'true'));
        $this->hasColumn('idservidor', 'numeric', null, array ('notnull' => 'false','primary' => 'true'));
        $this->hasColumn('idgestor', 'numeric', null, array ('notnull' => 'false','primary' => 'true'));
    }


}

