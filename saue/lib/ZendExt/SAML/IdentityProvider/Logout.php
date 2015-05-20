<?php
/**
 * ZendExt_SAML_ServiceProvider_Client *
 * Clase que debe implementar cada aplicacion que vaya a utilizar el componente *
 *@ Daniel Enrique L�pez M�ndez * 
 *@Seguridad* (m�dulo)
 *@Autenticacion (subm�dulo)*
 *@copyright ERP Cuba*
 *@version 1.0*/
class ZendExt_SAML_IdentityProvider_Logout 
{
/**
*clearOutSession*
*Funcion que limpia la sesion del usuario*
*@param *-*
*@throws *-*
*@return *-*/
     function clearOutSession() {
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


/**
*Logout*
*Funcion que gestiona el logout*
*@param *-*
*@throws *-*
*@return *-*/
    function Logout($session) {
     $session->close = true;
     $this->clearOutSession();
    }

function parseData($stringdata)
	{
	$returnarray = explode('-',$stringdata);
	return $returnarray;
	}

public function init($request,$config){
  $rsa  = new ZendExt_RSA_Facade();
	if(isset($_COOKIE[md5('stringdatasign')]) && isset($_COOKIE[md5('encdata')]) && $_COOKIE[md5('seed')]){
	$stringdatasign = $_COOKIE[md5('stringdatasign')];
	$encdata = $_COOKIE[md5('encdata')];
	$seed = $_COOKIE[md5('seed')];
	$url_self = $this->generateUrlSelf();
        $dir = str_replace('log_out', 'html_log_out', $url_self);
	}
	else if(isset($request[md5('stringdatasign'.$request[md5('seed')])]) && isset($request[md5('stringdatasign'.$request[md5('seed')])]) && isset($request[md5('seed')])){
	$stringdatasign = $request[md5('stringdatasign'.$request[md5('seed')])];
	$encdata = $request[md5('encdata'.$request[md5('seed')])];
	$seed = $request[md5('seed')];
	$dir = $this->getServer();
	}
	else{
	die('<h1 style="color:#FF0000">O te demoraste deslogueandote o te estas queriendo hacer el hacker soqueteiro.</h1>');
	}
	$stringdata = $rsa->decrypt($encdata);
	$arraystringdata = $this->parseData($stringdata);

        
	if((string)$arraystringdata[3]=="si")
	if(!isset($_COOKIE[md5('seed')]) || !isset($_COOKIE[md5('encdata')]) || !isset($_COOKIE[md5('stringdatasign')]))
	 die('<h1 style="color:#FF0000">Borraste alguna cookie ?, seras hacker o soquete</h1>');
	//$rsa  = new ZendExt_RSA_Facade();	
	/*$stringdatasign = $_COOKIE[md5('stringdatasign')];
	$encdata = $_COOKIE[md5('encdata')];
	$seed = $_COOKIE[md5('seed')];
	/*setcookie(md5('encdata'),'');
	setcookie(md5('stringdatasign'),'');
	setcookie(md5('seed'),'');*/
	$prove = $rsa->proveMessage($encdata,$stringdatasign);

		if($prove)
			{
			$sso = ZendExt_SAML_IdentityProvider_Sso::getInstance();
			$sso->init($config);
			$session = $sso->getSession();
			unset($session->datasystem);
			$this->Logout($session);
			/*$stringdata = $rsa->decrypt($encdata);
			$arraystringdata = $this->parseData($stringdata);*/
			$this->redirectToResource($dir,$arraystringdata[6]);
			}
			else{
			die('<h1 style="color:#FF0000">Violacion de seguridad</h1>');
			}

	
 }

 /**
    *generateUrlSelf *
    *Funcion que genera el url del recurso *
    *@param * - *
    *@throws * - *
    *@return * $url_self */
	private function generateUrlSelf()
		{
			$array_protocol   = explode('/', $_SERVER['SERVER_PROTOCOL']);
		        $protocolo 	  = strtolower($array_protocol[0]);
		        $url_self	  = "{$protocolo}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";	
			return $url_self;
		}


/**
    *redirectToServiceProvider *
    *Funcion que redirecciona hacia el Proveedor de Servicios *
    *@param * ($serversp,$serveridp,$app,$flag) *
    *@throws * - *
    *@return * - */
	private function redirectToResource($dir,$relayStateURL)
                {
                       $locate = "<html><head></head><body onload=\"document.getElementById('form1').submit()\">";
	               $locate .= "<form id='form1' action='$dir' method='POST'>";
		       $locate .= "<input type='hidden' name='relayStateURL' value='{$relayStateURL}'/>";
		       $locate .= "</form></body></html>";
		       echo $locate;
                     
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
