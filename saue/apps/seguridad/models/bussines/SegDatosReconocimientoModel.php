<?php

class SegDatosReconocimientoModel extends ZendExt_Model
{
    
    function modificardatos($datos)
    
		{
        
			try
	       	 {
	       	 	$datos->save();                         
	        	return true;
	         }
	       	 catch(Doctrine_Exception $ee)
	         {
                   
                     
	            throw $ee;
	         }
                }
                 function insertardatos($datos)
    
		{
        
			try
	       	 {
	       	 	$datos->save();                         
	        	return true;
	         }
	       	 catch(Doctrine_Exception $ee)
	         {
                   
                     
	            throw $ee;
	         }
                }


}

