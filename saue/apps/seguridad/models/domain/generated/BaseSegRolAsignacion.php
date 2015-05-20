<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Janier Treto Portal
 * @author Hector David Peguero Alvarez

 * @version 1.0-0
 */
abstract class BaseSegRolAsignacion extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_seguridad.seg_rol_asignacion');
        $this->hasColumn('idrol', 'numeric', null, array ('notnull' => false,'primary' => true));
        $this->hasColumn('idrolasig', 'numeric', null, array ('notnull' => false,'primary' => true));
    }


}

