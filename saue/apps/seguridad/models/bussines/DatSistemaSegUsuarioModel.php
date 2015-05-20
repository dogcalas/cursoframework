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
  class DatSistemaSegUsuarioModel extends ZendExt_Model
  {
       public function DatSistemaSegUsuarioModel()
	   {
	      parent::ZendExt_Model();
	   }
	   
	  /* function insertarservicio($instance)
	   {
	   try
	       	 {
	       	 	$instance->save();
	        	return true;
	         }
	       	 catch(Doctrine_Exception $ee)
	         {
	            throw $ee;
	         }				
	      
	   }
	   function eliminarservicio($instance)
	   {
	   try
	       {
	          $instance->delete();
			  return true;
	       }
	   
		   catch(Doctrine_Exception $ee)
		   {
		        throw $ee;  
		   }
	   }*/
	   
	   function modusersist($sistusuario)
	   {
	   try
	       	 {
	       	 	$sistusuario->save();
	        	return true;
	         }
	       	 catch(Doctrine_Exception $ee)
	         {
	            throw $ee;
	         }				
	      
	   }
	   
  
  }
?>