<?php

/**
* ZendExt_SAML_ServiceProvider_Server *
* Clase interfaz del Proveedor de Servicios *
*@ Daniel Enrique L�pez M�ndez * 
*@Seguridad* (m�dulo)
*@Licencia (subm�dulo)*
*@copyright ERP Cuba*
*@version 1.0*/

class ZendExt_License_Lc implements ZendExt_Aspect_ISinglenton{
    //-Atributtes-//
    private static $instance;
    private $actcod;
    private $keys;

    //-Methods-//
    private function __construct(){
        $dir_index = $_SERVER['SCRIPT_FILENAME'];
	$this->keys = substr($dir_index, 0, strrpos($dir_index, 'web')) . 'config/xml/keys.xml';	
        $this->actcod = $this->generateActCode();
    }

    static public function getInstance() {
        if (!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function generateActCode(){
        $created = false;
        $dir = $_SERVER['DOCUMENT_ROOT'];
        $real_dir = str_replace('web', 'config/xml/', $dir);
        system('lshw > '.$real_dir.'lc.txt');
        while(!$created){
            if(file_exists($real_dir.'lc.txt'))
                $created = true;	
        }
        $datastring = self::getCPU($real_dir)."*".self::getNetwork($real_dir);
        $result = md5($datastring); 
        $system = $this->getProduct();
        $version = $this->getVersion(); 
        return $system."-".$version."-".$result;
    }
    
    static private function getCPU($real_dir){
        $lc = file_get_contents($real_dir.'lc.txt');
        $cpu_ex = explode('*-cpu',$lc);
        $cpu_ex = explode('*-',$cpu_ex[1]);
        $cpu = $cpu_ex[0];
        $product_ex = explode("product: ",$cpu);
        $product_ex = explode("\n", $product_ex[1]);
        $product = $product_ex[0];
        $serial_ex = explode("serial: ",$cpu);
        $serial_ex = explode("\n", $serial_ex[1]);
        $serial = $serial_ex[0];
        return $serial;
    }
    
    static private function getNetwork($real_dir){
        $lc = file_get_contents($real_dir.'lc.txt');
        $network_ex = explode('*-network',$lc);
        $network_ex = explode('*-',$network_ex[1]);
        $network = $network_ex[0];
        $product_ex = explode("product: ",$network);
        $product_ex = explode("\n", $product_ex[1]);
        $product = $product_ex[0];
        $serial_ex = explode("serial: ",$network);
        $serial_ex = explode("\n", $serial_ex[1]);
        $serial = $serial_ex[0];
        return $serial;
    }

    public function getAtivationCode(){
        $cache = ZendExt_Cache::getInstance();
        $cache->save($this->actcod,'actcod');
        return $this->actcod;
    }
    
    public static function getAtivationCodeFromCache(){
        $cache = ZendExt_Cache::getInstance();
        try{
            $actcod = $cache->load('actcod');
            if($actcod){
                return $actcod;
            }
        } catch (Exception $ee){}
        $self = self::getInstance();
        $actcod = $self->getAtivationCode();
        $cache->save($actcod,'actcod');
        return $actcod;
    }
    
    public function validate(){
        $now = new DateTime();
        $licensed = $this->getLicenciaDesglozada();
        if($licensed->actcod != $this->getAtivationCode()) {return false;}
        if(strtotime($licensed->fechaexp->format("y-m-d")) < strtotime($now->format("y-m-d"))) {return false;}
        if($licensed->product != $this->getProduct()) {return false;}
        if($licensed->version != $this->getVersion()) {return false;}
        return true;
    }
    
    public function setLicense($license){
        $System = $this->getXml();
        $System->key['value'] = $license;
        file_put_contents($this->keys, $System->asXML());
    }
    
    public function getLicense(){
        $System = $this->getXml();
        return (string)$System->key['value'];
    }
    
    public function getLicenciaDesglozada(){
        $result = new stdClass();
        $licencia = $this->getLicense();
        $encription_obj = new ZendExt_Encript_Encript();
        $codigo = $encription_obj->Decrypt_Text($licencia);
        $split = explode("*", $codigo);
        $result->actcod = $split[0];
        $result->fechaexp = new DateTime($split[1]);
        $result->numsec = $split[2];
        $split = explode("-", $split[0]);
        $result->product = $split[0];
        $result->version = $split[1];
        return $result;
    }
        
    public function getProduct(){
        $System = $this->getXml();
        return (string)$System['product'];
    }
    
    public function getVersion(){
        $System = $this->getXml();
        return (string)$System['version'];
    }
    
    public function getFechaExpiracion(){
        $licencia = $this->getLicense();
        $encription_obj = new ZendExt_Encript_Encript();
        $codigo = $encription_obj->Decrypt_Text($licencia);
        $split = explode("*", $codigo);
        $fechaexp = new DateTime($split[1]);
        return $fechaexp->format('d-m-Y');
    }
    
    public function getActCodLicense(){
        $licencia = $this->getLicense();
        $encription_obj = new ZendExt_Encript_Encript();
        $codigo = $encription_obj->Decrypt_Text($licencia);
        $split = explode("*", $codigo);
        return $split[0];
    }
    
    public function getSecuencia(){
        $licencia = $this->getLicense();
        $encription_obj = new ZendExt_Encript_Encript();
        $codigo = $encription_obj->Decrypt_Text($licencia);
        $split = explode("*", $codigo);
        return $split[2];
    }
    
    public function getXml(){
        
        return simplexml_load_file($this->keys);
    }
  
}
