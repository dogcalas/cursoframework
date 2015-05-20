<?php
/**
 * ZendExt_SAML_ServiceProvider_Client *
 * Clase que debe implementar cada aplicacion que vaya a utilizar el componente *
 *@ Daniel Enrique L�pez M�ndez * 
 *@Seguridad* (m�dulo)
 *@Autenticacion (subm�dulo)*
 *@copyright ERP Cuba*
 *@version 1.0*/
class ZendExt_SAML_ServiceProvider_Client 
{


    /**
    *init *
    *Funcion principal de la clase *
    *@param * ($request, $flag) *
    *@throws * - *
    *@return * - */
	public function init($flag,$seed)
            {
            
		$rsa = new ZendExt_RSA_Facade();
                $url_self = $this->generateUrlSelf();
               
		$arraydata = $this-> loadXml($flag,$seed,$url_self);
                if($arraydata[3]!="si" && $arraydata[3]!="no")
                    die('<h1 style="color:#FF0000">Error de configuracion.Revise la configuracion de su xml.</h1>');
		$sp = $arraydata[1];
		if((integer)$flag==1)
			{
				if((string)$arraydata[3]=="si")
					{
						$sp = $sp.'API/';
						$arraydata[1] = $sp;
					}
			}
		else if((integer)$flag==2)
			{
				
				$sp = $sp.'SINGLELOGOUT/';
				if($arraydata[3]=="no")
				$sp = $sp.'log_out.php';
			}
		$stringdata = $this->parseData($arraydata);
                $encdata = $rsa->encrypt($stringdata);
                $stringdatasign = $rsa->signMessage($encdata);
                $this->redirectToServiceProvider($stringdatasign,$encdata,$sp,$url_self,$seed,$arraydata[7],$arraydata[8]);
            }


	
    /**
    *redirectToServiceProvider *
    *Funcion que redirecciona hacia el Proveedor de Servicios *
    *@param * ($serversp,$serveridp,$app,$flag) *
    *@throws * - *
    *@return * - */
	private function redirectToServiceProvider($stringdatasign,$encdata,$sp,$url_self,$seed,$acs,$logout)
                {
                    if($this->isXMLHttpRequest())
                    {
                        $this->setStatusCode(401);
                        echo "{success:false,msg:'No puede realizar ninguna accion, su sesion ha expirado.                  					Se realizara el proceso de autenticacion nuevamente.'}";
                    }
                     else
                      {
		       
		       $md5secure = md5('secure'.$seed);
                       $md5stringdatasign = md5('stringdatasign'.$seed);
                       $md5resource = md5('resource'.$seed);
		       $md5encdata = md5('encdata'.$seed);
                       $md5seed = md5('seed');
                       $md5acs = md5('acs'.$seed);
		       $md5logout = md5('logout'.$seed);
                       $locate = "<html><head></head><body onload=\"document.getElementById('form1').submit()\">";
	               $locate .= "<form id='form1' action='$sp' method='POST'>";
		       $locate .= "<input type='hidden' name='{$md5acs}' value='{$acs}'/>";
		       $locate .= "<input type='hidden' name='{$md5stringdatasign}' value='{$stringdatasign}'/>";
		       $locate .= "<input type='hidden' name='{$md5resource}' value='{$url_self}'/>";
		       $locate .= "<input type='hidden' name='{$md5encdata}' value='{$encdata}'/>";
		       $locate .= "<input type='hidden' name='{$md5seed}' value='{$seed}'/>";
		       $locate .= "</form></body></html>";
		       echo $locate;
                     }
	        }


    /**
    *isXMLHttpRequest *
    *Funcion que determina si la peticion es ajax *
    *@param * - *
    *@throws * - *
    *@return *true/false*/
	public function isXMLHttpRequest()
		{
			return  $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest";
		}
	

    /**
    *setStatusCode *
    *Funcion que envia un header *
    *@param * $code *
    *@throws *-*
    *@return *true/false*/
	private function setStatusCode($code) 
		{
			header("HTTP/1.1 $code");
		}

	
    /**
    *loadXml *
    *Funcion que carga los datos del xml de configuracion *
    *@param *-*
    *@throws *-*
    *@return *array*/
	private function loadXml($flag,$seed,$url_self)
                {
			 $register = Zend_Registry::getInstance ();
			 $dirModulesConfig = $register->config->xml->saml;
			 $xml = new SimpleXMLElement($dirModulesConfig,null,true);
			 $resultarray = array();
			 $resultarray[0] = (string)$xml->idp[0];
			 $resultarray[1] = (string)$xml->sp[0];
			 $resultarray[2] = (string)$xml->app[0];
			 $resultarray[3] = (string)$xml->api[0];
			 $resultarray[4] = $flag;
			 $resultarray[5] = $seed;
			 $resultarray[6] = $url_self;
			 $resultarray[7] = urlencode((string)$xml->acs[0]);

			 return $resultarray;
		}


    /**
    *generateUrlSelf *
    *Funcion que genera el url del recurso *
    *@param * - *
    *@throws * - *
    *@return * $url_self */
	private function generateUrlSelf()
		{
		        $url_self	  = (string)$this->getServer().(string)$_SERVER['REQUEST_URI'];
			return $url_self;
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
					$stringdata = $stringdata.$arraydata[$i];
					if($i!=count($arraydata)-1)
						$stringdata = $stringdata.'-';
				}
		
			return 	$stringdata;
		}


   /**
    *getServer *
    *Funcion auxiliar para obtener la direccion del recurso solicitado *
    *@param * - *
    *@throws * - *
    *@return * $dir */
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
