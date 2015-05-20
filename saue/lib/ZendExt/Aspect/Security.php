<?php
class ZendExt_Aspect_Security implements ZendExt_Aspect_ISinglenton {

	/**
	 * Constructor de la clase, es privado para impedir que pueda 
	 * ser instanciado y de esta forma garantizar que la instancia 
	 * sea un singlenton
	 * 
	 * @return void
	 */
	private function __construct() {
	
	}
	
	/**
	 * Obtencion de la instancia de la clase, ya que esta no puede ser 
	 * instanciada directamente debido a que es un singlenton
	 * 
	 * @return ZendExt_Aspect_Security - instancia de la clase
	 */
	static public function getInstance() {
		static $instance;
		if (!isset($instance))
			$instance = new self();
		//Se guarda en el singelton seguridad.
		$register = Zend_Registry::getInstance();
		$register->seguridadInstance = $instance;
		return $instance;
	}



public function authenticateUser () {
		 $spcObj = new ZendExt_SAML_ServiceProvider_Client();
		 $flag = 0;
		 $session = Zend_Registry::getInstance()->session;
		 //echo '<pre>';print_r($_REQUEST);die; 
		if (!$session->certificado) {		    
           	if (!$_REQUEST[md5('certificado'.$session->seed)])
			{
				$flag = 1;
                                $seed = rand(0,10000000);
				$session->seed = $seed;
				//$spcObj->init($_REQUEST,$flag,$enc_seed,$key_seed);
                                $spcObj->init($flag,$seed);
			}
			else
			{
			        $rsa = new ZendExt_RSA_Facade();
				$encSeed = $rsa->encrypt($session->seed);
				$proveseed = $rsa->proveMessage($encSeed,$_REQUEST[md5('keySeed'.$session->seed)]);
				//echo '<pre>';print_r($proveseed);die;
				if($proveseed)
				{
				 if((string)$_REQUEST[md5('flag'.$session->seed)]=='2')
				  {
					unset($_REQUEST);

				  }
				  else
				  {
				   if($encSeed==$_REQUEST[md5('encSeed'.$session->seed)])
				    {
					$session->certificado = $rsa->decrypt($_REQUEST[md5('certificado'.$session->seed)]);
					$config = Zend_Registry::get('config');
					$session->setExpirationSeconds($config->security->ttl, 'certificado');
					 unset($_REQUEST);
					}
					else
				     die('<h1 style="color:#FF0000">Se ha intentado acceder a la pagina de forma incorrecta o no tiene priveligios de acceso</h1>');
				  }
				}
				else
				 die('<h1 style="color:#FF0000">Se ha intentado acceder a la pagina de forma incorrecta o no tiene priveligios de acceso</h1>');
			}
		}

		if ($session->close)
		{
				$flag = 2;
				$this->clearOutSession();
				$session->unsetAll();
                                $seed = rand(0,10000000);
				$session->seed = $seed;
				$spcObj->init($flag,$seed);
		}

	}
	
	
	
	
	
	protected function clearOutSession () {
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
	 * getCertificate
	 * Obtiene el certificado del usuario
	 * 
	 * @param $user - nombre o alias del usuario que se registro
	 * @param $password - clave de acceso del usuario
	 * @return string - devuelve el certificado asignado al usuario
	 * @ignore return - retorna 1, pendiente a utilizacion
	 */
	protected function getCertificate ($user, $password) {
		return 1;
	}
	
	/**
	 * getUserData
	 * Obtiene los datos del usuario autenticado a traves de un servicio de
	 * negocio que brinda el componente portal
	 * 
	 * @return void
	 */
	public function getUserData() {
		$register = Zend_Registry::getInstance();
		$session = $register->session;
		if (!$session->perfil)
		{
			$integrator = ZendExt_IoC::getInstance();
			$usuario = $integrator->portal->BuscarUsuarioByAlias($session->usuario);
			$usuarioArr = $usuario->toArray();
			$session->perfil = $usuarioArr[0];
		}
	}
	
	/**
	 * hasAccess
	 * Verifica que un usuario tiene acceso a una funcionalidad y a una
	 * accion.
	 * 
	 * @return boolean - retorna true si el usuario autenticado tiene acceso falso sino.
	 * @ignore - Ignorar el resultado pq siempre se devuelve true.
	 */
	public function hasAccess() {
		return true;
	}
	
	/**
	 * Muestra la ventana de autenticacion por HTTP
	 * 
	 * @return void
	 */
	protected function showLogonWindow ()
	{
		//Se muetra la ventana de autenticacion. 
		header('WWW-Authenticate: Basic realm="SIGIS"');
		//Si cancela la autenticacion se muestra un mensaje de acceso denegado
		header('HTTP/1.0 401 Unauthorized');
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) //Si la peticion es por ajax
			die(json_encode (array('codMsg'=>3,'mensaje'=>'<b> Acceso denegado </b>'))); //Se imprime la excepcion en codigo json
		else
			die('<h1 style="color:#FF0000">Acceso denegado</h1>'); 
	}

	public function verifyAccessToFunctionality () {
		
	}
	/**
	 * authenticateUser
	 * Realiza el proceso de autenticacion de un usuario a traves del SIGES
	 * 
	 * @return void
	 */
	public function authenticateUserInstalador () {
		$session = Zend_Registry::getInstance()->session;
		//Si no existe el certificado, ni se autentico por http y no se ha cerrado la session
			$rsaObj = new ZendExt_RSA();
			$keys = $rsaObj->generate_keys ('9990454949', '9990450271', 0);
			//Se guarda en la session el usuario y el password
			$session->usuario = 'instalacion';
			$session->pass = $rsaObj->encrypt ('instalacion', $keys[1], $keys[0], 5);
	}
}
