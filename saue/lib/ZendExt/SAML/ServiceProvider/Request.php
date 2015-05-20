<?php

/**
 * Copyright (C) 2011 Ing. Daniel Enrique Lopez Mendez.
 *
 * 
 */


class ZendExt_SAML_ServiceProvider_Request 
{

public function init($request,$config){
		$returnPage = $request[md5('returnPage'.$request[md5('seed')])];
		$stringdatasign = $request[md5('stringdatasign'.$request[md5('seed')])];
		$encdata = $request[md5('encdata'.$request[md5('seed')])];
		$seed = $request[md5('seed')];
		//$acs = $request[md5('acs'.$request[md5('seed')])];
		$rsa  = new ZendExt_RSA_Facade();
		$util = new ZendExt_SAML_Util();
		//
		$prove = $rsa->proveMessage($encdata,$stringdatasign);
		if($prove)
			{
				$stringdata = $rsa->decrypt($encdata);
				$arraystringdata = $util->parseData($stringdata);
				$ssoURLprocess = 'process_index.php';
				$ssoURLresponse = 'response_index.php';
				$idpURL = 'identity_provider.php';
				$providerName = $arraystringdata[2];
				$domainName = 'acaxia.uci.cu';
				$relayStateURL = $arraystringdata[6];
				$acsURL = $arraystringdata[7];
                                //echo '<pre>';print_r($acs);die;
				$logout = urldecode($stringdata[8]);
				$authnRequest = '';
		  		$encauthnRequest = '';
				$signauthnRequest = '';
				$redirectURL = '';
				$error = '';

				$authnRequest = $util->createAuthnRequest($config, urldecode($acsURL), $providerName);
				
				if ($authnRequest == NULL) {
				  $error = 'Error: no se encuentra la plantilla del REQUEST';
				};
				
				
				$encauthnRequest  = $rsa->encrypt($authnRequest);
				$signauthnRequest = $rsa->signMessage($encauthnRequest);
				$redirectURL = $util->computeURL($acsURL,$logout,$arraystringdata[1].'idp/'.$ssoURLprocess, $arraystringdata[1].'idp/'.$ssoURLresponse, $encauthnRequest, $signauthnRequest, $seed, $relayStateURL,$arraystringdata[1].'idp/'.$idpURL);
				$this->redirect($authnRequest,$arraystringdata[1].'idp/'.$ssoURLprocess, $encauthnRequest, $signauthnRequest, $seed, $relayStateURL, $acs, $arraystringdata[1].'sp/'.$returnPage,$redirectURL, $arraystringdata[7]);

			}
		else
			{
				die('<h1 style="color:#FF0000">Violacion de seguridad</h1>');
			}
	}

private function redirect($authnRequest,$ssoURL, $encauthnRequest, $signauthnRequest, $seed, $relayStateURL, $acs, $dir,$redirectURL, $api)
                {
                      
                       $md5encauthnRequest = md5('encauthnRequest'.$seed);
		       $md5seed = md5('seed');
		       $md5redirectUrl = md5('redirectURL'.$seed);
                       $md5authnRequest = md5('authnRequest'.$seed);
                       $locate = "<html><head></head><body onload=\"document.getElementById('form1').submit()\">";
                       $locate .= "<form id='form1' action='$dir' method='POST'>";
		       $locate .= "<input type='hidden' name='{$md5encauthnRequest}' value='{$encauthnRequest}'/>";
		       $locate .= "<input type='hidden' name='{$md5seed}' value='{$seed}'/>";	
		       $locate .= "<input type='hidden' name='{$md5redirectUrl}' value='{$redirectURL}'/>";
                       $locate .= "<input type='hidden' name='{$md5authnRequest}' value='{$authnRequest}'/>";
		       $locate .= "</form></body></html>";

		       echo $locate;
                 
	        }

}


?>
