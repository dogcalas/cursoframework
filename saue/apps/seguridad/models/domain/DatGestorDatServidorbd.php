<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @version 1.0-0
 */
class DatGestorDatServidorbd extends BaseDatGestorDatServidorbd
{

	public function setUp() {
	    parent::setUp();    
	    $this->hasOne('DatServidorbd',array('local'=>'idservidor','foreign'=>'idservidor'));
	    $this->hasOne('DatGestor',array('local'=>'idgestor','foreign'=>'idgestor')); 
	    $this->hasMany('DatServidorDatGestorDatBd',array('local'=>'idservidor','foreign'=>'idservidor'));
	    $this->hasMany('DatServidorDatGestorDatBd',array('local'=>'idgestor','foreign'=>'idgestor'));   
    }
    
  	static function eliminargestorservidor($ideservidor,$idgestor) {
		$query = Doctrine_Query::create();
		$query->delete()->from('DatGestorDatServidorbd s')->where("s.idgestor = ? and idservidor=?", array($idgestor,$ideservidor))->execute();
		return true;
  	}
  	
  	static public function comprobarservidor($idservidor) {
		$query = Doctrine_Query::create();
		$cant = $query->from('DatGestorDatServidorbd s')->where("s.idservidor = ?", $idservidor)->execute()->count();			
		return $cant;
  	}
  	
	static public function obtenercantnomgest($idgestor) {
		$query = Doctrine_Query::create();
		$cantFndes = $query->select('count(g.idgestor) cant')->from('DatGestor g,g.DatGestorDatServidorbd b')->where("b.idgestor = ?", $idgestor)->execute();                
		return $cantFndes[0]->cant;
	}

        static public function getSid($idgestor, $idservidor) {
		$query = Doctrine_Query::create();
		$cantFndes = $query->select('codigosid')->from('DatGestorDatServidorbd')->where("idgestor = ? AND idservidor = ?", array($idgestor, $idservidor))->execute();
		return $cantFndes[0]->codigosid;
	}
}