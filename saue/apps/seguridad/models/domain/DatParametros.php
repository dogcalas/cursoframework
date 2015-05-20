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
class DatParametros extends BaseDatParametros
{

	public function setUp()
	{
		    parent::setUp();    
		    $this->hasOne('DatFunciones',array('local'=>'idfunciones','foreign'=>'idfunciones'));
		  }
		  
    static function cargarparametros($idfunciones,$limit,$start)
	{
    $query = Doctrine_Query::create();
    $fndes = $query->select('f.idfunciones,f.denominacion, f.descripcion,f.puedesernull,f.tipoparametro,f.valordefecto,f.idparametros')->from('DatParametros f')->where("f.idfunciones = ?", $idfunciones)->orderby('idparametros')->limit($limit)->offset($start)->execute();
    return $fndes;
	}
		
	static function obtenercantpar($idfunciones)
	{
    $query = Doctrine_Query::create();
    $cantFndes = $query->from('DatParametros f')->where("f.idfunciones= ?", $idfunciones)->count();
    return $cantFndes;
	} 
	public static function verificarparametros($denominacion)
	{
	$query = Doctrine_Query::create(); 
	$var = $query->from('DatParametros p')
                  ->where("p.denominacion = ?", $denominacion)->count();
	return $var;
	}
	static public function obtenerParametrosServicio($idfunciones) {
		            $query = Doctrine_Query::create();
		            $datos = $query->select('a.idparametros id,a.idparametros,a.denominacion')->from('DatParametros a')->where("a.idfunciones = ?", $idfunciones)->setHydrationMode( Doctrine :: HYDRATE_ARRAY )->execute();
		            return $datos;
			}	
}