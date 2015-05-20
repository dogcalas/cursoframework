<?php

abstract class BaseDatEvento extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_traza.dat_evento');
        $this->hasColumn('orderby', 'ARRAY', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('condiciones', 'ARRAY', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('acctimestamp', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('semanticmodelreference', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('piid', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('accorggroup', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('orggroup', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('accorgrole', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('orgrole', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('timestamp', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('accorgresource', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('orgresource', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('accconceptinstance', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('conceptinstance', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('accconceptname', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('conceptname', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('idproceso', 'integer', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('descripcion', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('nombre', 'character varying', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('pl', 'character varying[]', null, array('notnull' => true, 'primary' => false));
        $this->hasColumn('idevento', 'decimal', null, array('ntype' => 'numeric', 'alltypes'
        => array(0 => 'decimal',), 'notnull' => true, 'primary' => true, 'sequence' => 'mod_traza.dat_evento_id_event',
        ));

    }

}

