<?php

abstract class BaseNomTipoconex extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.nom_tipoconex');
        $this->hasColumn('descripcion', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('denominacion', 'character varying', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('seleccion', 'boolean', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('idconexion', 'numeric', null, array ('notnull' => true,'primary' => true, 'sequence' => 'sec_nomtipoconexion',));
        $this->hasColumn('tipo', 'numeric', null, array ('notnull' => true,'primary' => false));
    }


}

