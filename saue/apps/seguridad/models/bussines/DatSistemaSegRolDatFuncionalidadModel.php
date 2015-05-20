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
	class DatSistemaSegRolDatFuncionalidadModel extends ZendExt_Model 
	{
		public function DatSistemaSegRolDatFuncionalidadModel()
		{
			parent::ZendExt_Model();
		}
		
		public function Eliminar($DatSistemaSegRoldatfun){
                    $DatSistemaSegRoldatfun->delete();
                }
		public function Adicionar($DatSistemaSegRoldatfun){
                    $DatSistemaSegRoldatfun->save();
                }

	}
?>