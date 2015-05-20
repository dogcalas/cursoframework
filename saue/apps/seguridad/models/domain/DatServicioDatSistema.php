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
class DatServicioDatSistema extends BaseDatServicioDatSistema
{

	public function setUp()
    {
    parent::setUp();    
    $this->hasOne('DatSistema',array('local'=>'idsistema','foreign'=>'idsistema')); 
    $this->hasOne('DatServicio',array('local'=>'idservicio','foreign'=>'idservicio'));  
  }

	static function cargarserviciocons($idsistema, $limit, $start)
	{
	  $query=Doctrine_Query::create();
	  $datos=$query->select('f.idservicio, f.denominacion, f.descripcion, f.wsdl, f.idsistema')->from('DatServicio f, f.DatServicioDatSistema g')->where("g.idsistema=?",$idsistema)->limit($limit)->offset($start)->execute();
	  return $datos;
	}
    			
	static function obtenercantsercons($idsistema)
	{
    $query = Doctrine_Query::create();
    $cantFndes = $query->from('DatServicio s, s.DatServicioDatSistema m')->where("m.idsistema = ?", $idsistema)->count();
    return $cantFndes;
	}
           	
	static function buscarservicioscons($idservicio,$idsistema) {
    $query = Doctrine_Query::create();
    $query->delete()->from('DatServicioDatSistema')->where("idservicio = ? and idsistema = ?", array($idservicio,$idsistema))->execute();      			           
	}
    
	static function cargarservicionocons($idsistema)
	{
	  $query=Doctrine_Query::create();
	  $query1 = Doctrine_Query::create();
	  $adentro = $query->select('s.idservicio')->from('DatServicio s,s.DatServicioDatSistema a')->where("a.idsistema='$idsistema'")->execute();
	  $adentro1 = array();
	  foreach ($adentro as $obj)
	  	$adentro1[] = $obj->idservicio;
	  $afuera = $query1->select('f.idservicio, f.denominacion')->from('DatServicio f')->whereNotIn('f.idservicio',$adentro1)->execute();
	   return $afuera;
	}
	
		
}