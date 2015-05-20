<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */
abstract class BaseNomObjetospermisos extends Doctrine_Record
{

  public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.nom_objetospermisos');
        $this->hasColumn('id', 'numeric', null, array ('notnull' => false,'primary' => true,));
        $this->hasColumn('nombreobjeto', 'character varying', null, array ('notnull' => false,'primary' => false,));
        $this->hasColumn('descripcion', 'character varying', null, array ('notnull' => true,'primary' => false,));
        $this->hasColumn('idobj', 'numeric', null, array ('notnull' => true,'primary' => false,));
    }



}
