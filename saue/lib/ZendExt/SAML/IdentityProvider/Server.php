<?php
/**
 * ZendExt_SAML_IdentityProvider_Server *
 * Clase interfaz del Proveedor de Identidad *
 *@ Daniel Enrique López Méndez * 
 *@ Darien García Tejo*
 *@ Yoandry Morejón Borbón* 
 *@Seguridad* (módulo)
 *@Autenticacion (submódulo)*
 *@copyright ERP Cuba, UCID - FAR*
 *@version 1.0*/
class ZendExt_SAML_IdentityProvider_Server {
	
/**
*init *
*Funcion principal de la clase *
*@param *($request, $config)*
*@throws *Acceso Denegado Mensaje Corrupto*
*@return *-*/
  	public function init($request, $config) {
	   $rsa = new ZendExt_RSA_Facade();
	   $util = new ZendExt_SAML_Util();
	   $encAuthnRequest = $request[md5('encAuthnRequest'.$request[md5('seed')])];
	   $signAuthnRequest = $request[md5('signAuthnRequest'.$request[md5('seed')])];
	   $encrelayStateUrl = $request[md5('encrelayStateUrl'.$request[md5('seed')])];
	   $seed = $request[md5('seed')];

	   if(isset($encAuthnRequest) && isset($signAuthnRequest) && isset($encrelayStateUrl) && isset($seed)){

		    $prove = $rsa->proveMessage($encAuthnRequest,$signAuthnRequest);

                    if($prove){
				$AuthnRequest= $rsa->decrypt($encAuthnRequest);
				$samlAttr = $util->getRequestAttributes($AuthnRequest);
				$urlself = $util->generateUrlSelf();
				$arreglodatos = array();
				$arreglodatos[0] = (string)$samlAttr['issueInstant'];
				$arreglodatos[1] = (string)$samlAttr['acsURL'];
				$arreglodatos[2] = (string)$samlAttr['providerName'];
				$arreglodatos[3] = (string)$samlAttr['requestID'];
				$stringdata = $this->parseData($arreglodatos);
				$sso = ZendExt_SAML_IdentityProvider_Sso::getInstance();
				$sso->init($config);
				$session = $sso->getSession();
				$certificado 	= $session->certificado;
//echo'<pre>';print_r($certificado);die;
				$this->redirectToLogin($rsa->encrypt($stringdata),$urlself,$encrelayStateUrl,$seed,$certificado);
				//
		              }
		    else{
			 die('<h1 style="color:#FF0000">Violacion de seguridad 1.</h1>');
		        }
		}
	   else{
		die('<h1 style="color:#FF0000">Violacion de seguridad 2.</h1>');
	       }
 	}

private function redirectToLogin($encstringdata,$urlself,$encrelayStateUrl,$seed,$certificado) {
	            
	if(!$certificado)
	$dir = $urlself.'login/';
        else
	$dir = $urlself.'response/index.php';
//echo '<pre>';print_r($dir);die;   
        $md5encstringdata = md5('encstringdata'.$seed);
        $md5urlself = md5('urlself'.$seed);
	$md5encrelayStateUrl = md5('encrelayStateUrl'.$seed);
        $md5seed = md5('seed');
        $locate  = "<html><head></head><body onload=\"document.getElementById('form1').submit()\">";
        $locate .= "<form id='form1' action='$dir' method='POST'>";
        $locate .= "<input type='hidden' name='$md5encstringdata' value='$encstringdata'/>";
        $locate .= "<input type='hidden' name='$md5urlself' value='$urlself'/>";
	$locate .= "<input type='hidden' name='$md5encrelayStateUrl' value='$encrelayStateUrl'/>";
	$locate .= "<input type='hidden' name='$md5seed' value='$seed'/>";
        echo $locate;
   }


/**
    *parseData *
    *Funcion que parsea los datos leidos del xml de configuracion y los devuelve en un string *
    *@param * $arraydata *
    *@throws * - *
    *@return * $stringdata */
	private function parseData($arraydata)
		{
			$stringdata ="";
		        for($i=0;$i<count($arraydata);$i++)
				{
					$stringdata = $stringdata.(string)$arraydata[$i];
					if($i!=count($arraydata)-1)
						$stringdata = $stringdata.'@';
				}
		
			return 	$stringdata;
		}



	/*private function httpsLogin($request, $config){
		$xml_response;  
	   	$xmlarray;
	   	$this->initRegister();
	  	$this->initConfig($config);
	   	$this->initSession();
	   	$rsa  = new ZendExt_RSA_Facade();
	   	$saml = new ZendExt_SAML_Response();
	    if(!$request['log_entrar']) {
		    $prove = $rsa->proveMessage($request['xml_ath_req'],$request['signkey']);
			$seed_attribute 	= $request['seed'];
			$keyseed_attribute  = $request['keyseed'];
			$proveseed 			= $rsa->proveMessage($seed_attribute,$keyseed_attribute);
			if($prove && $proveseed) {
	             $dec_xml  = $rsa->decrypt($request['xml_ath_req']);
				 $dec_seed = $rsa->decrypt($seed_attribute);
			   }else
			   		die('<h1 style="color:#FF0000">Se ha intentado acceder a la pagina de forma incorrecta o no tiene priveligios de acceso</h1>');
		 }else {
			   $dec_xml  = $rsa->decrypt($_COOKIE['xml_ath']);
			   $dec_seed = $rsa->decrypt($_COOKIE['seedcookie']);
			   }
	     $xmlarray  	= $saml->data_extract($dec_xml);
	     $type 			= $xmlarray[7];	
		 $session 		= Zend_Registry::getInstance()->session;
	     $certificado 	= $session->certificado;
	   	 if($certificado) {
	    	$prove = $rsa->proveMessage($request['xml_ath_req'],$request['signkey']);
			$seed_attribute 	= $request['seed'];
			$keyseed_attribute  = $request['keyseed'];
			$proveseed 			= $rsa->proveMessage($seed_attribute,$keyseed_attribute);
		 	if($prove && $proveseed) {
		   		if($type == '1') {
		    		$xml_response 	= $saml->create_saml_response($xmlarray[1], $xmlarray[5], $xmlarray[3], $xmlarray[2], $certificado, $type);
					$enc_xml 		= $rsa->encrypt($xml_response);
					$keycr 			= $rsa->signMessage($enc_xml);
					$dec_seed 		= $rsa->decrypt($seed_attribute);
					$enc_seed 		= $rsa->encrypt($dec_seed);
					$keyseed 		= $rsa->signMessage($enc_seed);
					$this->redirectToServiceProvider($certificado, $request, $enc_xml, $xmlarray[3],$type,$keycr,$enc_seed,$keyseed);
		   		}else {
		    		$this->Logout();
	        		$session->unsetAll();
					$certificado = $session->certificado;
			 		if(!$certificado)
		      			$this->showLoginWindow($request,true,$xmlarray[4]);
		   			}
		 	}
		 	else
		  		die('<h1 style="color:#FF0000">Se ha intentado acceder a la pagina de forma incorrecta o no tiene priveligios de acceso</h1>');
	   }else {
		  if(!$request['log_entrar']) {
		   	$prove 				= $rsa->proveMessage($request['xml_ath_req'],$request['signkey']);
	       	$seed_attribute 	= $request['seed'];
		   	$keyseed_attribute 	= $request['keyseed'];
		   	$proveseed 			= $rsa->proveMessage($seed_attribute,$keyseed_attribute);
		  	if($prove && $proveseed) {
			  setcookie("xml_ath",$request['xml_ath_req']);
	          setcookie("flag",$xmlarray[7]);
			  setcookie("seedcookie",$seed_attribute);
			  $this->showLoginWindow($request,true,$xmlarray[4]);
			 }else
			  	die('<h1 style="color:#FF0000">Se ha intentado acceder a la pagina de forma incorrecta o no tiene priveligios de acceso</h1>');
		  }else {
			 $certificado 			= $this->login($request['user'],$request['pass']);
			 $session->certificado 	= $certificado;
			 $session->usuario 		= $request['user'];
			 $dec_cookie_xml 		= $rsa->decrypt($_COOKIE['xml_ath']);
			 $dec_seed 				= $rsa->decrypt($_COOKIE['seedcookie']);
			 $cookiearrayxml 		= $saml->data_extract($dec_cookie_xml); 
			 $session->entidad 		= $cookiearrayxml[8];
			 $session->seed 		= $_COOKIE['seedcookie'];
			 $cookieflag 			= $_COOKIE['flag'];
			  if(!$certificado)
			    $this->showLoginWindow($request,false,$cookiearrayxml[4]);
			   else {
				 $xml_response 	= $saml->create_saml_response($cookiearrayxml[1], $cookiearrayxml[5], $cookiearrayxml[3], $cookiearrayxml[2], $certificado, $cookieflag);
				 $enc_xml 		= $rsa->encrypt($xml_response);
				 $enc_seed 		= $rsa->encrypt($dec_seed);
			     $keycr 		= $rsa->signMessage($enc_xml);
				 $keyseed 		= $rsa->signMessage($enc_seed);
				 unset($_REQUEST['log_entrar']);
				 unset($request['log_entrar']);
				 unset($_COOKIE["xml_ath"]);
				 unset($_COOKIE["flag"]);
				 unset($_COOKIE["seedcookie"]);
				 setcookie("xml_ath",'');
				 setcookie("flag",'');
				 setcookie("seedcookie",'');
			     $this->redirectToServiceProvider($certificado, $request, $enc_xml, $xmlarray[3],$type,$keycr,$enc_seed,$keyseed);
				}
			}
	    }
	}

	private function httpLogin($request, $config) {
		$xml_response;
		$xmlarray;
		//Se inicializa el registro
		$this->initRegister();
		//Se inicializa la configuracion de la aplicacion
		$this->initConfig($config);
		//Se inicializa la configuracion de la session
		$this->initSession();
		$session 	 = Zend_Registry::getInstance()->session;
		$certificado = $session->certificado;
		$type		 = 0;
		$rsa 		 = new ZendExt_RSA_Facade();
		$saml 		 = new ZendExt_SAML_Response();
		$dec_xml 	 = $rsa->decrypt($request['xml_ath_req']);
		$prove 		 = $rsa->proveMessage($request['xml_ath_req'],$request['signkey']);
		if($prove) {
			$xmlarray = $saml->data_extract($dec_xml);
			$type = $xmlarray[7];
	        $flag = $request['flag'];
			$register1 = Zend_Registry::getInstance();
				if (!$certificado) { 
					if($flag == 2) {
						if(!isset($_SESSION['log'])) {
							$this->Logout();
							$session->unsetAll();
							}
						}
					if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
						$certificado = $this->createCertificate($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
					else
						$this->showLoginWindow(0,array());
					if ($certificado) {
					  if(isset($_SESSION['log']))
					  	unset($_SESSION['log']);
				      $session->certificado = $certificado;
					}
					else
					  $this->showLoginWindow(0,array());
				}
				$seed_attribute = $request['seed'];
				$dec_seed 		= $rsa->decrypt($seed_attribute);
				$enc_seed 		= $rsa->encrypt($dec_seed);
				$keyseed 		= $rsa->signMessage($enc_seed);
			    $xml_response 	= $saml->create_saml_response($xmlarray[1], $xmlarray[5], $xmlarray[3], $xmlarray[2], $certificado, $type);
			    $enc_xml 		= $rsa->encrypt($xml_response);
	            $keycr 			= $rsa->signMessage($enc_xml);	
			    $this->redirectToServiceProvider($certificado, $request, $enc_xml, $xmlarray[3],$type,$keycr,$enc_seed, $keyseed);
		}
		else
		 die('<h1 style="color:#FF0000">Acceso Denegado Mensaje Corrupto</h1>');
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
* Inicializa la session del usuario
* 
* @return void
*/
    protected function initSession() {
     $config = Zend_Registry::getInstance()->config;
     session_save_path($config->session_save_path);
     Zend_Session::start(array('save_path' => $config->session_save_path));
     $session = new Zend_Session_Namespace ('Acaxia_Identity_Provider');
     $cacheObj = ZendExt_Cache::getInstance();
     $cacheData = $cacheObj->load(session_id());
     if (!isset($cacheData->initialized)) {
      Zend_Session::regenerateId();
      $sessionStd = new stdClass();
      $sessionStd->initialized = true;
      $cacheObj->save($sessionStd, session_id());
      }
     $register = Zend_Registry::getInstance();
     $register->session = $session;
    }

/**
*redirectToServiceProvider *
*Funcion que redirecciona hacia el Proveedor de Servicios *
*@param *($certificado, $request, $xml_response, $sp, $type,$keycr)*
*@throws *-*
*@return *-*/
	
   /* private function redirectToServiceProvider($certificado, $request, $xml_response, $service_provider, $type, $keycr, $enc_seed, $keyseed) {
     $locate = "<html><head></head><body onload=\"document.getElementById('form1').submit()\">";
     $locate .= "<form id='form1' action='$service_provider' method='POST'>";
     if($type == 2)
      $locate .= "<input type='hidden' name='flag' value='{$type}'/>";
     $locate .= "<input type='hidden' name='xml_saml_response' value='{$xml_response}'/>";
     $locate .= "<input type='hidden' name='certificado' value='{$certificado}'/>";
     $locate .= "<input type='hidden' name='signkeyr' value='{$keycr}'/>";
	 $locate .= "<input type='hidden' name='seed' value='{$enc_seed}'/>";
     $locate .= "<input type='hidden' name='keyseed' value='{$keyseed}'/>";
     $locate .= "</form></body></html>";
     echo $locate;
    }
	*/
/**
*login*
*Funcion que gestiona el logueo del usuario*
*@param *-*
*@throws *-*
*@return *-*/
	/*private function login($user,$pass) {
      return $this->createCertificate($user, $pass);
    }*/

/**
*showLoginWindow*
*Fachada para levantar la ventana de autenticacion segun el protocolo*
*@param *-*
*@throws *-*
*@return *-*/
	private function showLoginWindow($valid,$dir) {
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
			$this->httpsShowLoginWindow($valid,$dir);
		else 
			$this->httpShowLogonWindow();
	}
	
/**
*httpShowLogonWindow*
*Levanta la ventana de autenticacion del navegador*
*Protocole: HTTP
*@param *-*
*@throws *-*
*@return *-*/
	protected function httpShowLogonWindow () {
		//Se muetra la ventana de autenticacion. 
		header('WWW-Authenticate: Basic realm="Acaxia"');
		//Si cancela la autenticacion se muestra un mensaje de acceso denegado
		header('HTTP/1.0 401 Unauthorized');
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) //Si la peticion es por ajax
			die(json_encode (array('codMsg'=>3,'mensaje'=>'<b> Acceso denegado </b>'))); //Se imprime la excepcion en codigo json
		else
			die('<h1 style="color:#FF0000">Se ha intentado acceder a la pagina de forma incorrecta o no tiene priveligios de acceso</h1>'); 
		}
		
/**
*httpsShowLoginWindow*
*Funcion que levanta la ventana de autenticacion propia de la aplicacion*
*Protocole: HTTPS
*@param *-*
*@throws *-*
*@return *-*/
	private function httpsShowLoginWindow($valid,$dir) {
      $locate = "<html><head></head><body onload=\"document.getElementById('form1').submit()\">";
      //$locate .= "<form id='form1' action='https://10.7.15.4/seguridad/IdentityProvider/login/index.php' method='POST'>";
	  $locate .= "<form id='form1' action='".$dir."login/index.php' method='POST'>";
      $locate .= "<input type='hidden' name='validuserpass' value='{$valid}'/>";
	  $locate .= "<input type='hidden' name='action_url' value='{$dir}'/>";
	  $locate .= "</form></body></html>";
	  echo $locate;
	}

/**
*createCertificate*
*Funcion que crea el certificado a traves de un servicio*
*@param *($usuario, $password)*
*@throws *-*
*@return *-*/
    private function createCertificate($usuario, $password) {
      	$RSA 			= new ZendExt_RSA_Facade();
	    $integrator 	= ZendExt_IoC::getInstance();
	    return $integrator->seguridad->AutenticarUsuario($usuario, $RSA->encrypt($password));
    }

/**
*check_athreq*
*Funcion que cheque la fecha del XML*
*@param *($athreq,$saml_object)*
*@throws *-*
*@return *true/false*/
    private function check_athreq($athreq,$saml_object) {
     $cont_check=0;
     if($saml_object->validSamlDateFormat($athreq[5]))
      $cont_check++;
     if($cont_check == 1)
      return true;
     return false;
    }

/**
*Logout*
*Funcion que gestiona el logout*
*@param *-*
*@throws *-*
*@return *-*/
    private function Logout() {
     unset($_SESSION['certificado']);
     unset($_REQUEST['certificado']);
	 unset($request['certificado']);
     unset($_SERVER['PHP_AUTH_USER']);
     unset($_SERVER['PHP_AUTH_PW']);
     $register = Zend_Registry::getInstance();
     $register->session->close = true;
     $this->clearOutSession();
     $flag=0;
     $_SESSION['log']=true;
    }

/**
*clearOutSession*
*Funcion que limpia la sesion del usuario*
*@param *-*
*@throws *-*
*@return *-*/
    private function clearOutSession() {
     if (isset($_SESSION['__ZF']) && is_array($_SESSION['__ZF'])) {
      foreach($_SESSION['__ZF'] as $key => $sesszf) {
       $namespace = $key;
       break;
      }
     $session = $_SESSION;
     foreach ($session as $key=>$sess) {
      if ($key != $namespace && $key != '__ZF')
       unset ($_SESSION[$key]);
     }
     unset($_SESSION['__ZF'][$namespace]);
    }
   }
}
