<?php

class SegDatosImagenesModel extends ZendExt_Model

{

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



}

