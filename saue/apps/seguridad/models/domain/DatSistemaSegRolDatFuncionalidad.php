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
class DatSistemaSegRolDatFuncionalidad extends BaseDatSistemaSegRolDatFuncionalidad
{

    public function setUp()
    {
    parent::setUp();     
    $this->hasOne('DatFuncionalidad', array('local'=>'idfuncionalidad', 'foreign'=>'idfuncionalidad')); 
    $this->hasOne('DatSistemaSegRol', array('local'=>'idsistema', 'foreign'=>'idsistema'));    
    $this->hasOne('DatSistemaSegRol', array('local'=>'idrol', 'foreign'=>'idrol'));    
    $this->hasMany('DatSistemaSegRolDatFuncionalidadDatAccion', array('local'=>'idsistema', 'foreign'=>'idsistema'));    
    $this->hasMany('DatSistemaSegRolDatFuncionalidadDatAccion', array('local'=>'idrol', 'foreign'=>'idrol')); 
    $this->hasMany('DatSistemaSegRolDatFuncionalidadDatAccion', array('local'=>'idfuncionalidad', 'foreign'=>'idfuncionalidad')); 
    }
  
    static public function obtenerfunrol($idrol,$idsistema)
    {
        $query = Doctrine_Query::create();            
        $fndes = $query->select('idfuncionalidad,idsistema')->from('DatSistemaSegRolDatFuncionalidad')->where("idrol = ? and idsistema = ? ", array( $idrol,$idsistema))->execute();
        return $fndes;
    }

    static public function eliminarfuncionalidad($idrol,$idfuncionalidad,$idsistema)
    {
    $query = Doctrine_Query::create(); 
    $query->delete()->from('DatSistemaSegRolDatFuncionalidad')->where("idrol=? and idfuncionalidad=? and idsistema=?",array($idrol,$idfuncionalidad,$idsistema))->execute(); 
    return true;
    }
  
    static public function cargarsist_funcionalidades($idsistema,$rol)
    {
        $query = Doctrine_Query::create();         
        $fndes = $query->select('f.idfuncionalidad id, f.referencia, f.denominacion text, f.descripcion, f.icono,true leaf, f.idsistema')->from('DatFuncionalidad f, f.DatSistemaSegRolDatFuncionalidad serf')->where("serf.idsistema = ? and  serf.idrol =?", array($idsistema,$rol))->orderby('f.idfuncionalidad')->execute();
        return $fndes;
    }
    
    static public function tieneFuncionalidad($idrol,$idsistema)
    {
        $query = Doctrine_Query::create();            
        return $query->from('DatSistemaSegRolDatFuncionalidad')->where("idrol = ? and idsistema = ? ", array( $idrol,$idsistema))->count();
    }
    
    static function cantidadsisrol($idrol)
  {
    $q = Doctrine_Query::create();
    return $q->select("DISTINCT idsistema")->from('DatSistemaSegRolDatFuncionalidad')->where("idrol = ?",$idrol)->groupby("idsistema")->count();
  }
    
  ///---------------------------------------------------------------------------
   static public function eliminarXrol($idrol){

        $query = Doctrine_Query::create();
        $query->delete()->from('DatSistemaSegRolDatFuncionalidad')->where("idrol = ?", array( $idrol))->execute();
        return true;
    } 
    
     static public function eliminarXSQL($rol,$idsistema,$idfuncionalidades)
    {
    $query = Doctrine_Query::create(); 
    $query->delete()->from('DatSistemaSegRolDatFuncionalidad')
            ->where('idrol=? and idsistema=?',array($rol,$idsistema))
            ->whereIn('idfuncionalidad',$idfuncionalidades)->execute(); 
    }
  
}