<?php

abstract class BaseNomPropComercial extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.nom_prop_comercial');
        //$this->hasColumn('nombre_t', 'character varying', null, array ('notnull' => false,'primary' => true));
        $this->hasColumn('bloq_font', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('bloq_underline', 'boolean', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('bloq_italic', 'boolean', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('bloq_bold', 'boolean', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('bloq_size', 'integer', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('bloq_color', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('bloq_header_background', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('bloq_header_color', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('nav_hover', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('nav_color_hover', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('nav_background', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('nav_color', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('slogan_font', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('slogan_underline', 'boolean', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('slogan_italic', 'boolean', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('slogan_bold', 'boolean', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('slogan_size', 'integer', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('slogan_color', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('bar_opacity', 'integer', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('body_opacity', 'integer', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('bar_background', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('body_background', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('footer_background', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('nav_texture', 'boolean', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('nav_color_texture', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('idtema', 'numeric', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('idcomercial', 'numeric', null, array ('notnull' => false,'primary' => true));
    }


}

