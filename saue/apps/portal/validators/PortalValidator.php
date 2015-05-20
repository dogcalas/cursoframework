<?php

class PortalValidator {

	public function desbloquearDocLog () {
		$ioc = ZendExt_IoC::getInstance();
		$global = ZendExt_GlobalConcept::getInstance();
		$idusuario = $global->Perfil->idusuario;
		try {
			$ioc->logistica->desbloqueardocByIdusuario($idusuario);
		} catch (Exception $e) {
			return false;
		}
		return true;
	}
	public function comprobarConMongo () {	
		 $xml = ZendExt_FastResponse::getXML('mongo');
		 $recursos = array();
        foreach ($xml->children() as $recu) {           
            $instalado = (string)$recu['instalado'];            
        }
         if($instalado == 0)
	     return true;	
		try {
			 $mongo=new ZendExt_Mongo();			   
		} catch (Exception $e) {
			return false;
		}
		return true;
	}
}

