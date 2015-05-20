<?php

abstract class BaseDatScript extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_generacionscript.dat_script');
        $this->hasColumn('id_script', 'decimal', null, array ('notnull' => false,'primary' => true, 'autoincrement' => true, 'sequence' => 'mod_generacionscript.dat_script_id_script_seq'));
        $this->hasColumn('nombre_paquete', 'character varying', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('nombre_sistema', 'character varying', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('version_sistema', 'character varying', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('version_script', 'character varying', null, array ('notnull' => false,'primary' => false));        
        $this->hasColumn('id_tiposcript', 'intiger', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('usuario', 'character varying', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('fecha', 'date', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('ip_host', 'character varying', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('definicionsql', 'text', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('nombre_script', 'character varying', null, array ('notnull' => false,'primary' => false));
    }


}

