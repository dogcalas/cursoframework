<?php
/**
 * ZendExt_Trace_Container_Log
 *
 * Clase base de las trazas
 * 
 * @author Omar Antonio D�az Pe�a.
 * @author René R. Bauta
 * @copyright ERP-Cuba. 
 * @version 1.0.0
 */
	
class ZendExt_Trace_Container_Log {
	/**
	 * Fecha de la traza
	 *
	 * @var String
	 */
	protected $_date;
	
	/**
	 * Hora
	 *
	 * @var String
	 */
	protected $_time;
	
	/**
	 * Identificador del ip desde donde se realizó la petición
	 *
	 * @var String.
	 */
	protected $_ip_host;
	
	/**
	 * Identificador de tipo
	 * 
	 * @var Int
	 */
	protected $_id_type;
	
	/**
	 * Usuario
	 *
	 * @var String
	 */
	protected $_user;
	
	/**
	 * Identificador de estructura común
	 *  
	 * @var Int
	 */
	protected $_id_common_structure;
	
	/**
	 * Identificador de rol
	 * 
	 * @var Int
	 */
	protected $_idrol;
	
	/**
	 * Identificador de dominio
	 * 
	 * @var Int
	 */
	protected $_iddominio;
	
	/**
	 * denominación del rol
	 * 
	 * @var String
	 */
	protected $_rol;
	
		/**
	 * denominación del dominio
	 * 
	 * @var String
	 */
	protected $_dominio;
	
	/**
	 * denominación de la estructura
	 * 
	 * @var String
	 */
	protected $_estructuracomun;

    /**
	 * Constructor
	 *
	 * @param Int $pIdTraza
	 * @param Int $pIdTipo
	 * @param String $pFecha
	 * @param String $pHora
	 * @param Int $pUser
	 * @param Int $pIdrol
	 * @param Int $pIddominio
	 */
	function __construct ($pIp_host,  $pId_rol, $pId_dominio, $pUser, $pIdCommonStructure, $pRol, $pDominio, $pEstructura, $pFecha = null, $pHora = null){
		$this->_date = ($pFecha == null) ? date ('d/m/Y') : $pFecha;
		$this->_time = ($pHora == null) ? date ('H:i:s') : $pHora;
		$this->_id_common_structure = ($pIdCommonStructure != null) ? $pIdCommonStructure: -1;

		$this->_user = $pUser;
		$this->_ip_host = $pIp_host;	
		$this->_idrol = $pId_rol;
		$this->_iddominio = $pId_dominio;
		$this->_rol = $pRol;
		$this->_dominio = $pDominio;
		$this->_estructuracomun = $pEstructura;	
	}
	
	
	/**
	 * Identificador de estructura común.
	 *
	 * @return Int
	 */
	function getCommonStructure() {
		return $this->_id_common_structure;
	}
	
	/**
	 * Obtiene la fecha
	 *
	 * @return String
	 */
	function getDate () {
		return $this->_date;	
	}
	
	/**
	 * Obtiene la hora
	 *
	 * @return String
	 */
	function getTime () {
		return $this->_time;	
	}
	
	/**
	 * Identificador de la traza
	 *
	 * @return String
	 */
	function getIp_Host () {
		return $this->_ip_host;	
	}
	
	/**
	 * Identificador del tipo de la traza
	 *
	 * @return Int
	 */
	function getIdType () {
		return $this->_id_type;	
	}
	
	/**
	 * Devolver el usuario.
	 *
	 * @return String.
	 */
	function getUser () {
		return $this->_user;
	}	
	/**
	 * Fija el tipo de traza
	 *
	 * @param Int $pType
	 */
	function setTraceType ($pType) {
		$this->_id_type = $pType;		
	}	
	/**
	 * Fija el rol
	 */
	function getRol () {
		return $this->_idrol;		
	}
	/**
	 * Fija el id del dominio
	 *
	 */
	function getDominio () {
		return $this->_iddominio;		
	}
		/**
	 * Fija la denominacion del rol
	 */
	function getDenomRol () {
		return $this->_rol;		
	}
	/**
	 * Fija la denominacion del dominio
	 */
	function getDenomDominio () {
		return $this->_dominio;		
	}
		/**
	 * Fija la denominacion de la estructura
	 */
	function getDenomEstructura () {
		return $this->_estructuracomun;		
	}
}
?>
