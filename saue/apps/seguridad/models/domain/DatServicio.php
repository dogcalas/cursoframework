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
class DatServicio extends BaseDatServicio
{
	public function setUp()
	{
		parent::setUp();    
		$this->hasOne('DatSistema',array('local'=>'idsistema','foreign'=>'idsistema'));
		$this->hasMany('DatServicioDatSistema',array('local'=>'idservicio','foreign'=>'idservicio')); 
		$this->hasMany('DatFunciones',array('local'=>'idservicio','foreign'=>'idservicio'));
	  }
	
	static function obtenerServicio($idsistema, $limit, $start)
	{ 
    $query = Doctrine_Query::create();
    $fndes = $query->select('idservicio,denominacion,descripcion,wsdl')->from('DatServicio')->where("idsistema =?",$idsistema)->orderby('idservicio')->limit($limit)->offset($start)->execute();
    return $fndes;			    
	}
			
	static function cantserviciop($idsistema)
	{
    $query = Doctrine_Query::create();
    $cantFndes = $query->from('DatServicio')->where("idsistema=?",$idsistema)->count();
    return $cantFndes;
	}
	
	static function cargarser($limit, $start)
	{
    $query = Doctrine_Query::create();
    $fndes = $query->select('idservicio,denominacion')->from('DatServicio')->orderby('idservicio')->limit($limit)->offset($start)->execute();
    return $fndes;			    
	}
	public static function verificarserviciop($denominacion)
	{
	$query = Doctrine_Query::create(); 
	$var = $query->from('DatServicio s')
                  ->where("s.denominacion = ?", $denominacion)->count();
	return $var;
	}
	static public function obtenerServPrestaSistema($idsistema) {
		            $query = Doctrine_Query::create();
		            $datos = $query->select('a.idservicio id,a.idservicio,a.denominacion')->from('DatServicio a')->where("a.idsistema = ?", $idsistema)->setHydrationMode( Doctrine :: HYDRATE_ARRAY )->execute();
		            return $datos;
			}
        static public function obtenerWSDLSistema($idsistema) {
            $query = Doctrine_Query::create();
            $datos = $query->select('a.wsdl')->from('DatServicio a')->where("a.idsistema = ?", $idsistema)->setHydrationMode( Doctrine :: HYDRATE_ARRAY )->execute();
            return $datos;
        }
}