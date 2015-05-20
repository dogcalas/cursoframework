<?php
/**
 * ZendExt_Aspect_Security_Sgis
 * Encapsula los aspectos de seguridad, realiza a traves del SGIS los
 * procesos de autenticacion, autorizacion, auditoria y administracion
 * de perfiles
 * 
 * @package ZendExt
 * @copyright UCID-ERP Cuba
 * @author Omar Antonio Diaz Peña	 
 * @version 1.0-0
 */
class ZendExt_Aspect_Security_Sgis extends ZendExt_Aspect_Security {

	/**
	 * Integrador utilizado para solicitar los servicios que brinda el SIGES
	 * a traves de inversion de control
	 * 
	 * @var ZendExt_IoC
	 */
	protected $integrator;
	
	/**
	 * Constructor de la clase, es privado para impedir que pueda 
	 * ser instanciado y de esta forma garantizar que la instancia 
	 * sea un singlenton
	 * 
	 * @return void
	 */
	private function __construct() {
		//Se obtiene la instancia del integrador
		$this->integrator = ZendExt_IoC::getInstance();
	}
	
	/**
	 * Obtencion de la instancia de la clase, ya que esta no puede ser 
	 * instanciada directamente debido a que es un singlenton
	 * 
	 * @return ZendExt_Aspect_Security_Sgis - instancia de la clase
	 */
	static public function getInstance() {
		static $instance;
		if (!isset($instance))
			$instance = new self();
		return $instance;
	}
	
	/** 
	 * getCertificate
	 * Obtiene el certificado del usuario
	 * 
	 * @param $pUser - nombre o alias del usuario que se registro
	 * @param $pPass - clave de acceso del usuario
	 * @param $pIdSistema - identificador del sistema
	 * @return string - devuelve el certificado asignado al usuario
	 */
	protected function getCertificate ($user, $password) {
		$integrator = ZendExt_IoC::getInstance();
		return $integrator->seguridad->AutenticarUsuario($user, $password);
	}
		/** 
	 * getCertificate
	 * Obtiene el certificado del usuario para el instalador
	 * 
	 * @param $pUser - nombre o alias del usuario que se registro
	 * @param $pPass - clave de acceso del usuario
	 * @param $pIdSistema - identificador del sistema
	 * @return string - devuelve el certificado asignado al usuario
	 */
	protected function getCertificateInstalador ($user, $password) {
		$integrator = ZendExt_IoC::getInstance();
		return true;
	}
	/**
	 * getUserData
	 * Obtiene los datos del usuario autenticado utilizando un servicio
	 * que brinda el SGIS a traves del IoC
	 * 
	 * @return void
	 */
	public function getUserData() {
		//Se obtiene la session a partir del registro
		$session = Zend_Registry::getInstance()->session;
		if (!isset($session->perfil)) {
			//Se solicita un servicio al SGIS para cargar los datos del usuario
			$integrator = ZendExt_IoC::getInstance();
        	$usuarioArr = $integrator->seguridad->CargarPerfil($session->certificado);
        	//Se guarda en una dimension de la session los datos del perfil del usuario                
        	$session->perfil = $usuarioArr;
		}
	}

	/**
	 * hasAcces
	 * Verifica que un usuario tiene acceso a una funcionalidad y a una
	 * accion a traves de un servicio web que brinda el SGIS.
	 * 
	 * @return boolean - retorna true si el usuario autenticado tiene acceso falso sino.
	 * @ignore - Ignorar el resultado pq siempre se devuelve true.
	 */
	public function verifyAccessEntity () {
		$session = Zend_Registry::get('session');
		$integrator = ZendExt_IoC::getInstance();
		return $integrator->seguridad->verifyAccessEntity ($session->certificado, $session->idestructura);
	}

	public function getDomain ($idestructuracomun) {
		$certificate = Zend_Registry::get('session')->certificado;
		return $this->integrator->seguridad->CargarDominio($certificate, $idestructuracomun);
	}
        
        public function verifyAccessToFunctionality(){
                $request_url = trim($_SERVER['PHP_SELF'],'/');
		$request_url_parts = explode("/",$request_url);
                $session = $_SESSION['UCID_Cedrux_UCI'];
                
                //obteniendo el nombre de la acción limpio tal y como lo mapeará Zend contra el controlador
		$frontController=Zend_Controller_Front::getInstance();
                $cleanAction=str_replace("Action","",$frontController->getDispatcher()->formatActionName(end($request_url_parts)));
                array_pop($request_url_parts);
		array_push($request_url_parts, $cleanAction);
                $request_url=implode("/", $request_url_parts);                
                
		//if($request_url_parts[0] == "portal" || $request_url_parts[0] == "seguridad" ||$request_url_parts[0] == "metadatos" ||$request_url_parts[0] == "traza" ){//esta condicion esta temporal
		if($request_url_parts[0] == "portal"|| $request_url_parts[1] == "doctrine_generator" ){//esta es la condicion que se debe usar
			return true;
		}
                
        //FIXME: MODO DE PRUEBAS (eliminar en producción)
        if($request_url_parts[0] == "data_entry.php" || $request_url_parts[0] == "model_generator.php" ||$request_url_parts[0] == "report_generator.php" ||$request_url_parts[0] == "records_classifiers.php" ){		
			return true;
		}
        
        if (!isset($session['valid_urls'])) {
            $integrator = ZendExt_IoC::getInstance();
			$funcionalidades = $integrator->seguridad->ObtenerTodasFucionalidades($session['certificado'], $session['entidad']->idestructura);
			$funcionalidades["portal/index.php/portal/portal"] = array();
			$valid_urls = array();
			foreach($funcionalidades as $url=>$acciones){
				$valid_urls[] = $url;
				$url_parts = explode("/",$url);
				foreach($acciones as $accion){
					if(strstr($accion, "/")){
						$valid_urls[] = $accion;
					}
					else{
						$url_parts[count($url_parts)-1] = $accion;
						$valid_urls[] = implode("/",$url_parts);
					}
				}
			}
			$session['valid_urls'] = $valid_urls;
            $_SESSION['UCID_Cedrux_UCI'] = $session;
        }
		foreach($session['valid_urls'] as $url){
			if(strcasecmp($request_url , trim($url,"/"))==0)
				return true;
		}                
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) //Si la peticion es por ajax
			die(json_encode (array('codMsg'=>3,'mensaje'=>'<b> Acceso denegado </b>'))); 
		else
			die('<h1 style="color:#FF0000">Acceso denegado</h1>');
	}
        /**
        * Elimina el fichero asociado a la sesión que se pasa por parametro. Si no se especifica $session_id se toma la sesion actual
        * 
        * @return boolean - Si la eliminación fue exitosa
        */
        /*public function deleteSessionFile($session_id=null){
            if(!isset($session_id)){
                $session_id=session_id();
            } 
            $registro = Zend_Registry::getInstance();
            $filename=$registro->config->session_save_path;
            $filename=$filename.'sess_'.$session_id;
            if(chmod($filename, 0777))
                return unlink($filename);
            return false;
        }*/
		
		public function deleteSessionFile($session_id=null){
            if(!isset($session_id)){
                $session_id=session_id();
            } 
            $registro = Zend_Registry::getInstance();
            $filename=$registro->config->session_save_path;
			$filename=ini_get('upload_tmp_dir').'/sess_'.$session_id;
                return unlink($filename);
            return false;
        }
}
