<?php

/**
 * Description of MainPeP
 *
 * @author quirog@
 */

class ZendExt_XACML_PEP_MainPeP {
    //put your code here
    protected $datosuser= array();
    protected $accion;
    private static $instancia;    
    protected $frontController;
    protected $decision;
    protected $permisos=array();
    protected $funcionalidades;
    protected $objcache;
    protected $url;
    
    private function __construct() {}

      
    public static function getInstance () {
        if (!isset(self::$instancia)) {
            $obj = __CLASS__;
            self::$instancia = new $obj;
        }
        return self::$instancia;
    }
  
    public function ObtenerDatosUser($resource,$action) {
         
          $session = Zend_Registry::getInstance()->session;
          $this->datosuser= $session->perfil;         
          $idusuario=$this->datosuser[idusuario];
          $iddominio=$this->datosuser[iddominio];        
          $certificado=$session->certificado;       
          $identidad=$this->datosuser[identidad];
           
          $requestXml=$this->CreateAuthorizeRequestXML($idusuario,$iddominio,$certificado,$identidad,$resource,$action);
        /*Estas lines comentariadas son para si se desea encriptar la peticion
          $rsa = new ZendExt_RSA_Facade(); 
          $this->redirectToMainPdP($rsa->encrypt($requestXml));*/
      //$pdp= ZendExt_XACML_PDP_MainPdP::getInstance();
      //$pdp->initPDP($requestXml);
          $this->redirectToMainPdP($requestXml); 
      }
    
    public function CreateAuthorizeRequestXML($user,$iddominio,$certificado,$identidad,$recurso,$accion) {
        
          $xml = new DomDocument('1.0', 'UTF-8');
          $root = $xml->createElement('Request');
          $root = $xml->appendChild($root);

          $subject=$xml->createElement('Subject');
          $subject =$root->appendChild($subject);
          $attribute=$xml->createElement('Attribute');
          $attribute =$subject->appendChild($attribute);
          $attribute->setAttribute('Attributeid0','user');
          $attributevalue=$xml->createElement('AttributeValue',$user);         
          $attributevalue =$attribute->appendChild($attributevalue);
           
          $dominio=$xml->createElement('Dominio');
          $dominio =$root->appendChild($dominio);
          $attribute=$xml->createElement('Attribute');
          $attribute =$dominio->appendChild($attribute);
          $attribute->setAttribute('Attributeid1','iddominio');
          $attributevalue=$xml->createElement('AttributeValue',$iddominio);         
          $attributevalue =$attribute->appendChild($attributevalue);
          
          $certificate=$xml->createElement('Certificate');
          $certificate =$root->appendChild($certificate);
          $attribute=$xml->createElement('Attribute');
          $attribute =$certificate->appendChild($attribute);
          $attribute->setAttribute('Attributeid1','certificado');
          $attributevalue=$xml->createElement('AttributeValue',$certificado);         
          $attributevalue =$attribute->appendChild($attributevalue);
          
          $estructura=$xml->createElement('Estructure');
          $estructura =$root->appendChild($estructura);
          $attribute=$xml->createElement('Attribute');
          $attribute =$estructura->appendChild($attribute);
          $attribute->setAttribute('Attributeid1','estructura');
          $attributevalue=$xml->createElement('AttributeValue',$identidad);         
          $attributevalue =$attribute->appendChild($attributevalue);
          
          $resource=$xml->createElement('Resource');
          $resource =$root->appendChild($resource);
          $attribute=$xml->createElement('Attribute');
          $attribute =$resource->appendChild($attribute);
          $attribute->setAttribute('Attributeid2','recurso');
          $attributevalue=$xml->createElement('AttributeValue',$recurso);
          $attributevalue =$attribute->appendChild($attributevalue); 

          $action=$xml->createElement('Action');
          $action =$root->appendChild($action);
          $attribute=$xml->createElement('Attribute');
          $attribute =$action->appendChild($attribute);
          $attribute->setAttribute('Attributeid3','accion');
          $attributevalue=$xml->createElement('AttributeValue',$accion);
          $attributevalue =$attribute->appendChild($attributevalue);          
          
          $environment=$xml->createElement('Environment');
          $environment =$root->appendChild($environment);
          $entorno=$xml->createElement('Entorno','uci');
          $entorno =$environment->appendChild($entorno);
        
          $xml->formatOutput = true;
       //Guardar el xml como un archivo de String, es decir, poner los string en la variable $strings_xml:
          $strings_xml = $xml->saveXML();
          
      //Finalmente, guardarlo en un directorio:
          $xml->save('../../lib/ZendExt/XACML/RequestXML/Request.xml');
         
          return $strings_xml;
    }
    
    public function redirectToMainPdP($requestXml) {
        $dir='http://10.58.15.16:5900/AuthoriceServicePdP/';
        $form  = "<html><head></head><body onload=\"document.getElementById('form1').submit()\">";
        $form .= "<form id='form1' action='$dir' method='POST'>";
        $form .= "<input type='hidden' name='RequestXML' value='$requestXml'/>";
        echo $form;
    }
    
    public function redirectToIndex($obligaciones) {
        $url='http://10.58.15.16:5900/xacml/';
        $form  = "<html><head></head><body onload=\"document.getElementById('form2').submit()\">";
        $form .= "<form id='form2' action='$url' method='POST'>";
        $form .= "<input type='hidden' name='Obligaciones' value='$obligaciones'/>";
        
        echo $form;   
    }
   
    public function ValidateAuthoriceResponseXML($response) {
      /*Estas lines comentariadas son para si se desea encriptar la peticion
         $rsa = new ZendExt_RSA_Facade(); 
        $this->parsearDatosResponse($rsa->decrypt($response));*/
    
        $this->parsearDatosResponse($response);
        if($this->decision=='permit'){
           if(!empty($this->permisos)){
              $obligaciones['permisos']=  $this->permisos;
           }else{
             $obligaciones['permisos']='';
           }         
          $obligaciones['funcionalidades']=  $this->funcionalidades;  
         
          $this->redirectToIndex(json_encode($obligaciones));
          
        }  else if ($this->decision=='deny') {
              throw new Exception('<h3> No tiene los privilegios suficientes para acceder a este objeto.
                                        Pónganse en contacto con el administrador del sistema.</h3>');                      
        }  else {            
             throw new Exception('<h3> Ocurrió un error en la conección con el PDP de XACML. Por favor contacte al administrador.</h3>');
        }            
    }
    
    public function parsearDatosResponse($response) {
        $xml = simplexml_load_string($response);     
        $Result=$xml->Result;        
        $this->decision=(string)$Result->Decision;
       
        $Functionalities=$xml->Functionalities;
        $funcionalidades=(string)$Functionalities->Functionality;
        $this->funcionalidades=  json_decode($funcionalidades);
       
        $Obligations=$xml->Obligations;
        $permisos=(string)$Obligations->Obligation;
        $this->permisos=  json_decode($permisos);
        
    }
  
}

?>
