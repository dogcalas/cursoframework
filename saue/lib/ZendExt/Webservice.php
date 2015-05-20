<?php
/**
 * 
 */
class ZendExt_Webservice {
    protected  $integrator;
    function  ZendExt_Webservice() {
        $this->integrator = ZendExt_IoC::getInstance();
    }
    
    function isValid($headers){
        //aki se debe llamar a un servicio de Acaxia
        return true; 
    }
}
?>
