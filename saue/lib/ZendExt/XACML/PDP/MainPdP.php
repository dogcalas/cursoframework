<?php

/**
 * Description of MainPdP
 *
 * @author quiroga
 */
class ZendExt_XACML_PDP_MainPdP {
    //put your code here
     private static $instancia;  
     protected $user;
     protected $resource;
     protected $action;
     protected $policy;
     protected $certificate;
     protected $entidad;
     protected $iddominio;
     protected $objpap;
     protected $objpip;
     protected $funcionalidadesacion;
     protected $permisos;
     
     private function __construct() {
       $this->objpap= ZendExt_XACML_PAP_MainPaP::getInstance(); 
       $this->objpip= ZendExt_XACML_PIP_MainPiP::getInstance();  
    }
      
    public static function getInstance () {
        if (!isset(self::$instancia)) {
            $obj = __CLASS__;
            self::$instancia = new $obj;
        }
        return self::$instancia;
    }
    
    public function initPDP($request) {       
       /*Estas lines comentariadas son para desencriptar la peticion
        $rsa = new ZendExt_RSA_Facade();         
        $this->parsearDatosRequest($rsa->decrypt($request));*/
    
        $this->parsearDatosRequest($request);         
       
        /*Almacenar las funcionalidades del usuario en la entidad en cache por el PAP*/
        $this->AlmacenarFuncionalidadesCache();
        
         /*Validar peticion*/   
        $decision=$this->ValidateRequest();   
        $obligaciones=array();
        $funcionalidades=array();
        
         if($decision==permit){
        $funcionalidades= json_encode($this->funcionalidadesacion); 
        $idrol=  $this->objpip->obteneRol($this->user,$this->entidad);  
        $rolacl=$this->user . '_' . $idrol . '_' . $this->entidad;        
        $this->permisos=  $this->objpap->cargarPermisos($this->iddominio,$rolacl);
          
        $obligaciones=  json_encode( $this->permisos); 
         }
         
        /*Crear respuesta*/
        $response=$this->CreateAuthorizeResponseXML($decision,$funcionalidades,$obligaciones);       
       
        /*Esta line comentariadas son para si se desea encriptar la respuesta
          $rsa = new ZendExt_RSA_Facade(); 
          $this->redirectToMainPeP($rsa->encrypt($response));*/
        $this->redirectToMainPeP($response);
    }
    
    public function parsearDatosRequest($request) {
       
        $xml = simplexml_load_string($request);
     
        $Subject=$xml->Subject;
        $Attribute=$Subject->Attribute;
        $this->user=(string)$Attribute->AttributeValue;
       
        $Dominio=$xml->Dominio;
        $Attribute=$Dominio->Attribute;
        $this->iddominio=(string)$Attribute->AttributeValue;
        
        $Certificate=$xml->Certificate;
        $Attribute=$Certificate->Attribute;
        $this->certificate=(string)$Attribute->AttributeValue;
        
        $Estructure=$xml->Estructure;
        $Attribute=$Estructure->Attribute;
        $this->entidad=(string)$Attribute->AttributeValue;
      
        $Resource=$xml->Resource;
        $Attribute=$Resource->Attribute;
        $this->resource=(string)$Attribute->AttributeValue;
       
        $Action=$xml->Action;
        $Attribute=$Action->Attribute;
        $this->action=(string)$Attribute->AttributeValue;
      
    }
    public function redirectToMainPeP($responseXml) {
        $dir='http://10.58.15.16:5900/seguridad/AuthoriceServicePeP/';
        $form  = "<html><head></head><body onload=\"document.getElementById('form2').submit()\">";
        $form .= "<form id='form2' action='$dir' method='POST'>";
        $form .= "<input type='hidden' name='ResponseXML' value='$responseXml'/>";
        echo $form;        
    }
    
    public function CreateAuthorizeResponseXML($decision,$funcionalidades,$obligaciones) {
        $xml = new DomDocument('1.0', 'UTF-8');
        $root = $xml->createElement('Response');
        $root = $xml->appendChild($root);        
       
        $attribute=$xml->createElement('Result');
        $attribute =$root->appendChild($attribute);
        $attribute->setAttribute('Resourceid',  $this->resource);
        $attributevalue=$xml->createElement('Decision',$decision);         
        $attributevalue =$attribute->appendChild($attributevalue);
        
         if($decision!=deny){
        $attribute=$xml->createElement('Functionalities');
        $attribute =$root->appendChild($attribute);
        $attribute->setAttribute('Functionalitiesid', 'idfunc');
        $attributevalue=$xml->createElement('Functionality',$funcionalidades);         
        $attributevalue =$attribute->appendChild($attributevalue);
        
        $attribute=$xml->createElement('Obligations');
        $attribute =$root->appendChild($attribute);
        $attribute->setAttribute('Obligationsid',  'idreglas');
        $attributevalue=$xml->createElement('Obligation',$obligaciones);         
        $attributevalue =$attribute->appendChild($attributevalue);
         }
        $xml->formatOutput = true;
       //Guardar el xml como un archivo de String, es decir, poner los string en la variable $strings_xml:
        $strings_xml = $xml->saveXML();
       
      //Finalmente, guardarlo en un directorio:
        $xml->save('../../lib/ZendExt/XACML/ResponseXML/response.xml');
      
        return $strings_xml;    
    }
    
    public function AlmacenarFuncionalidadesCache() {       
        $this->funcionalidadesacion= $this->objpap->cargarFuncionalidadesCache($this->certificate, $this->entidad);  
      }
   
    public function ValidateRequest() {
                   
       $funcionalidades= $this->funcionalidadesacion;    
       $acciones= $funcionalidades[$this->resource];
        if(count($acciones))
              return 'permit'; 
        return 'deny'; 
    }
       
}

?>
