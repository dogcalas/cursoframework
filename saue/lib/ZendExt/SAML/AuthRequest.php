<?php

/**
 * ZendExt_SAML_AuthRequest *
 * Clase que representa al primer xml en la comunicación SAML *
 *@ Daniel Enrique López Méndez * 
 *@Seguridad* (módulo)
 *@Autenticacion (submódulo)*
 *@copyright ERP Cuba*
 *@version 1.0*/

class ZendExt_SAML_AuthRequest
    {

        function _construct()
        {

        }


        public function create_AuthRequest($protocolo, $initdir, $url_self, $serveridp,$app,$flag) {
            $saml 				= new ZendExt_SAML_Generate();
            $doc 				= new DomDocument("1.0");
            $doc->formatOutput 	= true;
            $root 				= $doc->createElement("data");
            $doc->appendChild($root);
            $assert 			= $doc->createElement("assertion");
            $assert->setAttribute("name","Authentication_Request");
            $root->appendChild($assert);
            $ident 				= $doc->createElement("identification");
            $ident->setAttribute("id",$saml->samlCreateId());
            $root->appendChild($ident);
            $resourceurl 		= $doc->createElement("resource");
            $resourceurl->setAttribute("url",$initdir);
            $root->appendChild($resourceurl);
            $comunication 		= $doc->createElement("samlconcept");
            $comunication->setAttribute("idp_url",$serveridp);
            $comunication->setAttribute("sp_url",$url_self);
            $root->appendChild($comunication);
            $time 				= $doc->createElement("time");
            $time->setAttribute("timedate",$saml->samlGetDateTime(time()));
            $root->appendChild($time);
            $comunicationprotocol = $doc->createElement("protocol");
            $comunicationprotocol->setAttribute("protocol",$protocolo);
            $root->appendChild($comunicationprotocol);
			$typexml = $doc->createElement("type_xml");
            $typexml->setAttribute("datatype",$flag);
            $root->appendChild($typexml);
			$appname = $doc->createElement("app_name");
            $appname->setAttribute("name",$app);
            $root->appendChild($appname);
            $xml=$doc->saveXML();
            return $xml;
        }
		
		function data_extract($dec_xml) {
			$xml 	 = array();
			$dom 	 = new DOMDocument();
			$dom->loadXML($dec_xml);
			$el 	 = $dom->getElementsByTagName("assertion")->item(0);
			$xml[0]  = (string)$el->getAttribute("name");
			$el 	 = $dom->getElementsByTagName("response_id")->item(0);
			$xml[1]  = (string)$el->getAttribute("idr");
			$el 	 = $dom->getElementsByTagName("authentication_id")->item(0);
			$xml[2]  = (string)$el->getAttribute("ida");
			$el 	 = $dom->getElementsByTagName("destination")->item(0);
			$xml[3]  = (string)$el->getAttribute("url");
			$el 	 = $dom->getElementsByTagName("issue_instant")->item(0);
			$xml[4]  = (string)$el->getAttribute("dater");
			$el 	 = $dom->getElementsByTagName("authentication_instant")->item(0);
			$xml[5]  = (string)$el->getAttribute("datea");
			$el 	 = $dom->getElementsByTagName("not_before")->item(0);
			$xml[6]  = (string)$el->getAttribute("nb");
			$el 	 = $dom->getElementsByTagName("not_on_or_after")->item(0);
			$xml[7]  = (string)$el->getAttribute("noa");
			$el 	 = $dom->getElementsByTagName("resource")->item(0);
			$xml[8]  = (string)$el->getAttribute("url");
			$el 	 = $dom->getElementsByTagName("certificate")->item(0);
			$xml[9]  = (string)$el->getAttribute("datacertificate");
			$el 	 = $dom->getElementsByTagName("xml_type")->item(0);
			$xml[10] = (string)$el->getAttribute("datatype");
			return $xml;
			}
 }
?>
