<?php

abstract class BaseDatImagenesUsuario extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.dat_imagenes_usuario');
        $this->hasColumn('idusuario', 'numeric', null, array ('notnull' => false,'primary' => true));
        $this->hasColumn('nombreimage', 'character varying', null, array ('notnull' => 'false','primary' => true));
         
        $this->hasColumn('fecha', 'date', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('imagen', 'bytea', null, array ('notnull' => false,'primary' => false));
    }


}

