<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MainPIP
 *
 * @author quiroga
 */
class ZendExt_XACML_PIP_MainPiP {
    //put your code here
     private static $instancia;  
     
        private function __construct() {}
      
        public static function getInstance () {
        if (!isset(self::$instancia)) {
            $obj = __CLASS__;
            self::$instancia = new $obj;
        }
        return self::$instancia;
    }
    
     public function initPIP() {
         echo 'llego pip';die;
     }
     
     public function obteneRol($idusuario,$identidad) {
         
         $integrator = ZendExt_IoC::getInstance();   
         $idrol=$integrator->seguridad->ObtenerRolUsuarioEntidad($idusuario,$identidad);
        
         return $idrol;
     }
}

?>
