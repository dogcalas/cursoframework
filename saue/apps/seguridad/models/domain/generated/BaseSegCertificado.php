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
abstract class BaseSegCertificado extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('mod_seguridad.seg_certificado');
    $this->hasColumn('idcertificado', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => true,
  'sequence' => 'mod_seguridad.sec_segcertificado',
));

    $this->hasColumn('mac', 'string', 255, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => true,
  'primary' => false,
));

    $this->hasColumn('valor', 'string', 255, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => true,
  'primary' => false,
));
    $this->hasColumn('idusuario', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => false,
));
    $this->hasColumn('fecha', 'date', null, array ('notnull' => true,'primary' => false));
    $this->hasColumn('hora', 'time without time zone', null, array ('notnull' => true,'primary' => false));
    $this->hasColumn('idsession', 'character varying', null, array ('notnull' => true,'primary' => false));
    $this->hasColumn('rol', 'text', null, array ('notnull' => true,'primary' => false));
    $this->hasColumn('entidad', 'text', null, array ('notnull' => true,'primary' => false));
    
  }


}