<?php

abstract class BaseNomAutenticacion extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.nom_autenticacion');
        $this->hasColumn('activo', 'numeric', null, array ('notnull' => true,'primary' => false,'default' => 0));
        $this->hasColumn('descripcion', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('abreviatura', 'character varying', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('denominacion', 'character varying', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('idautenticacion', 'numeric', null, array ('notnull' => false,'primary' => true));
    }


}

