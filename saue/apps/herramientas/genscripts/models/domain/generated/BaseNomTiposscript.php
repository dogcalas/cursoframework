<?php

abstract class BaseNomTiposscript extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_generacionscript.nom_tiposscript');
        $this->hasColumn('id_tiposcript', 'intiger', null, array ('notnull' => false,'primary' => true, 'autoincrement' => true, 'sequence' => 'mod_generacionscript.nom_tiposcript_id_tiposcript_seq'));
        $this->hasColumn('nombre', 'character varying', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('descripcion', 'character varying', null, array ('notnull' => false,'primary' => false));
    }


}

