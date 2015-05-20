<?php

abstract class BaseSegDatosReconocimiento extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.seg_datos_reconocimiento');
        $this->hasColumn('metodorec', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('ndescomposicion', 'numeric', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('metodoknn', 'character varying', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('metododistancia', 'character varying', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('iddatosreconocimiento', 'numeric', null, array ('notnull' => false,'primary' => true));
    }


}

