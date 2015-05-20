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
	class DatServicioDatSistemaModel extends ZendExt_Model 
	{
		public function DatServicioDatSistemaModel()
		{
			parent::ZendExt_Model();
		}
        		
		function insertarserviciocons($instance)
		{ 
		  $instance->save();
		}		
		function eliminar($instance)
		{
	        $instance->delete();
		}

		function modificarserviciocons($instance)
		{ 
	      $instance->save();
		}
						
	}
?>