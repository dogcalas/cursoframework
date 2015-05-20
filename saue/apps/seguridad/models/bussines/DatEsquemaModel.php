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
	class DatEsquemaModel extends ZendExt_Model 
	{
		public function DatEsquemaModel()
		{
			parent::ZendExt_Model();
		}
        
		function insertarnomesquema($instance)
		{
	      $instance->save();
		}
        
		function modificarnomesquema($instance)
		{ 
	      $instance->save();
		}
        
		function eliminarnomesquema($instance)
		{ 
	      $instance->delete();
		}
	}
?>