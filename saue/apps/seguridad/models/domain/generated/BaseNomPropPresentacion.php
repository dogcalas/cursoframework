<?php

abstract class BaseNomPropPresentacion extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.nom_prop_presentacion');
        //$this->hasColumn('nombre_tema', 'character varying', null, array ('notnull' => false,'primary' => true));
        $this->hasColumn('iconos', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('ventanas', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('idtema', 'numeric', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('idpresentacion', 'numeric', null, array ('notnull' => false,'primary' => true));
    }


}

