<?php
/**
 * Description of MainPaP
 *
 * @author quiroga
 */
class ZendExt_XACML_PAP_MainPaP {
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
    
     public function cargarFuncionalidadesCache($certificado,$entidad) {
         $funcionalidades= array(); 
         $cacheObj = ZendExt_Cache::getInstance();	
         $funcionalidades= $cacheObj->load('funcionalidades'); 
       
          if (empty($funcionalidades)){            
         $integrator = ZendExt_IoC::getInstance();  
         $funcionalidades = $integrator->seguridad->ObtenerTodasFucionalidades($certificado,$entidad);
         $cacheObj->save($funcionalidades,'funcionalidades');        
          }  
        
         return $funcionalidades; 
     }
     
   /*Debe tener mongodb habilitado*/  
     public function cargarPermisos($iddominio,$rolacl) {
         $permisos = array(); 
         
         if($this->comprobarmongo()){
              try {
        $objmongodb = new ZendExt_Mongo();
        } catch (Exception $e) {
      throw new ZendExt_Exception('ECR06');
    }
     // $objmongodb->eliminaracl(1);
      $datos = $objmongodb->buscarPorIdAcl($iddominio);
     //  echo '<pre>';print_r($datos);die;
             
            foreach ($datos as $permiso) {
                if ($permiso['rolacl'] == $rolacl) {
                    $perm['permiso'] = $permiso['permiso'];
                    $perm['recurso'] = $permiso['recurso'];                     
                    $perm['nombreregla'] = $permiso['nombreregla'];
                    $perm['campo'] = $permiso['campo'];
                    if($permiso['operador']=='<>'){
                       $permiso['operador']='diferente';  
                    }  elseif ($permiso['operador']=='<=') {
                       $permiso['operador']='menorigual'; 
                    }                       
                    $perm['operador'] = $permiso['operador'];
                    $perm['valor'] = $permiso['valor'];
                    $perm['table_name']=$permiso['table_name'];
                    $dato = $perm;                    
                    if (!in_array($dato, $permisos)) {
                        $permisos[] = $dato;
                    }
                }
            }
            return $permisos;
         }
         
        return $permisos;
       
     }
     
    public function comprobarmongo() {
     $xml = ZendExt_FastResponse::getXML('mongo');
        $recursos = array();
        foreach ($xml->children() as $recu) {           
            $instalado = (string)$recu['instalado'];            
        }
       
       if($instalado == 1){
           return true;
       } else {
           return false; 
       }     
              
    
}

}



?>
