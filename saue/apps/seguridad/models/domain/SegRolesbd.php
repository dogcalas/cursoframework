<?php
/*
 *Roles de bases de dato.
 *
 * @package Acaxia
 * @copyright UCID-ERP Cuba
 * @author Darien Garc�a Tejo
 * @version 2.0-0
 */
class SegRolesbd extends BaseSegRolesbd
{

	public function setUp() {
	    parent::setUp();
		$this->hasOne('DatSistemaDatServidores',array('local'=>'idrolesbd','foreign'=>'idrolesbd'));
  	}
  	
  	static public function exist($rolname,$idgestor,$idservidor) {
  		$query = Doctrine_Query::create();
  		$result = $query->select('idrolesbd, passw')
  						->from('SegRolesbd')
  						->where("nombrerol =? and idservidor =? and idgestor =?",array($rolname, $idservidor, $idgestor))
  						->execute();                
  		return $result;
  	}
  	
  	static public function deleteRol($idrolesbd) {
  		$query = Doctrine_Query::create();
  		$query->delete()->from('SegRolesbd')->where("idrolesbd =?",$idrolesbd)->execute();
  	}
  	
  	static public function loadRoleBD($idservidor, $idgestor) {
  		$query = Doctrine_Query::create();
  		$datos = $query->select('idrolesbd id,nombrerol, passw')
  						->from('SegRolesbd')
  						->where("idservidor =? AND idgestor =?", array($idservidor, $idgestor))
  						->execute();
  		return $datos;
  	}
  	
	static public function deleteRoles($arrayDelete) {
  		$query = Doctrine_Query::create();
  		$query->delete()->from('SegRolesbd')->whereIn('nombrerol',$arrayDelete)->execute();
  	}
  	
  	static public function getRolInformation($arrayReturn) {
  		$query = Doctrine_Query::create();
  		return $query->select('idrolesbd id,nombrerol rolname')->from('SegRolesbd')->whereIn('nombrerol',$arrayReturn)->execute();
  	}
        
        //----------------------------------------------------
        static public function getNombreyPassXidrol($idrol) {
  		$query = Doctrine_Query::create();
  		return $query->select('nombrerol,passw')->from('SegRolesbd')->where("idrolesbd=?",array($idrol))->execute()->toArray();
  	}
}