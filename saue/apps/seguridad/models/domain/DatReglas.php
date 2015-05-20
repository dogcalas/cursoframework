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
class DatReglas extends BaseDatReglas
{

    public function setUp()
    {
    parent::setUp();
   
    }
    
    	//*****************************//
  static public function obtenerRegla($idfuncionalidad,$idfuncionalidadexcluida) {
    $query = Doctrine_Query::create();         
    $datos = $query->select('r.idfuncionalidad , r.idfuncionalidadexcluida')->from('DatReglas r')->where("r.idfuncionalidad =? ",array($idfuncionalidad))->whereIn('r.idfuncionalidadexcluida',$idfuncionalidadexcluida)->setHydrationMode( Doctrine :: HYDRATE_ARRAY )->execute();
    return $datos;
    }
//  function cargarFunexcluidas($idfuncionalidad)
//    {
//   $query = Doctrine_Query::create();
//    $funexcluidas = $query ->select('idfuncionalidad')
//        				->from('DatReglas')       				
//        				->where("idfuncionalidadexcluida =?",$idfuncionalidad)
//        				->execute()->toArray();
//        return $funexcluidas;
//		 
//    }
//
//    function estaFuncionalidad($idfuncionalidad)
//    {
//   $query = Doctrine_Query::create();
//    $datos = $query ->select('r.idfuncionalidadexcluida')
//        				->from('DatReglas r')
//        				->where("idfuncionalidad =?",$idfuncionalidad)
//        				->execute()->toArray();
//        return $datos;
//
//    }
//    
//	
///*	static public function obtenerRegla($idfuncionalidad,$idfuncionalidadexcluida) {
//    $query = Doctrine_Query::create();         
//    $datos = $query->select('r.idfuncionalidad , r.idfuncionalidadexcluida')->from('DatReglas r')->where("idfuncionalidad =? ",array($idfuncionalidad))->whereIn("r.idfuncionalidadexcluida",$idfuncionalidadexcluida)->execute()->toArray(); 
//    return $datos;
//    }*/
//	
//	
//	
//	
//	
//	
//	
// function nombreFuncionalidad($id) {
//   $query = Doctrine_Query::create();
// $nombre = $query ->select('denominacion')
//        				->from('dat_funcionalidad')
//        				->where("idfuncionalidad =?",$id)
//        				->execute();
//        return $nombre;
//   
//    }
  	
}
 
