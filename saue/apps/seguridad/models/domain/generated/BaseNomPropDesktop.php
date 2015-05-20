<?php

abstract class BaseNomPropDesktop extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.nom_prop_desktop');
        $this->hasColumn('inicio_font_size', 'integer', null, array ('notnull' => true,'primary' => false));
       // $this->hasColumn('nombre_tema', 'character varying', null, array ('notnull' => false,'primary' => true));
        $this->hasColumn('inicio_tipo_letra', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('inicio_underline', 'boolean', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('inicio_italic', 'boolean', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('inicio_bold', 'boolean', null, array ('notnull' => true,'primary' => false));
       // $this->hasColumn('icono', 'character varying', null, array ('notnull' => true,'primary' => false));
      //  $this->hasColumn('tema', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('inicio_texto_hex', 'character varying', null, array ('notnull' => true,'primary' => false));
       // $this->hasColumn('inicio_fondo_rgb', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('inicio_fondo_hex', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('inicio_hex', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('tarea_hex', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('shadow', 'boolean', null, array ('notnull' => true,'primary' => false));
      //  $this->hasColumn('barra_hex_inf', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('barra_hex_sup', 'character varying', null, array ('notnull' => true,'primary' => false));
		$this->hasColumn('menu_header', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('menu_dock', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('menu_dock_opacity', 'integer', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('iddesktop', 'numeric', null, array ('notnull' => false,'primary' => true));
        $this->hasColumn('idtema', 'numeric', null, array ('notnull' => false,'primary' => false));
    }


}

