<?php

/**
 * Copyright (C) 2011 Ing. Daniel Enrique Lopez Mendez.
 *
 * 
 */



class ZendExt_SAML_Util 
{

/**
 * Crea un string de 40 caracteres.
 * @return string
 */
function samlCreateId() {
  $rndChars = 'abcdefghijklmnop';
  $rndId = '';
  
  for ($i = 0; $i < 40; $i++ ) {
    $rndId .= $rndChars[rand(0,strlen($rndChars)-1)];
  }
  
  return $rndId;
}

/**
 * Devuelve un unix timestamp en formato xsd:dateTime .
 * @param timestamp int UNIX Timestamp para convertirlo en 
   formato xsd:dateTime ISO 8601 .
 * @return string
 */
function samlGetDateTime($timestamp) {
  return gmdate('Y-m-d\TH:i:s\Z', $timestamp);
}

/**
 * Checkea que la fecha SAML sea valida.  
 * @param string $samlDate
 * @return bool
 */
function validSamlDateFormat($samlDate) {
  if ($samlDate == "") return false;
  $indexT = strpos($samlDate, 'T');
  $indexZ = strpos($samlDate, 'Z');
  
  if (($indexT != 10) || ($indexZ != 19)) {
    return false;
  }
  
  $dateString = substr($samlDate, 0, 10);
  $timeString = substr($samlDate, $indexT + 1, 8);
  
  list($year, $month, $day) = explode('-', $dateString);
  list($hour, $minute, $second) = explode(':', $timeString);
  $parsedDate = mktime($hour, $minute, $second, $month, $day, $year);
  if (($parsedDate === FALSE) || ($parsedDate == -1)) return false;

  if (!checkdate($month, $day, $year)) return false;
  
  return true;
  
}
/**
 * Codifica un mensaje en el siguiente orden 
 *
 * 1. Deflate
 * 2. Base64 encode
 * 3. URL encode
 * @param string $msg
 * @return string
 */
function samlEncodeMessage($msg) {
  $encmsg = gzdeflate($msg);
  $encmsg = base64_encode($encmsg);
  $encmsg = urlencode($encmsg);
  return $encmsg;
}

/**
 * Decodifica el mensaje codificado en l siguiente orden:
 *
 * 1. Base64 decode
 * 2. Deflate
 *
 * Devuelve FALSE si el mensaje no pudo ser decodificado.
 * @param string $msg
 * @return string
 */
function samlDecodeMessage($msg) {
  $decmsg = base64_decode($msg);
  $infmsg = gzinflate($decmsg);
  if ($infmsg === FALSE) {
    // si falla gzinflate, probar gzuncompress
    $infmsg = gzuncompress($decmsg);
  };
  return $infmsg;
}

/**
 * Devuelve un arreglo con los valores del SAML Request.
 * @param string $xmlString
 * @return array
 */
function getRequestAttributes($xmlString) {

  if (class_exists("SimpleXMLElement")) {
    //  PHP5 SimpleXML parsing
    $xml = new SimpleXMLElement($xmlString);
    $attr['issueInstant'] = $xml['IssueInstant'];
    $attr['acsURL'] = $xml['AssertionConsumerServiceURL'];
    $attr['providerName'] = $xml['ProviderName'];
    $attr['requestID'] = $xml['ID'];
    
    return $attr;
  } else {
    // expat XML parsing extension
    $p = xml_parser_create();    
    $result = xml_parse_into_struct($p, $xmlString, $vals, $index);
    $attr['issueInstant'] = $vals[0]['attributes']['ISSUEINSTANT'];
    $attr['acsURL'] = $vals[0]['attributes']['ASSERTIONCONSUMERSERVICEURL'];
    $attr['providerName'] = $vals[0]['attributes']['PROVIDERNAME'];
    $attr['requestID'] = $vals[0]['attributes']['ID'];
    
    return $attr;
  }
}


private function loadTemplateAuthReq($config)
                {
			 $this->initRegister();
			 $this->initConfig($config);
			 $register = Zend_Registry::getInstance ();
			 $dirModulesConfig = $register->config->xml->authreq;
			 $xml = file_get_contents($dirModulesConfig);
			 //$xml = new SimpleXMLElement($dirModulesConfig,null,true);
			// echo'<pre>';print_r($xml);die;
			 return $xml;
		}

private function loadTemplateResponse($config)
                {
			// $this->initRegister();
			// $this->initConfig($config);
			 $register = Zend_Registry::getInstance ();
			 $dirModulesConfig = $register->config->xml->response;
			 $xml = file_get_contents($dirModulesConfig);
			 //$xml = new SimpleXMLElement($dirModulesConfig,null,true);
			// echo'<pre>';print_r($xml);die;
			 return $xml;
		}





/**
	 * Crea el SAML authentication request.
	 * @param string $acsURL url del SSO
	 * @param string $providerName Nombre del PS
	 * @return string
	 */
public function createAuthnRequest($config, $acsURL, $providerName) {
	  
	  $tml = $this->loadTemplateAuthReq($config);
	  $tml = str_replace('<PROVIDER_NAME>', $providerName, $tml); 
	  $tml = str_replace('<AUTHN_ID>', $this->samlCreateId(), $tml); 
	  $tml = str_replace('<ACS_URL>', $acsURL, $tml); 
	  $tml = str_replace('<ISSUE_INSTANT>', $this->samlGetDateTime(time()), $tml); 

	  return $tml;
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
public function createSamlResponse($config, $authenticatedUser, $notBefore, $notOnOrAfter, $rsadsa, $requestID, $destination) {


    $samlResponse = $this->loadTemplateResponse($config);
    $samlResponse = str_replace('<USERNAME_STRING>', $authenticatedUser, $samlResponse);
    $samlResponse = str_replace('<RESPONSE_ID>', $this->samlCreateId(), $samlResponse);
    $samlResponse = str_replace('<ISSUE_INSTANT>', $this->samlGetDateTime(time()), $samlResponse);
    $samlResponse = str_replace('<AUTHN_INSTANT>', $this->samlGetDateTime(time()), $samlResponse);
    $samlResponse = str_replace('<NOT_BEFORE>', $notBefore, $samlResponse);
    $samlResponse = str_replace('<NOT_ON_OR_AFTER>', $notOnOrAfter, $samlResponse);
    $samlResponse = str_replace('<ASSERTION_ID>', $this->samlCreateId(), $samlResponse);
    $samlResponse = str_replace('<RSADSA>', strtolower($rsadsa), $samlResponse);
    $samlResponse = str_replace('<REQUEST_ID>', $requestID, $samlResponse);
    $samlResponse = str_replace('<DESTINATION>', $destination, $samlResponse);
    $samlResponse = str_replace('<ISSUER_DOMAIN>', $domainName, $samlResponse);

    return $samlResponse;
}

public function parseData($stringdata)
	{
	$returnarray = explode('-',$stringdata);
	return $returnarray;
	}


public function computeURL($acs,$logout,$ssoURLprocess, $ssoURLresponse, $encauthnRequest, $signauthnRequest, $seed, $relayStateURL,$idp, $acs) {
	  $athreq = md5('SAMLRequest'.$seed);
	  $signathreq = md5('signSAMLRequest'.$seed);
	  $url = $ssoURLprocess;
	  $url .= '?'.$athreq.'=' . $this->samlEncodeMessage($encauthnRequest);
	  $url .= '&'.$signathreq.'=' . $this->samlEncodeMessage($signauthnRequest);
	  $url .= '&'.md5('RelayState'.$seed).'=' .$this->samlEncodeMessage($relayStateURL);
	  $url .= '&'.md5('idp'.$seed).'=' .$this->samlEncodeMessage($idp);
	  $url .= '&'.md5('ssoURLprocess'.$seed).'=' .$this->samlEncodeMessage($ssoURLprocess);
	  $url .= '&'.md5('ssoURLresponse'.$seed).'=' .$this->samlEncodeMessage($ssoURLresponse);
	  $url .= '&'.md5('seed').'=' .$this->samlEncodeMessage($seed);
	  $url .= '&'.md5('acs'.$seed).'=' .$this->samlEncodeMessage($acs);
	  $url .= '&'.md5('logout'.$seed).'=' .$this->samlEncodeMessage($logout);
	  return $url;
	}

public function generateACS($arraystringdata)
		{
			$array_protocol   = explode('/', $_SERVER['SERVER_PROTOCOL']);
			$port             = ":{$_SERVER[SERVER_PORT]}";
			if((string)$arraystringdata[7]=="si")
				{
				$protocolo = "https";
				$port = "";
				}
			 else
				$protocolo 	  = strtolower($array_protocol[0]);
			$requri = $_SERVER['REQUEST_URI'];
			$acsuri = str_replace("create_request.php","ACS",$requri);
		        $acs	  = "{$protocolo}://{$_SERVER['SERVER_NAME']}{$port}{$acsuri}";	
			return $acs;
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

/**
    *generateUrlSelf *
    *Funcion que genera el url del recurso *
    *@param * - *
    *@throws * - *
    *@return * $url_self */
	public function generateUrlSelf()
		{
		        $url_self	  = (string)$this->getServer().(string)$_SERVER['REQUEST_URI'];//"{$protocolo}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
			return $url_self;
		}

private function getServer()
		{
			$dir = '';
			$server = $_SERVER['SERVER_NAME'];
			$array_protocol   = explode('/', $_SERVER['SERVER_PROTOCOL']);
			$port             = ':'.$_SERVER['SERVER_PORT'];
			if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
				{
					$protocolo = "https";
					$port = "";
				}
			 else
					$protocolo 	  = strtolower($array_protocol[0]);
			$dir = "{$protocolo}://{$server}{$port}";
			return $dir;
		}



}

?>
