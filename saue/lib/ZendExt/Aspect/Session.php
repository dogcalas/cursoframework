<?php
/**
 * ZendExt_Aspect_Session
 * Inicializa la session
 * 
 * @author: Yoandry Morejon Borbon
 * @copyright UCID-ERP Cuba
 * @package: ZendExt
 * @subpackage ZendExt_Aspect_Session
 * @version 1.0-0
 */
class ZendExt_Aspect_Session implements ZendExt_Aspect_ISinglenton {
	
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
	 * @return ZendExt_Aspect_Session - instancia de la clase
	 */
	static public function getInstance() {
		static $instance;
		if (!isset($instance))
			$instance = new self();
		return $instance;
	}
	
	public function initSession() {
		//Se inicia la sesion.
		session_save_path($this->config->session_save_path);
		Zend_Session::start(array('save_path' => $this->config->session_save_path));
		$session = new Zend_Session_Namespace ('UCID_Cedrux_UCI');
		
		//Se busca en la cache si la session se inicializo
		$cacheObj = ZendExt_Cache::getInstance();
		$cacheData = $cacheObj->load(session_id());
		
		//Se protege el id de la session contra ataques.
		if (!isset($cacheData->initialized))
		{
		    Zend_Session::regenerateId();
		    $sessionStd = new stdClass();
		    $sessionStd->initialized = true;
		    $cacheObj->save($sessionStd, session_id());
		}
		
		//Se guarda en el registro la session.
		$register = Zend_Registry::getInstance();
		$register->session = $session;
	}
}
