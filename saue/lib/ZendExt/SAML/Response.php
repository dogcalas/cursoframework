<?php

/**
 * ZendExt_SAML_Response *
 * Clase que representa al segundo xml en la comunicación SAML *
 *@ Daniel Enrique López Méndez * 
 *@Seguridad* (módulo)
 *@Autenticacion (submódulo)*
 *@copyright ERP Cuba*
 *@version 1.0*/

class ZendExt_SAML_Response
{

public function _construct()
{}

public function create_saml_response($id_auth,$auth_time,$url,$resource,$certificate,$type)
{
$saml = new ZendExt_SAML_Generate();
$doc = new DomDocument("1.0");
$doc->formatOutput = true;
$root = $doc->createElement("data");
$doc->appendChild($root);
$assert = $doc->createElement("assertion");
$assert->setAttribute("name","Saml Response");
$root->appendChild($assert);
$assert_id = $doc->createElement("response_id");
$assert_id->setAttribute("idr",$saml->samlCreateId());
$root->appendChild($assert_id);
$assert_auth_id = $doc->createElement("authentication_id");
$assert_auth_id->setAttribute("ida",$id_auth);
$root->appendChild($assert_auth_id);
$assert_instant = $doc->createElement("issue_instant");
$assert_instant->setAttribute("dater",$saml->samlGetDateTime(time()));
$root->appendChild($assert_instant);
$assert_auth_instant = $doc->createElement("authentication_instant");
$assert_auth_instant->setAttribute("datea",$auth_time);
$root->appendChild($assert_auth_instant);
$assert_not_before = $doc->createElement("not_before");
$assert_not_before->setAttribute("nb",$this->notBefore());
$root->appendChild($assert_not_before);
$assert_not_on_or_after = $doc->createElement("not_on_or_after");
$assert_not_on_or_after->setAttribute("noa",$this->notOnOrAfter());
$root->appendChild($assert_not_on_or_after);
$assert_destination = $doc->createElement("destination");
$assert_destination->setAttribute("url",$url);
$root->appendChild($assert_destination);
$resource_destination = $doc->createElement("resource");
$resource_destination->setAttribute("url",$resource);
$root->appendChild($resource_destination);
$certificate_response = $doc->createElement("certificate");
$certificate_response->setAttribute("datacertificate",$certificate);
$root->appendChild($certificate_response);
$xml_type = $doc->createElement("xml_type");
$xml_type->setAttribute("datatype",$type);
$root->appendChild($xml_type);
$xml=$doc->saveXML();
// echo "<pre>"; print_r($xml); echo "</pre>"; die();
return $xml;
}

	public function data_extract($dec_xml) {
		$xml 	= array();
		$dom 	= new DOMDocument();
		$dom->loadXML($dec_xml);
		$el 	= $dom->getElementsByTagName("assertion")->item(0);
		//echo '<pre>';print_r((string)$el->getAttribute("name"));die($dec_xml);
		$xml[0] = (string)$el->getAttribute("name");
		$el 	= $dom->getElementsByTagName("identification")->item(0);
		$xml[1] = (string)$el->getAttribute("id");
		$el 	= $dom->getElementsByTagName("resource")->item(0);
		$xml[2] = (string)$el->getAttribute("url");
		$el 	= $dom->getElementsByTagName("samlconcept")->item(0);
		$xml[3] = (string)$el->getAttribute("sp_url");
		$xml[4] = (string)$el->getAttribute("idp_url");
		$el 	= $dom->getElementsByTagName("time")->item(0);
		$xml[5] = (string)$el->getAttribute("timedate");
		$el 	= $dom->getElementsByTagName("protocol")->item(0);
		$xml[6] = (string)$el->getAttribute("protocol");
		$el 	= $dom->getElementsByTagName("type_xml")->item(0);
		$xml[7] = (string)$el->getAttribute("datatype");
		$el 	= $dom->getElementsByTagName("app_name")->item(0);
		$xml[8] = (string)$el->getAttribute("name");
		return $xml;
		}

	public function validSamlDateFormat($samlDate) {
		if ($samlDate == "")
		return false;
		$indexT = strpos($samlDate, 'T');
		$indexZ = strpos($samlDate, 'Z');
		if (($indexT != 10) || ($indexZ != 19))
			return false;
		$dateString = substr($samlDate, 0, 10);
		$timeString = substr($samlDate, $indexT + 1, 8);
		$result = explode('?', $dateString);
		$server = $result[0];
		list($year, $month, $day) = explode('-', $dateString);
		list($hour, $minute, $second) = explode(':', $timeString);
		$parsedDate = mktime($hour, $minute, $second, $month, $day, $year);
		if (($parsedDate === FALSE) || ($parsedDate == -1))
			return false;
		if (!checkdate($month, $day, $year))
			return false;
		return true;
		}

	public function notOnOrAfter() {
	 	$saml = new ZendExt_SAML_Generate();
	 	return   $saml->samlGetDateTime(strtotime('-5 minutes'));
		}

	public function notBefore() {
	 	$saml = new ZendExt_SAML_Generate();
	 	return   $saml->samlGetDateTime(strtotime('+10 minutes'));   
		}
}
?>