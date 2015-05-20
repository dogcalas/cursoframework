<?php
/**
* ZendExt_GlobalConcept_Estructura
*Clase para obtener el concepto "Estructura"
*
* @author Elianys Hurtado Sola
* @package ZendExt
* @subpackage GlobalConcept
* @copyright ERP Cuba 
* @version 1.5.0
*/

class ZendExt_GlobalConcept_Estructura implements ZendExt_GlobalConcept_IConcept {
	
	public $idestructura = 0;
	
	public $denominacion = '';
	
	public $abreviatura = '';
	
	public $idespecialidad = 0;
	
	public $idorgano = 0;

	public function __construct() {
		$register = Zend_Registry::getInstance();
		if (isset($register->session->entidad->idestructura)) {
			$this->idestructura = $register->session->entidad->idestructura;
			$this->denominacion = $register->session->entidad->denominacion;
			$this->abreviatura = $register->session->entidad->abreviatura;
			$this->idespecialidad = $register->session->entidad->idespecialidad;
			$this->idorgano = $register->session->entidad->idorgano;
		}
		else {
			$cacheObj = ZendExt_Cache::getInstance();
			$cacheData = $cacheObj->load(session_id());
			if (isset($cacheData->entidad)) {
				$register->session->entidad = $cacheData->entidad;
				$this->idestructura = $cacheData->entidad->idestructura;
				$this->denominacion = $cacheData->entidad->denominacion;
				$this->abreviatura = $cacheData->entidad->abreviatura;
				$this->idespecialidad = $cacheData->entidad->idespecialidad;
				$this->idorgano = $cacheData->entidad->idorgano;
			}
		}
	}
	
	public function ObtenerConcepto() {
		return $this;
	}
	
}
