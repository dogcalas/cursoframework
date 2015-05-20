<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseHisTraza extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('mod_traza.his_traza');
        $this->hasColumn('idtraza', 'decimal', null, array('type' => 'decimal', 'primary' => true, 'autoincrement' => true, 'sequence' => 'mod_traza.his_traza_idtraza_seq'));
        $this->hasColumn('fecha', 'date', null, array('type' => 'date'));
        $this->hasColumn('hora', 'time', null, array('type' => 'time'));
        $this->hasColumn('idtipotraza', 'decimal', null, array('type' => 'decimal', 'notnull' => true));
        $this->hasColumn('usuario', 'string', 50, array('type' => 'string', 'length' => '50', 'notnull' => true));
        $this->hasColumn('idestructuracomun', 'decimal', null, array('type' => 'decimal', 'notnull' => true));
        $this->hasColumn('ip_host', 'string', 20, array('type' => 'string', 'length' => '20', 'notnull' => true));
        $this->hasColumn('idrol', 'decimal', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('iddominio', 'decimal', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('rol', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('dominio', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('estructuracomun', 'character varying', null, array('notnull' => true, 'primary' => false));
    }

}
