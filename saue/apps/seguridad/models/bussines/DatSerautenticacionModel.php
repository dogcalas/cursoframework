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
  class DatSerautenticacionModel extends ZendExt_Model
  {
       public function DatSerautenticacionModel() 
	   {
	      parent::ZendExt_Model();
	   }
       	   
	   function eliminarservauth($instance) {
	          $instance->delete();
			  return true;
	   }
	   function insertar($tiposerv)
		{ 
		       	$tiposerv->save();		       	 	
		}
        
		function modificarservidor($servidor)
		{ 
		       	$servidor->save();		       	 	
		}
        
		function modificarservidorsabd($servidor)
		{ 
		       	$servidor->save();
		}
        
		function eliminarservidor($instance)
		{ 
	       	 	$instance->delete();
		}
	     
  }
?>