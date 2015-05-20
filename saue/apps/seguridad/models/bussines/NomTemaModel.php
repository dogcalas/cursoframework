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
class NomTemaModel extends ZendExt_Model 
{

	public function NomTemaModel()
	{
	   		parent::ZendExt_Model();       
	    }
	    
	function insertartema($tema)
    {
	      $tema->save();
	    }
	    
    function modificartema($tema)
	{ 
	  $tema->save();
	}
		
    function eliminartema($tema)
	{ 
	       	$tema->delete();
	}
        function comprobartemaenuso($idtema)
        {            
         $cantuser=  SegUsuario::comprobartema($idtema);         
         if($cantuser){
            return 1; 
         }else              
             return 0;
        }

}