<?php 
	/**
	 * ZendExt_Trace_Publisher_Exception
	 * 
	 * Clase para la publicación de las trazas de excepción.
	 * 
	 * @author Omar Antonio Díaz Peña.
	 * @copyright ERP-Cuba.
	 * @package Trace
	 * @subpackage Publisher
	 * @version 1.0.0
	 */
	class ZendExt_Trace_Container_Exception extends ZendExt_Trace_Container_Log {
		/**
		 * Código
		 *
		 * @var String
		 */
		private $_code;	

		/**
		 * Tipo de la excepción
		 *
		 * @var String
		 */
		private $_type;
		
		/**
		 * Idioma
		 *
		 * @var String
		 */
		private $_lang;
		
		/**
		 * Mensaje
		 *
		 * @var String
		 */
		private $_msg;
		
		/**
		 * Descripcion
		 *
		 * @var String
		 */
		private $_description;
		
		private $_log;
		
		/**
		 * Enter description here...
		 *
		 * @param String $pCode
		 * @param String $pType
		 * @param String $pLang
		 * @param String $pMsg
		 * @param String $pDescription
		 * @param Int $pIdTraceCategory
		 * @param String $pUser
		 * @param Int $pIdCommonStructure
		 * @param String $pFecha
		 * @param String $pHora
		 */
		function __construct ($pCode, $pType, $pLang, $pMsg, $pDescription, $pLog, $pIpHost, $pIdRol, $pIdDominio, $pUser, $pIdCommonStructure, $pRol, $pDominio, $pEstructura, $pFecha = null, $pHora = null) {
			parent :: __construct ($pIpHost, $pIdRol, $pIdDominio, $pUser, $pIdCommonStructure, $pRol, $pDominio, $pEstructura, $pFecha, $pHora);
			parent :: setTraceType(2);
			
			$this->_code = $pCode;
			$this->_type = $pType;
			$this->_description = $pDescription;
			$this->_msg = $pMsg;
			$this->_lang = $pLang;
			$this->_log = $pLog;
		}	
		
		function getLog () {
			return $this->_log;
		}
	
		/**
		 * Retorna el código de la excepción
		 *
		 * @return String
		 */
		function getCode () {
			return $this->_code;
		}
		
		/**
		 * Retorna el mensaje de la excepción
		 *
		 * @return String
		 */
		function getMsg () {
			return $this->_msg;	
		}
		
		/**
		 * Retorna el tipo de la excepcion
		 *
		 * @return String
		 */
		function getType () {
			return $this->_type;	
		}
		
		/**
		 * Retorna el idioma d la excpecion.
		 *
		 * @return String
		 */
		function getLang () { 
			return $this->_lang;	
		}
		
		/**
		 * Retorna la descipcion de la excepcion
		 *
		 * @return unknown
		 */
		function getDescription () {
			return $this->_description;	
		}
}
?>
