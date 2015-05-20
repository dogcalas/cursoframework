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
  class DatServidorbdModel extends ZendExt_Model
  {
       public function DatServidorbdModel()
	   {
	      parent::ZendExt_Model();
	   }	   
	   function eliminarservbd($instance)
	   {
	         $instance->delete();
	   }
	   function modificarservidor($servidor)
		{ 
		       	$servidor->save();		       	 	
		}
	  	   
  }
?>