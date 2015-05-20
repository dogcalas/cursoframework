<?php
/**
 * ZendExt_GlobalConcept_FechaContable
 * Clase para obtener los datos del perfil por defecto del concepto "Configuracion"
 *
 * @author Elianys Hurtado Sola
 * @package ZendExt
 * @subpackage GlobalConcept
 * @copyright ERP Cuba 
 * @version 1.5.0
 */
class ZendExt_GlobalConcept_FechaContable implements ZendExt_GlobalConcept_IConcept {
	
	public $idfecha = 0;
	
	public $fecha = '';
	
	public $idestructurasubsist = 0;
	
	public $incremento = 0;
	
	public $fechasist = '';
	
	public $precedencia = 0;
	
	public function __construct() {
		$register = Zend_Registry::getInstance();
		$global = ZendExt_GlobalConcept::getInstance();
		$idestructura = $global->Estructura->idestructura;
		$idsubsistema = $register->id_subsistema_pacsoft_instanciado;
		if (!$idsubsistema)
			$idsubsistema = $global->Subsistema->idsubsistema;
		if ($idestructura && $idsubsistema) {
			$integrator = ZendExt_IoC::getInstance ();
			$fecha = $integrator->parametros->FechaContableSubsistemaObj ($idestructura, $idsubsistema);
			if ($fecha->idfecha) {
				$this->idfecha = $fecha->idfecha;
				$this->fecha = $fecha->fecha;
				$this->incremento = $fecha->incremento;
				$this->idestructurasubsist = $fecha->idestructurasubsist;
				$this->fechasist = $fecha->fechasist;
				$this->precedencia = $fecha->precedencia;
			}
		}
	}
	
	public function ObtenerConcepto() {
		return $this;
	}
}
