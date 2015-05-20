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
	class DatAccionDatObjetobdModel extends ZendExt_Model
	{
		public function DatAccionDatObjetobdModel()
		{
			parent::ZendExt_Model();
		}
        
		public function insertar($accionobjetobd)
		{   
	       	 	$accionobjetobd->save();
		}
        
		public function modificar($accionobjetobd)
		{
                
	       	 	$accionobjetobd->save();
                        
		}
        
		public function eliminar($instance)
		{ 
	       	 	$instance->delete();
		}
	}
?>