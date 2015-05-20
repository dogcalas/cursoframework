<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien García Tejo
 * @author Julio Cesar García Mosquera  
 * @version 1.0-0
 */
class NomIdioma extends BaseNomIdioma
{

	public function setUp()
	  {
	    parent::setUp();    
	    $this->hasMany('SegUsuario',array('local'=>'ididioma','foreign'=>'ididioma'));	     
	  }

	static public function cargarnomidioma($limit,$start)
	{
			$query = new Doctrine_Query ();		
	        $result = $query ->select('i.ididioma,i.denominacion,i.abreviatura')->from('NomIdioma i')->limit($limit)
	        				 ->offset($start)
	        				 ->execute();	
	        return $result;        				 
	}
    
	static public function obtenercantnomidioma()
	{
		$query = new Doctrine_Query ();		
	    $cant = $query ->from('NomIdioma')->count();	
	    return $cant;        				 
	}
    
	static public function cargarcomboidioma()
	{
		$query = new Doctrine_Query ();		
	    $result = $query ->select('i.ididioma,i.denominacion')->from('NomIdioma i')->execute();	
	    return $result;        				 
	}
    
    static  public  function comprobaridioma($denominacion,$abreviatura)
    {
    $query = Doctrine_Query::create();
    $cantidadidioma = $query->from('NomIdioma')->where("denominacion = ? OR abreviatura = ?",array($denominacion,$abreviatura))->count();                      
    return $cantidadidioma;
    }
}	