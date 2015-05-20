<?php
/**
 * ZendExt_RSA_Facade
 * Configurador de la aplicacion
 * 
 * @author Darien García Tejo
 * @package ZendExt
 * @subpackage ZendExt_RSA
 * @copyright UCID - ERP Cuba
 * @version 1.0-0
 */
class ZendExt_RSA_Facade {
	
	/**
     * Constructor de la clase
     */
    public function __construct () {
    	
	}
	
	function encrypt($string) {
		$RSA = new ZendExt_RSA();
		$keys = $RSA->generate_keys ('9990454949', '9990450271', 0);	
		$encoded = $RSA->encrypt ($string, $keys[1], $keys[0], 3);
		return $encoded;
	}
	
	function decrypt($encoded) {
		$RSA = new ZendExt_RSA();
		$keys = $RSA->generate_keys ('9990454949', '9990450271', 0);
		$decoded = $RSA->decrypt ($encoded, $keys[2], $keys[0]);
		return $decoded;
	}
	
	function signMessage($message)
	{
	 $RSA = new ZendExt_RSA();
	 $keys = $RSA->generate_keys ('9990454949', '9990450271', 0);
	 //echo "<pre>"; print_r($message); die;
	 $signature = $RSA->sign($message, $keys[2], $keys[0]);
	 
	 //echo "<pre>"; print_r($signature); die;
	 return $signature;
	}
	
	function proveMessage($message,$signature) {
	 $RSA = new ZendExt_RSA();
	 $keys = $RSA->generate_keys ('9990454949', '9990450271', 0);
	 $response = $RSA->prove($message, $signature, $keys[1], $keys[0]);
	 return $response;
	}
}