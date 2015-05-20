<?php
/**
 * ZendExt_Traza_Publisher_Action
 * 
 * Publicador de las trazas de acción.
 * 
 * @author Omar Antonio Díaz Peña.
 * @copyright ERP-Cuba
 * @package Trace
 * @subpackage Publisher
 * @version 1.0.0
 */
class ZendExt_Trace_Container_Action extends ZendExt_Trace_Container_Log {
	/**
	 * Referencia
	 *
	 * @var String
	 */
	protected $_reference;
	
	/**
	 * Controlador
	 *
	 * @var String
	 */
	protected $_controller;
	
	/**
	 * Acción
	 *
	 * @var String
	 */
	protected $_action;
	
	/**
	 * ¿Fallo?.
	 *
	 * @var bool
	 */
	protected $_fault;
	
	/**
	 * ¿Inicio?
	 * 
	 * @var bool
	 */
	protected $_begin;
	
	/**
	 * Constructor
	 *
	 * @param String $pReference
	 * @param String $pController
	 * @param String $pAction
	 * @param Float $pExecTime
	 * @param Int $pIdTipo
	 * @param String $pUser
	 * @param String $pFecha
	 * @param String $pHora
	 */
	function __construct ($pFault, $pBegin, $pReference, $pController, $pAction,$pIpHost, $pIdRol, $pIdDominio, $pUser, $pIdCommonStructure, $pRol, $pDominio, $pEstructura, $pFecha = null, $pHora = null) {
		
		parent :: __construct ($pIpHost, $pIdRol, $pIdDominio, $pUser, $pIdCommonStructure, $pRol, $pDominio, $pEstructura, $pFecha, $pHora);
							   	
		parent :: setTraceType(1);
		$this->_reference = $pReference;
		$this->_controller = $pController;
		$this->_action = $pAction;
		$this->_begin = (int) $pBegin;
		$this->_fault = (int) $pFault;
	}
	
	/**
	 * Referencia
	 *
	 * @return String
	 */
	function getReference () {
		return $this->_reference;	
	}
	
	/**
	 * Controlador
	 *
	 * @return String
	 */
	function getController () {
		return $this->_controller;	
	}
	
	/**
	 * Acción
	 *
	 * @return String
	 */
	function getAction () {
		return $this->_action;	
	}
	
	/**
	 * Retorna si la acción inicio
	 *
	 * @return bool
	 */
	function getBegin () {
		return $this->_begin;
	}
	
	/**
	 * Retorna si la excepcion fallo.
	 *
	 * @return bool
	 */
	function getFault () {
		return $this->_fault;		
	}
}
?>
