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
	class DatAccionDatReporteModel extends ZendExt_Model 
	{
		public function DatAccionDatReporteModel()
		{
			parent::ZendExt_Model();
		}
        
		public function insertaraccionreporte($arrayRepoAcc)
		{ 
			
		foreach($arrayRepoAcc as $valor1)
	       	 	$valor1->save();

        return true;
		}
		public function modificaraccion($instance)
		{ 
	       	 	$instance->save();return true;
		}
        
		public function eliminaraccion($instance)
		{ 
	       	 	$instance->delete();return true;
		}
	}
?>