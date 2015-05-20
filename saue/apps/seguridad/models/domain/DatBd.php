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
class DatBd extends BaseDatBd
{
	public function setUp() {
	    parent::setUp();     
	    $this->hasMany('DatServidorDatGestorDatBd',array('local'=>'idbd','foreign'=>'idbd'));   
		$this->hasMany('DatSistemaDatServidores',array('local'=>'idbd','foreign'=>'idbd'));	
  	}
  
	static public function cargarbd($idservidor,$idgestor,$limit,$start)
	{
    $q = Doctrine_Query::create();					
    $result = $q->select('b.idbd id,b.denominacion text,b.descripcion')->from('DatBd b,b.DatServidorDatGestorDatBd g')->where("g.idservidor = ? and g.idgestor= ?", array($idservidor,$idgestor))->orderby('b.idbd')->limit($limit)->offset($start)->execute();
    return $result;
	}
	
	static function obtenercantbd($idservidor,$idgestor)
	{
    $query = Doctrine_Query::create();
    $result = $query->from('DatBd b,b.DatServidorDatGestorDatBd g')->where("g.idservidor = ? and g.idgestor= ?", array($idservidor,$idgestor))->count();
    return $result;
	} 
		
	static public function obtenercantgestsistema($idservidor,$idgestor,$idbd)
	{
    $query = Doctrine_Query::create();
    $cant = $query->from('DatSistemaDatServidores u')->where("u.idservidor = ? and u.idgestor =? and u.idbd =?", array($idservidor,$idgestor,$idbd))->count();
    return $cant;
	} 
	
	static function cargarcombobd($idservidor,$idgestor)
	{
    $query=Doctrine_Query::create();
    $query1 = Doctrine_Query::create();
    $adentro = $query->select('s.idbd')->from('Datbd s,s.DatServidorDatGestorDatBd a')->where("a.idservidor=? and a.idgestor = ?", array($idservidor,$idgestor))->execute();
    $adentro1 = array();
    foreach ($adentro as $obj)
    	$adentro1[] = $obj->idbd;
    $afuera = $query1->select('f.idbd, f.denominacion')->from('Datbd f')->whereNotIn('f.idbd',$adentro1)->execute();
    return $afuera;
	}
		
	static function cargarnombd($limit,$start)
	{	
    $query = Doctrine_Query::create();
    $bd = $query->select('b.idbd, b.denominacion,b.descripcion')->from('DatBd b ')->limit($limit)->offset($start)->execute();	        
    return $bd;
	}
		
	static function obtenercantnombd()
	{
    $query = Doctrine_Query::create();
    $cant = $query->from('DatBd')->count();	        
    return $cant;
	}	
		
	static function obtenercantnombdsist($idbd)
	{
    $query = Doctrine_Query::create();
    $cant = $query->from('DatSistemaDatServidores s')->where("s.idbd=?",$idbd)->count();	     
    return $cant;
	}	
		
	static public function buscarnombd($denominacion,$limit,$start)
	{
    $query = Doctrine_Query::create();
    $datos = $query->select('idbd, denominacion, descripcion')->from('DatBd')->where("denominacion like '$denominacion%'")->limit($limit)->offset($start)->execute();	        
    return $datos;
	}
		
	static public function obtenercantnombdbuscados($denominacion)
	{
    $query = Doctrine_Query::create();
    $cant = $query->from('DatBd')->where("denominacion like '$denominacion%'")->count();	        
    return $cant;
	}
	
	static public function borrarBdFisicamente ($arrBd) {
		$query = Doctrine_Query::create();
		$datos = $query	->delete()
						->from('DatBd')
						->whereIn('idbd', $arrBd)
						->execute();
	}
	
	static public function getBdConfig($idsistema, $idservidor, $idgestor) {
		$query = Doctrine_Query::create();
		$datos = $query->select('b.denominacion text, b.idbd id')
						->from('DatBd b')
						->innerJoin('b.DatSistemaDatServidores ss')
						->where("ss.idsistema =? and ss.idservidor =? and ss.idgestor =?",array($idsistema, $idservidor, $idgestor))
						->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
						->execute();
		return $datos;
	}
        
        ///-------------------------------------
         static public function getNomBdPorId($idbd)
	{
    $query = Doctrine_Query::create();
    $datos = $query->select('denominacion')->from('DatBd')->where('idbd =?',$idbd)->execute()->toArray();
    return $datos;
	}
}
