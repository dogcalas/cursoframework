<?php

abstract class BaseDatFuncionalidadesRestringidasUsuarioEntidadRol extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.dat_funcionalidades_restringidas_usuario_entidad_rol');
        $this->hasColumn('idusuario', 'numeric', null, array('notnull' => false, 'primary' => true));
        $this->hasColumn('idrol', 'numeric', null, array('notnull' => false, 'primary' => true));
        $this->hasColumn('identidad', 'numeric', null, array('notnull' => false, 'primary' => true));
        $this->hasColumn('idfuncionalidad', 'numeric', null, array('notnull' => false, 'primary' => true));
        $this->hasColumn('idsistema', 'numeric', null, array('notnull' => false, 'primary' => true));
    }


}

