<?php 


class ZendExt_SAML_IdentityProvider_SSOResponse 
{

public function init($request,$config){
	$encstringdata = $request[md5('encstringdata'.$request[md5('seed')])];
        $urlself = $request[md5('urlself'.$request[md5('seed')])];
	$encrelayStateUrl = $request[md5('encrelayStateUrl'.$request[md5('seed')])];

	$seed = $request[md5('seed')];
	$username = $request['username'];
	$password = $request[md5('truepassword')];

    	if(isset($seed) && isset($encrelayStateUrl) && isset($urlself) && isset($encstringdata)){
		$sso = ZendExt_SAML_IdentityProvider_Sso::getInstance();
		$sso->init($config);
		$session = $sso->getSession();
		$certificado 	= $session->certificado;
		$rsa = new ZendExt_RSA_Facade();
		$util = new ZendExt_SAML_Util();
		$stringdata = $rsa->decrypt($encstringdata);
		$arraydata = explode('@',$stringdata);
		if($certificado==0){
                        
		      $certificado = $this->login($username, $password);
//
                      if($certificado!=0){
		      $session->usuario = $username;
		      $session->certificado = $certificado;
		      $session->setExpirationSeconds(1800, 'certificado');
			}
		    }
		    else{
		      $certificado 	= $session->certificado;
		    }

		 if ($certificado == '' || $certificado == null || $certificado === FALSE || $certificado == 0) {
                        $error = 'Usuario o contraseña invalidos.';
			$this->redirectToLogin($encstringdata,$urlself,$encrelayStateUrl,$seed,$error);
                    } else {
                        $keyType = 'rsa';
                        // genera NotBefore y NotOnOrAfter
                        $notBefore = $util->samlGetDateTime(strtotime('-5 minutes'));
                        $notOnOrAfter =$util-> samlGetDateTime(strtotime('+1 minutes'));
                        // Firmar XML
                        $responseXmlString =$util->createSamlResponse($config, $username, $notBefore,
                        $notOnOrAfter, $keyType, $arraydata[3], $rsa->decrypt($encrelayStateUrl));
			$encResponse  = $rsa->encrypt($responseXmlString);
			$signResponse = $rsa->signMessage($encResponse);
			$encCertificado = $rsa->encrypt($certificado);
			unset($_POST);		
			
			$this->redirectToAcs($encResponse,$signResponse,$rsa->decrypt($encrelayStateUrl),$rsa->encrypt($certificado),md5('no'),1,$seed,$arraydata[1],$arraydata[2]);
	   }
        }
	else{
			
	}	
}


private function redirectToLogin($encstringdata,$urlself,$encrelayStateUrl,$seed,$error) {
	$dir = $urlself.'login/';
        $md5encstringdata = md5('encstringdata'.$seed);
        $md5urlself = md5('urlself'.$seed);
	$md5encrelayStateUrl = md5('encrelayStateUrl'.$seed);
	$md5error = md5('error'.$seed);
	$md5flag = md5('flag'.$seed);
	$md5seed = md5('seed');
        $locate  = "<html><head></head><body onload=\"document.getElementById('form1').submit()\">";
        $locate .= "<form id='form1' action='$dir' method='POST'>";
        $locate .= "<input type='hidden' name='$md5encstringdata' value='$encstringdata'/>";
        $locate .= "<input type='hidden' name='$md5urlself' value='$urlself'/>";
	$locate .= "<input type='hidden' name='$md5encrelayStateUrl' value='$encrelayStateUrl'/>";
	$locate .= "<input type='hidden' name='$md5error' value='$error'/>";
	$locate .= "<input type='hidden' name='$md5seed' value='$seed'/>";
  	$locate .= "<input type='hidden' name='$md5flag' value='1'/>";
        echo $locate;
   }


private function redirectToAcs($encResponse,$signResponse,$relayStateURL,$certificado,$api,$flag,$seed,$acs,$providerName) {
	    $md5encResponse = md5('encResponse'.$seed);
	    $md5signResponse = md5('signResponse'.$seed);
	    $md5relayStateURL = md5('relayStateURL'.$seed);
	    $md5certificado = md5('certificado'.$seed);
	    $md5api = md5('api'.$seed);
	    $md5flag = md5('flag'.$flag);
	    $md5seed = md5('seed');
	    $md5providerName = md5('providerName'.$seed);
	    $locate = "<html><head></head><body onload=\"document.getElementById('form1').submit()\">";
	    $locate .= "<form id='form1' action='{$acs}' method='POST'>";
            $locate .= "<input type='hidden' name='{$md5encResponse}' value='{$encResponse}'/>";
	    $locate .= "<input type='hidden' name='{$md5signResponse}' value='{$signResponse}'/>";
	    $locate .= "<input type='hidden' name='{$md5relayStateURL}' value='{$relayStateURL}'/>";
	    $locate .= "<input type='hidden' name='{$md5certificado}' value='{$certificado}'/>";
	    $locate .= "<input type='hidden' name='{$md5api}' value='{$api}'/>";
	    $locate .= "<input type='hidden' name='{$md5flag}' value='{$flag}'/>";
	    $locate .= "<input type='hidden' name='{$md5seed}' value='{$seed}'/>";
	    $locate .= "<input type='hidden' name='{$md5providerName}' value='{$providerName}'/>";
	    $locate .= "</form></body></html>";
	    echo $locate;
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


private function login($usuario, $password) {
$integrator = ZendExt_IoC::getInstance();
return $integrator->getInstance()->seguridad->AutenticarUsuarioApi($usuario,$password);
}

}
?>
