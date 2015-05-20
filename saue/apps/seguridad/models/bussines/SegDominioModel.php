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
class SegDominioModel extends ZendExt_Model 
{

   public function SegDominioModel()
	{
		parent::ZendExt_Model();
	}
	
   function insertardominio($dominio)
   {
            $dominio->save();
   }
   
   function modificardominio($dominio)
	{
        	$dominio->save();
    }
   
   function eliminardominio($dominio)
   {
            $dominio->delete();
   }
   
   public function obtenerCadenaEntidades($id) {
    $arrayEntidades = SegDominio::obtenerCadenaEntidades($id);
    return $arrayEntidades; 
   }
   public function cantusuariodadodominio($iddominio){ 
   $cantusuariodominio = SegUsuario::cantusuariodadodominio($iddominio);
   return $cantusuariodominio; 
   }
   
   
}