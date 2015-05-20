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
class DatGestor extends BaseDatGestor
{
	public function setUp() {
	    parent::setUp();    
	    $this->hasMany('DatGestorDatServidorbd',array('local'=>'idgestor','foreign'=>'idgestor'));      
		$this->hasMany('DatSistemaDatServidores',array('local'=>'idgestor','foreign'=>'idgestor'));	
  	}
  	
	static public function comprobargestor($gestor) {
	    $query = Doctrine_Query::create();
	    $cant = $query->from('DatGestor b')->where("b.gestor = ?", array($gestor))->count();            
	    return $cant;
		}
  
	static function obtenercantgest($idservidor) {
    $query = Doctrine_Query::create();
    $cantFndes = $query->from('DatGestor g,g.DatGestorDatServidorbd b')->where("b.idservidor = ?", $idservidor)->count();
    return $cantFndes;
	}
		
 	function obtenercantgestsist($idgestor) {
	    $query = Doctrine_Query::create();
	    $cantFndes = $query->from('DatGestor g, g.DatSistemaDatGestor s')->where("s.idgestor = ?", $idgestor)->count();            
	    return $cantFndes;
	 	}
 	
    static public function getGestoresServidor($idservidor) {
        $query = Doctrine_Query::create();
        return $query->select('g.idgestor, g.gestor')->from('DatGestor g, g.DatGestorDatServidorbd s')->where("s.idservidor = ?", $idservidor)->execute();            
     }
    
	static function cargargestores($idservidor,$limit,$start) {
	    $query = Doctrine_Query::create();
	    $gestores = $query	->select('g.idgestor, g.gestor, g.puerto,g.descripcion, gbd.idservidor, bd.idservidor, s.ip')
	    					->from('DatGestor g')
	    					->innerJoin('g.DatGestorDatServidorbd gbd')
	    					->innerJoin('gbd.DatServidorbd bd')
	    					->innerJoin('bd.DatServidor s')
	    					->where("gbd.idservidor = ?", $idservidor)
	    					->limit($limit)
	    					->offset($start)
	    					->execute();
	    return $gestores;
		}	
		
	static function cargarnomgestores($limit,$start) {
    $query = Doctrine_Query::create();
    $gestores = $query->select('g.idgestor, g.gestor, g.puerto,g.descripcion')->from('DatGestor g ')->limit($limit)->offset($start)->execute();	        
    return $gestores;
	}
		
	static function obtenercantnomgestores() {
	    $query = Doctrine_Query::create();
	    $cantFndes = $query->from('DatGestor')->count();
	    return $cantFndes;
		}
		
	static public function obtenercantnomgestsist($idgestor) {
    $query = Doctrine_Query::create();
    $cant = $query->select('count(s.idgestor) cant')->from('DatSistemaDatServidores s')->where("s.idgestor = ?", $idgestor)->execute();            
    return $cant[0]->cant;
	}
	 	
	static function cargarcombogestores($idservidor) {
	    $query=Doctrine_Query::create();
	    $query1 = Doctrine_Query::create();
	    $adentro = $query->select('s.idgestor')->from('DatGestor s,s.DatGestorDatServidorbd a')->where("a.idservidor='$idservidor'")->execute();
	    $adentro1 = array();
	    foreach ($adentro as $obj)
	    $adentro1[] = $obj->idgestor;
	    $afuera = $query1->select('f.idgestor, f.gestor,f.puerto,f.descripcion')->from('DatGestor f')->whereNotIn('f.idgestor',$adentro1)->execute();
	    return $afuera;
		}
		
 	function obtenercantgestbd($idgestor) {
    $query = Doctrine_Query::create();
    $cantFndes = $query->from('DatGestor g, g.DatBdDatGestor b')->where("b.idgestor = ?", $idgestor)->count();            
    return $cantFndes;
	}
	 	
	static function obtenercantgestsistema($idservidor,$idgestor) {
	    $query = Doctrine_Query::create();
	    $cantFndes = $query->select('b.idservidor, g.idgestor')
	                    ->from('DatGestor g, g.DatSistemaDatServidores b')
	                    ->where("b.idservidor = ? and b.idgestor = ?", array($idservidor,$idgestor))
	                    ->count();            
	    return $cantFndes;
		} 
	 	
	static public function buscarnomgestores($gestor,$limit,$start) {
	    $query = Doctrine_Query::create();
	    $datos= $query->select('idgestor, gestor, puerto,descripcion')->from('DatGestor')->where("gestor like '%$gestor%'")->limit($limit)->offset($start)->execute();
	    return $datos;
		}
	
	static public function obtenercantnomgestoresbuscados($gestor) {
	    $query = Doctrine_Query::create();
	    $cant= $query->from('DatGestor')->where("gestor like '$gestor%'")->count();
	    return $cant;
		}
	
    static public function comboGestores() {
    $query = Doctrine_Query::create();
    return $query->select('idgestor, gestor, puerto')->from('DatGestor')->execute();
    }
    
	static function getGestorConfig($idsistema, $idservidor) {
        $query=Doctrine_Query::create();
        $datos = $query->select('g.gestor text, g.idgestor id')->from('DatGestor g')->innerJoin('g.DatSistemaDatServidores ss')->where("ss.idsistema =? and ss.idservidor =?",array($idsistema,$idservidor))->setHydrationMode(Doctrine :: HYDRATE_ARRAY)->execute();
        return $datos;
    }
    
    //-------------------------------------------------------
    static public function getGestorPorId($idgestor) {         
        $query = Doctrine_Query::create();
        return $query->select(' g.gestor')->from('DatGestor g')->where("g.idgestor = ?", $idgestor)->execute()->toArray();
     }
    static public function getPuertoPorId($idgestor) {         
        $query = Doctrine_Query::create();
        $puerto=$query->select('g.puerto')->from('DatGestor g')->where("g.idgestor = ?", $idgestor)->execute()->toArray();
        $puerto=$puerto[0]['puerto'];
        return $puerto;
     }
    static public function getIdPorDenominacionPuerto($gestor,$puerto) {         
        $query = Doctrine_Query::create();
        return $query->select(' g.idgestor')->from('DatGestor g')
                    ->where("g.gestor = ? and g.puerto=?", array($gestor,$puerto))->execute();
     }
}