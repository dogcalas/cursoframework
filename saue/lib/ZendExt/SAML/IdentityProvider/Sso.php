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
class ZendExt_SAML_IdentityProvider_Sso implements ZendExt_Aspect_ISinglenton  {
	private $register;

	private $config;

	private $session;

	private $uniquesession;



/**
	 * Constructor de la clase, es privado para impedir que pueda 
	 * ser instanciado y de esta forma garantizar que la instancia 
	 * sea un singlenton
	 * 
	 * @return void
	 */
	private function __construct() {
		$this->register = null;
		$this->config = null;
		$this->session = null;
		$this->uniquesession = false;
	}



/**
	 * Obtencion de la instancia de la clase, ya que esta no puede ser 
	 * instanciada directamente debido a que es un singlenton
	 * 
	 * @return ZendExt_IoC | null - Instancia de la clase
	 */
	static public function getInstance() {
		static $instance;
		if (!isset($instance))
			{
			 $instance = new self();
			}


		return $instance;
	}


	public function init($config) {
	$this->register = $this->initRegister();
	$this->config = $this->initConfig($config);
	$this->session = $this->initSession() ;
	}


/**
* Inicializa el registro unico de objetos, arreglos, ...
* 
* @return void
*/
    private function initRegister() {
     $register = new Zend_Registry(array(), ArrayObject::ARRAY_AS_PROPS);
     Zend_Registry::setInstance($register);
     return $register;
    }
	
/**
* Inicializa la configuracion de la aplicacion
* 
* @param array $config - arreglo con la configuracion de la aplicacion
* @return void
*/
    private function initConfig($config) {
     $configApp = new ZendExt_App_Config();
     $config = $configApp->configApp($config);
     Zend_Registry::getInstance()->config = $config;
     return $config;
    }
	
/**
* Inicializa la session del usuario
* 
* @return void
*/
    private function initSession() {
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
     return $session;
    }



    public function getRegister(){
    return $this->register;
   }

    public function getConfig(){
    return $this->config;
   }

    public function getSession(){
    return $this->session;
   }

   public function getUniqueSession(){
    return $this->uniquesession;
   }

   public function setUniqueSession($unique){
    return $this->uniquesession = $unique;
   }


}
?>
