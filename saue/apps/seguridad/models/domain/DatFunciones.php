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
class DatFunciones extends BaseDatFunciones
{

	public function setUp()
	{
		    parent::setUp();    
		    $this->hasOne('DatServicio',array('local'=>'idservicio','foreign'=>'idservicio'));
		    $this->hasMany('DatParametros',array('local'=>'idfunciones','foreign'=>'idfunciones'));		   
		  }

    static function cargarfunciones($idservicio,$limit,$start)
	{
    $query = Doctrine_Query::create();
    $fndes = $query->select('idfunciones,denominacion, descripcion,idservicio')->from('DatFunciones')->where("idservicio = ?", $idservicio)->orderby('idfunciones')->limit($limit)->offset($start)->execute();
    return $fndes;
	}
		
	static function obtenercantfunc($idservicio)
	{
    $query = Doctrine_Query::create();
    $cantFndes = $query->from('DatFunciones f')->where("f.idservicio = ?", $idservicio)->count();
    return $cantFndes;
	} 
	public static function verificarfunciones($denominacion)
	{
	$query = Doctrine_Query::create(); 
	$var = $query->from('DatFunciones f')
                  ->where("f.denominacion = ?", $denominacion)->count();
	return $var;
	}
	static public function obtenerFuncionesServicio($idservicio) {
		            $query = Doctrine_Query::create();
		            $datos = $query->select('a.denominacion')->from('DatFunciones a')->where("a.idservicio = ?", $idservicio)->setHydrationMode( Doctrine :: HYDRATE_ARRAY )->execute();
		            return $datos;
			}
		
}
