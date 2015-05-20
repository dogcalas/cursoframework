<?php

/**
 * ZendExt_SAML_ServiceProvider_Server *
 * Clase interfaz del Proveedor de Servicios *
 *@ Daniel Enrique L�pez M�ndez * 
 *@Seguridad* (m�dulo)
 *@Autenticacion (subm�dulo)*
 *@copyright ERP Cuba*
 *@version 1.0*/

class ZendExt_SAML_ServiceProvider_Server 
{
  

/**
*init *
*Funcion principal de la clase *
*@param *($request) *
*@throws *Acceso Denegado Mensaje Corrupto*
*@return *-*/

  public function init($request,$config) {
   $rsa = new ZendExt_RSA_Facade();
   $saml_util = new ZendExt_SAML_Util();
   $stringdatasign = $request[md5('stringdatasign'.$request[md5('seed')])];
   $resource = $request[md5('resource'.$request[md5('seed')])];
   $encdata = $request[md5('encdata'.$request[md5('seed')])];
   $seed = $request[md5('seed')];
   $prove = $rsa->proveMessage($encdata,$stringdatasign);

		if($prove)
			{
			 $stringdata = $rsa->decrypt($encdata);
			 $arraydata = $saml_util->parseData($stringdata);
                         $auth_req_template = $saml_util->createAuthnRequest($config,urldecode($arraydata[7]), $arraydata[2]);
                         $encAuthnRequest = $rsa->encrypt($auth_req_template);
                         $signAuthnRequest = $rsa->signMessage($encAuthnRequest);
                         $encrelayStateUrl = $rsa->encrypt($arraydata[6]);
                         //
			 $encidp = $rsa->encrypt($arraydata[0]);
                         $this->redirectToIdentityProvider($encAuthnRequest,$signAuthnRequest,$seed,$encrelayStateUrl,$arraydata[0],$encidp);
                        }
		else{

		    }


     // $this->redirectToIdentityProvider($request,$rsa,$athreq);

   }


/**
*check *
*Funcion para chequear la validez del certificado digital*
*@param *($certificado) *
*@throws *-*
*@return *true/false*/

   private function check($certificado)
   {
    //Chequear el certificado (DUDA).
   }





/**
*redirectToIdentityProvider *
*Funcion que encripta y firma el primer XML (Authentication Request), lo y envia al proveedor de identidad*
*@param *($request,$rsa,$athreq)*
*@throws *-*
*@return *-*/



   private function redirectToIdentityProvider($encAuthnRequest,$signAuthnRequest,$seed,$encrelayStateUrl,$idp,$encidp) {

	$md5encAuthnRequest = md5('encAuthnRequest'.$seed);
        $md5signAuthnRequest = md5('signAuthnRequest'.$seed);
        $md5encrelayStateUrl = md5('encrelayStateUrl'.$seed);
	$md5idp = md5('idp'.$seed);
        $md5seed = md5('seed');
        $locate  = "<html><head></head><body onload=\"document.getElementById('form1').submit()\">";
        $locate .= "<form id='form1' action='$idp' method='POST'>";
        $locate .= "<input type='hidden' name='$md5encAuthnRequest' value='$encAuthnRequest'/>";
        $locate .= "<input type='hidden' name='$md5signAuthnRequest' value='$signAuthnRequest'/>";
	$locate .= "<input type='hidden' name='$md5encrelayStateUrl' value='$encrelayStateUrl'/>";
        //$locate .= "<input type='hidden' name='$md5idp' value='$encidp'/>";
	$locate .= "<input type='hidden' name='$md5seed' value='$seed'/>";
        echo $locate;
   }

/**
*parseString*
*Funcion que parsea la direccion del recurso en caso de estar por https*
*@param *($cadena)*
*@throws *-*
*@return * $respueta*/

   private function parseString($cadena) {
	    $mi_texto = eregi_replace("http","https","$cadena");
	    $respueta = eregi_replace(":443/","/","$mi_texto");
	    return $respueta;
	   	}


/**
*parseString*
*Funcion que crea el primer XML (Authentication Request) en la comunicacion SAML*
*@param *$protocolo,$initdir,$url_self,$serveridp,$athreq,$app,$flag)*
*@throws *-*
*@return *XML (Authentication Request)*/

   private function create_AuthRequest($protocolo,$initdir,$url_self,$serveridp,$athreq,$app,$flag) {
		return $athreq->create_AuthRequest($protocolo, $initdir, $url_self,$serveridp,$app,$flag);
		}
   
   private function xmltoArray($xml) {
	    $xml = new SimpleXMLElement($xml,null,true);
		$serversp = (string)$xml->sp[0];
		$serveridp = (string)$xml->idp[0];
		$app = (string)$xml->app[0];
		$resultarray = array();
		$resultarray[0] = $serversp;
		$resultarray[1] = $serveridp;
		$resultarray[2] = $app;
		return $resultarray;
	   	}



/**
* Inicializa el registro unico de objetos, arreglos, ...
* 
* @return void
*/
    protected function initRegister() {
     $register = new Zend_Registry(array(), ArrayObject::ARRAY_AS_PROPS);
     Zend_Registry::setInstance($register);
    }
	
/**
* Inicializa la configuracion de la aplicacion
* 
* @param array $config - arreglo con la configuracion de la aplicacion
* @return void
*/
    protected function initConfig($config) {
     $configApp = new ZendExt_App_Config();
     $config = $configApp->configApp($config);
     Zend_Registry::getInstance()->config = $config;
    }





}


