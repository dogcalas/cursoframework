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
class SegDominio extends BaseSegDominio
{

	public function setUp()
	  {
	    parent::setUp();    
	    $this->hasMany('SegUsuario',array('local'=>'iddominio','foreign'=>'iddominio'));
	  }  
     
	static public function cargarnomdominio($limit=10,$start=0)
	{
			$query = new Doctrine_Query ();		
	        $result = $query ->select('d.iddominio')
	        				 ->from('SegDominio d')
	        				 ->limit($limit)
	        				 ->offset($start)
	        				 ->execute();	
	        return $result;        				 
	}
	
	static public function obtenercantnomdominio()
	{
			$query = new Doctrine_Query ();		
	        $cant = $query ->from('SegDominio')
	        				 ->count();	
	        return $cant;        				 
		}
	
	static public function cargarcombodominio()
	{
			$query = new Doctrine_Query ();		
	        $result = $query ->select('iddominio,denominacion')
	        				 ->from('SegDominio')
	        				 ->execute();	
	        return $result;        				 
	}
    
    static public function obtenerCadenaEntidades($idDominio)
    {
            $query = new Doctrine_Query ();        
            $result = $query ->select('d.cadena')
                             ->from('SegDominio d')
                             ->where("d.iddominio = ?", $idDominio)
                             ->setHydrationMode( Doctrine:: HYDRATE_ARRAY )
                             ->execute();    
            return explode(",",$result[0]["cadena"]);                         
    }
    
   static public function verificarmodificaciones($dominio)
   {
    $query = new Doctrine_Query ();
    $result = $query->select('d.cadena')->from('SegDominio d')->where("d.iddominio =?",$dominio)->execute()->toArray();
    return $result;
   }
	
    static public function cargarDominio()
   {
    $query = new Doctrine_Query ();
    $result = $query->select('d.iddominio')->from('SegDominio d')->execute();
    return $result;
   }
   
   static  public  function contardominios($denominacion)
    {
    $query = Doctrine_Query::create();
    $usuario = $query->from('SegDominio')->where("denominacion = ?",$denominacion)->count();                      
    return $usuario;
    }
	static public function comprobardatosdominio($denominacion)
    {
	    $query = Doctrine_Query::create();
	    $cantidaddatdominios = $query->from('SegDominio')->where("denominacion = ?",array($denominacion))->count();                      
	    return $cantidaddatusuarios;
    }
}