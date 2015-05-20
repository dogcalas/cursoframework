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
	class DatParametrosModel extends ZendExt_Model 
	{
		public function DatParametrosModel()
		{
			parent::ZendExt_Model();
		}
        
		function insertarparametro($parametro)
		{ 
	     $parametro->save();
		}
        
		function modificarparametro($parametro)
		{ 
	     $parametro->save();
		}
        
		function eliminarparametro($parametro)
		{ 
	     $parametro->delete();
		}
		
	}
?>