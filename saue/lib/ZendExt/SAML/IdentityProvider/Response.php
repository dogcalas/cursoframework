<?php 


class ZendExt_SAML_IdentityProvider_Response 
{

public function init($request,$config){
//echo '<pre>';print_r($request);die;

if (($request[md5('relayStateURL' . $request[md5('seed')])] != '') && ($request[md5('returnPage' . $request[md5('seed')])] != '') /* && ($request[md5('password')] || $request[md5('certificado' . $request[md5('seed')])] != '')*/) {
	//$this->initRegister();
        //$this->initConfig($config);

        $sso = ZendExt_SAML_IdentityProvider_Sso::getInstance();
	$sso->init($config);
	$session = $sso->getSession();
	$certificado 	= $session->certificado;
	$rsa = new ZendExt_RSA_Facade();
	$util = new ZendExt_SAML_Util();

        $returnPage = $request[md5('returnPage' . $request[md5('seed')])];
        //$username = $request[md5('username' . $request[md5('seed')])];
	$username = $request['username'];
        $password = $request[md5('truepassword')];
        $requestID = $request[md5('requestID' . $request[md5('seed')])];
	$seed = $request[md5('seed')];
        $decrelayStateURL = $util->samlDecodeMessage($request[md5('RelayState' . $request[md5('seed')])]);
	$relayStateURL = $request[md5('relayStateURL' . $request[md5('seed')])];

	$acsURL = $request[md5('acsURL' . $request[md5('seed')])];
        $providerName = $request[md5('providerName' . $request[md5('seed')])];

		    if($certificado==0){
                        
		      $certificado = $this->login($username, $password);
                      if($certificado!=0){
		      $session->usuario = $username;
		      $session->certificado = $certificado;
		      //
		      $session->setExpirationSeconds(1800, 'certificado');
			}
		    }
		    else{
		      $certificado 	= $session->certificado;
		    }

                    if ($certificado == '' || $certificado == null || $certificado === FALSE || $certificado == 0) {
                        $error = 'Usuario o contrasenna invalidos.';
                    } else {
                        $keyType = 'rsa';
                        // genera NotBefore y NotOnOrAfter
                        $notBefore = $util->samlGetDateTime(strtotime('-5 minutes'));
                        $notOnOrAfter =$util-> samlGetDateTime(strtotime('+1 minutes'));
                        // Firmar XML
                        $responseXmlString =$util->createSamlResponse($config, $username, $notBefore,
                                        $notOnOrAfter, $keyType,
                                        $requestID, $util->samlDecodeMessage($relayStateURL));
				$encResponse  = $rsa->encrypt($responseXmlString);
				$signResponse = $rsa->signMessage($encResponse);
				$encCertificado = $rsa->encrypt($certificado);
				unset($_POST);
        //echo'<pre>';print_r($util->samlDecodeMessage($relayStateURL));die;
     /*$this->redirectToIdp(urldecode($returnPage),$acsURL,$encCertificado,$encResponse,$signResponse,$seed,$relayStateURL,$providerName,$error);*/
                            }
$this->redirectToIdp($responseXmlString,urldecode($returnPage),$acsURL,$encCertificado,$encResponse,$signResponse,$seed,$util->samlDecodeMessage($relayStateURL),$providerName,$error);
                }
                else {
                      die('<h1 style="color:#FF0000">Violacion de seguridad</h1>');
                     }
       
        }




        private function redirectToIdp($response,$returnPage,$acsURL,$encCertificado,$encResponse,$signResponse,$seed,$relayStateURL,$providerName,$error)
                {
                       $locate = "<html><head></head><body onload=\"document.getElementById('form1').submit()\">";
		       $md5returnPage = md5('returnPage'.$seed);
		       $md5acsURL = md5('acsURL'.$seed);
		       $md5encCertificado = md5('encCertificado'.$seed);
		       $md5encResponse = md5('encResponse'.$seed);
                       $md5signResponse = md5('signResponse'.$seed);
                       $md5seed = md5('seed');
                       $md5relayStateURL = md5('relayStateURL'.$seed);
                       $md5providerName = md5('providerName'.$seed);
		       $md5response = md5('response'.$seed);
                       $md5error = md5('error'.$seed);

                       $locate .= "<form id='form1' action='$returnPage' method='POST'>";
		       $locate .= "<input type='hidden' name='{$md5acsURL}' value='{$acsURL}'/>";
		       $locate .= "<input type='hidden' name='{$md5encCertificado}' value='{$encCertificado}'/>";
		       $locate .= "<input type='hidden' name='{$md5encResponse}' value='{$encResponse}'/>";
		       $locate .= "<input type='hidden' name='{$md5signResponse}' value='{$signResponse}'/>";
		       $locate .= "<input type='hidden' name='{$md5seed}' value='{$seed}'/>";
                       $locate .= "<input type='hidden' name='{$md5relayStateURL}' value='{$relayStateURL}'/>";
                       $locate .= "<input type='hidden' name='{$md5providerName}' value='{$providerName}'/>";
		       $locate .= "<input type='hidden' name='{$md5response}' value='{$response}'/>";
                       $locate .= "<input type='hidden' name='{$md5error}' value='{$error}'/>";

		       $locate .= "</form></body></html>";
			//}
		       echo $locate;

	        }

/**
* Inicializa el registro unico de objetos, arreglos, ...
*
* @return void
*/
  private function initRegister() {
     $register = new Zend_Registry(array(), ArrayObject::ARRAY_AS_PROPS);
     Zend_Registry::setInstance($register);
    }

/**
* Inicializa la configuracion de la aplicacion
*
* @param array $config - arreglo con la configuracion de la aplicacion
* @return void
*/
 private function initConfig($config) {
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


private function login($usuario, $password) {
$integrator = ZendExt_IoC::getInstance();
return $integrator->getInstance()->seguridad->AutenticarUsuarioApi($usuario,$password);
}


}

?>
