<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien García Tejo
 * @author Julio Cesar García Mosquera  
 * @version 1.0-0
 */
class DatSistemaSegRol extends BaseDatSistemaSegRol
{

   public function setUp() {
    parent::setUp();    
    $this->hasOne('SegRol',array('local'=>'idrol','foreign'=>'idrol'));
    $this->hasOne('DatSistema',array('local'=>'idsistema','foreign'=>'idsistema'));
    $this->hasMany('DatSistemaSegRolDatFuncionalidad',array('local'=>'idrol','foreign'=>'idrol'));  
    $this->hasMany('DatSistemaSegRolDatFuncionalidad',array('local'=>'idsistema','foreign'=>'idsistema'));     
  }
  
   static public function sistemasdadorol($idrol) {
    $q = Doctrine_Query::create();
    $result = $q->select('DISTINCT (idsistema) idsistema')->from('DatSistemaSegRol')->where("idrol = ?",$idrol)->orderby('idsistema')->execute();
    return $result;       
  }

   static public function eliminarrol($valorsistema,$rol) {
    $query = Doctrine_Query::create();
    $query->delete()->from('DatSistemaSegRol')->where("idsistema =? and idrol=?",array($valorsistema,$rol))->execute();   
    return true;
   }
   
  static function cargarentidadesrol($idrol)
  {
    $q = Doctrine_Query::create();
    $result = $q->select('identidad')->from('DatSistemaDatEntidadSegRol')->where("idrol = ?",$idrol)->execute();
    return $result;
  
  }
  
    static public function eliminarrolsistema($idrol,$idsistema)
    {

        $query = Doctrine_Query::create();            
        $query->delete()->from('DatSistemaSegRol')->where("idrol = ? and idsistema = ?", array( $idrol,$idsistema))->execute();
        return true;
    }  
  
  static public function cantidadentidadsistemarol($idsistema,$identidad) 
    {
        $query = Doctrine_Query::create();            
        $result = $query->from('DatSistemaDatEntidadSegRol')->where("idsistema = ? and identidad = ?", array( $idsistema,$identidad))->count();
        return $result;
    }
}
