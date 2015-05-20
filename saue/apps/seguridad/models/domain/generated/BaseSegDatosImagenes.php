<?php

abstract class BaseSegDatosImagenes extends Doctrine_Record
{

    public function setTableDefinition()
    {

        
        $this->setTableName('mod_seguridad.seg_datos_imagenes');
        $this->hasColumn('formato', 'character varying', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('forma', 'character varying', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('cantimg', 'numeric', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('contraste', 'numeric', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('brillo', 'numeric', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('ancho', 'numeric', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('alto', 'numeric', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('iddatosimg', 'numeric', null, array ('notnull' => false,'primary' => true));
    }


}

