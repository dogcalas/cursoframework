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
	class DatSistemaSegRolModel extends ZendExt_Model 
	{
		public function DatSistemaSegRolModel()
		{
			parent::ZendExt_Model();
		}
		
		public function Eliminar($DatSistemaSegRol){
                    $DatSistemaSegRol->delete();
                }
		public function Adicionar($DatSistemaSegRol){
                    $DatSistemaSegRol->save();
                }

	}
?>