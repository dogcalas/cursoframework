<?php

/**
 * Copyright (C) 2011 Ing. Daniel Enrique Lopez Mendez.
 *
 * 
 */

class ZendExt_SAML_IdentityProvider_Proces 
{
/**
* Inicializa el registro unico de objetos, arreglos, ...
* 
* @return void
*/
    function initRegister() {
     $register = new Zend_Registry(array(), ArrayObject::ARRAY_AS_PROPS);
     Zend_Registry::setInstance($register);
    }
	
/**
* Inicializa la configuracion de la aplicacion
* 
* @param array $config - arreglo con la configuracion de la aplicacion
* @return void
*/
    function initConfig($config) {
     $configApp = new ZendExt_App_Config();
     $config = $configApp->configApp($config);
     Zend_Registry::getInstance()->config = $config;
    }



/**
 * El metodo de logueo devuelve null si hubo fallo o certificado digital si fue exitoso
 * @param string $username
 * @param string $password
 * @return string
 */


function login($usuario, $password) {
$integrator = ZendExt_IoC::getInstance();
return $integrator->getInstance()->seguridad->AutenticarUsuarioApi($usuario,$password);
}


/**
 * Devuelve un SAML response .
 * @param string $authenticatedUser 
 * @param string $notBefore 
 * @param string $notOnOrAfter 
 * @param string $rsadsa 'rsa' si el response fue firmado con llaves RSA, 'dsa'si el response fue firmado con llaves DSA
 * @param string $requestID 
 * @param string $destination ACS URL 
 * @return string XML SAML response.
 */
function createSamlResponse($authenticatedUser, $notBefore, $notOnOrAfter, $rsadsa, $requestID, $destination) {
    global $domainName;

    $samlResponse = file_get_contents('templates/SamlResponseTemplate.xml');
    $samlResponse = str_replace('<USERNAME_STRING>', $authenticatedUser, $samlResponse);
    $samlResponse = str_replace('<RESPONSE_ID>', samlCreateId(), $samlResponse);
    $samlResponse = str_replace('<ISSUE_INSTANT>', samlGetDateTime(time()), $samlResponse);
    $samlResponse = str_replace('<AUTHN_INSTANT>', samlGetDateTime(time()), $samlResponse);
    $samlResponse = str_replace('<NOT_BEFORE>', $notBefore, $samlResponse);
    $samlResponse = str_replace('<NOT_ON_OR_AFTER>', $notOnOrAfter, $samlResponse);
    $samlResponse = str_replace('<ASSERTION_ID>', samlCreateId(), $samlResponse);
    $samlResponse = str_replace('<RSADSA>', strtolower($rsadsa), $samlResponse);
    $samlResponse = str_replace('<REQUEST_ID>', $requestID, $samlResponse);
    $samlResponse = str_replace('<DESTINATION>', $destination, $samlResponse);
    $samlResponse = str_replace('<ISSUER_DOMAIN>', $domainName, $samlResponse);

    return $samlResponse;
}


public function init($request,$config){
    $util = new ZendExt_SAML_Util();
   if (($request[md5('SAMLRequest' . $util->samlDecodeMessage($request[md5('seed')]))] != '' ) && ($request[md5('signSAMLRequest' . $util->samlDecodeMessage($request[md5('seed')]))] != '')) {
      $sso = ZendExt_SAML_IdentityProvider_Sso::getInstance();
      $sso->init($config);
      $session = $sso->getSession();
      $certificado 	= $session->certificado;
      $rsa = new ZendExt_RSA_Facade();
      

      $SAMLRequest = $util->samlDecodeMessage($request[md5('SAMLRequest' . $util->samlDecodeMessage($request[md5('seed')]))]);
      $signSAMLRequest = $util->samlDecodeMessage($request[md5('signSAMLRequest' . $util->samlDecodeMessage($request[md5('seed')]))]);
      $seed = $util->samlDecodeMessage($request[md5('seed')]);
      $error = '';
      $relayState = $request[md5('RelayState' .$util->samlDecodeMessage($request[md5('seed')]))];
      $ssoURLprocess = $request[md5('ssoURLprocess' .$util->samlDecodeMessage($request[md5('seed')]))];
      $ssoURLresponse = $request[md5('ssoURLresponse' .$util->samlDecodeMessage($request[md5('seed')]))];
      $logout = $request[md5('logout' .$util->samlDecodeMessage($request[md5('seed')]))];
         if ($SAMLRequest == '') {
             $error = 'Error: No existe el SAML Request.';
             }
             else {

             $prove = $rsa->proveMessage($SAMLRequest, $signSAMLRequest);
              if ($prove) {
                  $decSAMLRequest = $rsa->decrypt($SAMLRequest);
		  $encsamlSAMLRequest = $util->samlEncodeMessage($decSAMLRequest);

                   if (($decSAMLRequest == '') || ($decSAMLRequest === FALSE)) {
                        $error = 'No se puede decodificar SAML.';
                        }
                        else {
                        $samlAttr = $util->getRequestAttributes($decSAMLRequest);
			$issueInstant = (string)$samlAttr['issueInstant'];
			$acsURL = (string)$samlAttr['acsURL'];

			$providerName = (string)$samlAttr['providerName'];
			$requestID = (string)$samlAttr['requestID'];
			//$session
			$idp = $util->samlDecodeMessage((string)$request[md5('idp' .$util->samlDecodeMessage($request[md5('seed')]))]);
			$encidp = urlencode($idp);
                        $this->redirectToIdp($error,$util->samlEncodeMessage($issueInstant) /*$issueInstant*/, $acsURL, $util->samlEncodeMessage($providerName)/*$providerName*/, $requestID, $relayState, $seed, $idp, $ssoURLprocess, $ssoURLresponse, $encidp,$util->samlEncodeMessage($certificado)/*$certificado*/,$logout);
                        }
                  }
                  else{
                     die('<h1 style="color:#FF0000">Violacion de seguridad</h1>');
                  }

             }

   }
   else{
       die('<h1 style="color:#FF0000">Violacion de seguridad</h1>');
   }
}


private function redirectToIdp($error, $issueInstant, $acsURL, $providerName, $requestID, $relayState, $seed, $idp, $ssoURLprocess, $ssoURLresponse, $encidp, $certificado,$logout)
                {
                       $locate = "<html><head></head><body onload=\"document.getElementById('form1').submit()\">";
                       //echo '<pre>';print_r($relayState);die;
		       $md5error = md5('error'.$seed);
                       //$md5encsamlSAMLRequest = md5('encsamlSAMLRequest'.$seed);
		       $md5idp = md5('encidp'.$seed);
		       $md5ssoURLprocess = md5('ssoURLprocess'.$seed);
		       $md5ssoURLresponse = md5('ssoURLresponse'.$seed);
                       $md5issueInstant = md5('issueInstant'.$seed);
		       $md5acsURL = md5('acsURL'.$seed);
                       $md5logout = md5('logout'.$seed);
		       $md5providerName = md5('providerName'.$seed);
		       $md5requestID = md5('requestID'.$seed);
		       $md5relayStateURL = md5('relayStateURL'.$seed);
                       $md5certificado = md5('certificado'.$seed);
                       $md5seed = md5('seed');
		       $md5urlback = md5('urlback'.$seed);
                       $locate .= "<form id='form1' action='$idp' method='POST'>";
		       $locate .= "<input type='hidden' name='{$md5error}' value='{$error}'/>";
		      // $locate .= "<input type='hidden' name='{$md5encsamlSAMLRequest}' value='{$encsamlSAMLRequest}'/>";
		       $locate .= "<input type='hidden' name='{$md5idp}' value='{$encidp}'/>";
		       $locate .= "<input type='hidden' name='{$md5ssoURLprocess}' value='{$ssoURLprocess}'/>";
		       $locate .= "<input type='hidden' name='{$md5ssoURLresponse}' value='{$ssoURLresponse}'/>";
		       $locate .= "<input type='hidden' name='{$md5issueInstant}' value='{$issueInstant}'/>";	
		       $locate .= "<input type='hidden' name='{$md5acsURL}' value='{$acsURL}'/>";
		       $locate .= "<input type='hidden' name='{$md5logout}' value='{$logout}'/>";
		       $locate .= "<input type='hidden' name='{$md5providerName}' value='{$providerName}'/>";
                       if($certificado)
                           $locate .= "<input type='hidden' name='{$md5certificado}' value='{$certificado}'/>";
		       $locate .= "<input type='hidden' name='{$md5requestID}' value='{$requestID}'/>";
		       $locate .= "<input type='hidden' name='{$md5relayStateURL}' value='{$relayState}'/>";	
		       $locate .= "<input type='hidden' name='{$md5seed}' value='{$seed}'/>";
		       //$locate .= "<input type='hidden' name='{$md5urlback}' value='{$urlback}'/>";
			//}else{
		      // $locate .= "<form id='form1' action='$idp' method='POST'>";	
		       //$locate .= "<input type='hidden' name='{$md5redirectUrl}' value='{$redirectURL}'/>";
		       $locate .= "</form></body></html>";
			//}
		       echo $locate;
                 
	        }
    
}
?>
