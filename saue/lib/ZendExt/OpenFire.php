<?php
/**
 
 * PHP Plugin de seguridad
 *Autor: Jorge Mendez Barreto
 *CEIGE - HAWG
 *libreria CURL (activada)
 *

 */
 class ZendExt_OpenFire{
 	/**
	 * El modo para recuperar los datos. cURL
	 * @var string
	 */
	private $mode = '';
	
	/**
	 * Localizacion del host
	 * @var string
	 */
	public $host = '';
	
	/**
	 * Puerto de escucha del openfire
	 * @var int
	 */
	public $port = '';
	
	/**
	 * Clave secreta que provee el plugin de seguridad
	 * @var string
	 */
	public $secret = '';
	
	/**
	 * Inicia la clase y se establecen las varaibles que necesitamos para acceder al servidor Openfire.
	 *
	 * @param string $host   Localizacion del host
	 * @param int    $port   Puerto de escucha del openfire
	 * @param string $secret Clave secreta que provee el plugin de seguridad
	 */
	public function __construct() {
	}
	
	/**
	 * Envía una solicitud al servidor Openfire con los parámetros establecidos.
	 *
	 * @param string $type     El tipo de solicitud que está tratando de hacer.
	 *                         Valores Posibles: crear, eliminar, modificar.
	 * @param string $username El nombre de usuario para crear la solicitud.
	 * @param string $password La contraseña que desea establecer para el usuario.
	 *
	 * @return string $result  La respuesta XML en el servidor Openfire.
	 */
	 //$coneccion->$conn['server']
	public function peticion($type, $username, $password = null) {
		$xml = ZendExt_FastResponse::getXML('xmpp');
		//print_r($xml);die;
		$coneccion = $xml->children();
		//print_r($coneccion->conn['server']);die;
		//print_r($coneccion->activo['activo']);die;
		if((string)$coneccion->activo['activo'] == 'true'){
		//echo('pinchaaaaa');
		$url = $coneccion->conn['server'] . ':' .'9090' 
			   . '/plugins/pluginSeguridad/pluginseguridad?'
			   . '&type='. $type
			   . '&username=' . $username
			   . '&password=' . $password
			   . '&secret=' . '54VyW6A6'; 
			   
		
		//if($mode == 'curl') {
			$result = $this->mode_curl($url);
		//}
		//print_r($result);
		//$xx = "true";
		/*if($result){
			throw new ZendExt_Exception('OF02');
		}*/
		return $result;
		}
		else{
			throw new ZendExt_Exception('OF01');
			}
			
	}
	/* Llamar a xml y comprobar el servidor*/
	
	
	/**
	 * Una función auxiliar básica CURL
	 * 
	 * @param string $url La URL que se ejecutará
	 * @return $data Los datos de la ejecución CURL
	 */
	public function mode_curl($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		curl_close($curl); 
		
		return $data;
	}
	
	
	
 }

?>