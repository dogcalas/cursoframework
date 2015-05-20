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
class DatServidorbd extends BaseDatServidorbd
{

	public function setUp()
  {
    parent::setUp();    
    $this->hasOne('DatServidor',array('local'=>'idservidor','foreign'=>'idservidor'));
  }
  
	static public function obtenercantidad($idservidor)
	{
	try
	     {
            $query = Doctrine_Query::create();
            $cantFndes = $query->from('DatServidorbd b, b.DatGestorDatServidorbd g')
		            		   ->where("g.idservidor = ?", $idservidor)
		            	       ->count();            
            return $cantFndes;
         }
        catch(Doctrine_Exception $ee)
        {
			return false;   
        }	
	}
}