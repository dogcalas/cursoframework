<?php

/**
 * ZendExt_SAML_ServiceProvider_Server *
 * Clase interfaz del Proveedor de Servicios *
 *@ Daniel Enrique L�pez M�ndez * 
 *@Seguridad* (m�dulo)
 *@Autenticacion (subm�dulo)*
 *@copyright ERP Cuba*
 *@version 1.0*/

class ZendExt_SAML_ServiceProvider_Acs 
{

 public function init($post,$config) {
//echo '<pre>';print_r($post);die;
if (($post[md5('encResponse' . $post[md5('seed')])] != '') && ($post[md5('signResponse' . $post[md5('seed')])] != '') && ($post[md5('relayStateURL' . $post[md5('seed')])] != '') && ($post[md5('seed')] != '')) {
 
		$sso = ZendExt_SAML_IdentityProvider_Sso::getInstance();
		$sso->init($config);
		$sso->setUniqueSession(true);
		$rsa = new ZendExt_RSA_Facade();
		$util = new ZendExt_SAML_Util();
		$encResponse = $post[md5('encResponse' . $post[md5('seed')])];
		$signResponse = $post[md5('signResponse' . $post[md5('seed')])];
		//$relayState = $util->samlDecodeMessage($post[md5('relayStateURL' . $post[md5('seed')])]);
		$relayState = $post[md5('relayStateURL' . $post[md5('seed')])];
		$api = $post[md5('api' . $post[md5('seed')])];
		//$providerName =  $util->samlDecodeMessage($post[md5('providerName' . $post[md5('seed')])]);
//
		//if($api == md5('si'))
                $flag = 1;
		//else
                //$flag = $post[md5('flag' . $post[md5('seed')])];
		$seed = $post[md5('seed')];
		$encCertificado = $post[md5('certificado' . $post[md5('seed')])];
		/*if($this->checkSession($providerName,$sso))
			die('<h1 style="color:#FF0000">Hay que ver que eres soquete ya no te autenticaste en este sistema ?</h1>');*/
		$prove = $rsa->proveMessage($encResponse,$signResponse);
//
		 if ($prove) {
		  $decResponse = $rsa->decrypt($encResponse);
		  $attributtes = $this->getRequestAttributes($decResponse);
		  $validate = $this->validSamlDateFormat($attributtes['IssueInstant']);
		  //

		   if($relayState == $attributtes['Destination']){
			if($this->parseTime($this->samlGetDateTime(time()),$attributtes['NotOnOrAfter'])){
				$encseed = $rsa->encrypt($seed);
				$this->redirectToResource($relayState,$encCertificado,$encseed,$rsa->signMessage($encseed),$flag,$seed);
			     }
			
			 else{
			   die('<h1 style="color:#FF0000">Tiempo de autenticacion excedido</h1>');
			  } 
		   }
		   else{
		   die('<h1 style="color:#FF0000">1</h1>');
		  } 
		}
		  else{
		   die('<h1 style="color:#FF0000">2</h1>');
		  } 
		}
		else{
		die('<h1 style="color:#FF0000">jgjyhj</h1>');
		}

 }

function getRequestAttributes($xmlString) {

  if (class_exists("SimpleXMLElement")) {
	
    //  PHP5 SimpleXML parsing
    $xml = new SimpleXMLElement($xmlString);
    $attr['ID'] = (string)$xml['ID'];
    $attr['IssueInstant'] = (string)$xml['IssueInstant'];
    $attr['Destination'] = (string)$xml['Destination'];
    $attr['InResponseTo'] = (string)$xml['InResponseTo'];
    $attr['NotOnOrAfter'] = (string)$xml->Assertion->Conditions['NotOnOrAfter'];
    
    return $attr;
  } else {
    // expat XML parsing extension
    $p = xml_parser_create();    
    $result = xml_parse_into_struct($p, $xmlString, $vals, $index);
    $attr['ID'] = (string)$xml['ID'];
    $attr['IssueInstant'] = (string)$vals[0]['attributes']['IssueInstant'];
    $attr['Destination'] = (string)$vals[0]['attributes']['Destination'];
    $attr['InResponseTo'] = (string)$vals[0]['attributes']['InResponseTo'];
    $attr['NotOnOrAfter'] = (string)$vals[0]->Assertion->Conditions['attributes']['NotOnOrAfter'];
    
    return $attr;
  }
 }

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

function samlGetDateTime($timestamp) {
  return gmdate('Y-m-d\TH:i:s\Z', $timestamp);
}

function parseTime($date,$datecompare) {
  $arraydateT = explode('T',$date);
  $fechadate = $arraydateT[0];
  $horadate = $arraydateT[1];
  $explodefechadate =  explode('-',$fechadate);
  $explodehoradate =  explode(':',$horadate);

  $arraydatecompareT = explode('T',$datecompare);
  $fechadatecompare = $arraydatecompareT[0];
  $horadatecompare = $arraydatecompareT[1];
  $explodefechadatecompare =  explode('-',$fechadatecompare);
  $explodehoradatecompare =  explode(':',$horadatecompare);
  if(($explodefechadate[1] < $explodefechadatecompare[1] || $explodefechadate[2] < $explodefechadatecompare[2]) ||          ((integer)$explodehoradate[0] > (integer)$explodehoradatecompare[0] || ((integer)$explodehoradate[1] > (integer)$explodehoradatecompare[1] )) )
 return false;
 return true;
}


private function redirectToResource($resource,$encCertificado,$encseed,$keyseed,$flag,$seed) {
//
	    $recurso = $resource;
	    $md5certificado = md5('certificado'.$seed);
	    $md5encSeed = md5('encSeed'.$seed);
	    $md5keySeed = md5('keySeed'.$seed);
	    $md5flag = md5('flag'.$seed);
	    $locate = "<html><head></head><body onload=\"document.getElementById('form1').submit()\">";
	    $locate .= "<form id='form1' action='{$recurso}' method='POST'>";
	    if($encCertificado)
	    	$locate .= "<input type='hidden' name='{$md5certificado}' value='{$encCertificado}'/>";
		$locate .= "<input type='hidden' name='{$md5encSeed}' value='{$encseed}'/>";
		$locate .= "<input type='hidden' name='{$md5keySeed}' value='{$keyseed}'/>";
		$locate .= "<input type='hidden' name='{$md5flag}' value='{$flag}'/>";
	    $locate .= "</form></body></html>";
	    echo $locate;
	   	}


private function checkSession($providerName,$sso){
$mark = true;
$session = $sso->getSession();
$datasystem = $session->datasystem;

if($datasystem){
				$array1 = (array)$session->datasystem;
				
					foreach($array1 as $arr){

						if((string)$arr['nombreapp']==(string)$providerName)
							$mark = true;
		                        }
					if($mark){
					$result = $array1;
					}
					else{
					$array2 = array('nombreapp'=>$providerName);
					$result = array($array1, $array2);
					}
				}
				else{
				$result = array('nombreapp'=>$providerName);
				$mark = false;
                                }
				$session->datasystem = $result;

return $mark; 
}

}

?>
