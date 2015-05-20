<?php

class ZendExt_GlobalConcept_Ejercicio implements ZendExt_GlobalConcept_IConcept {
	
	public $ejercicioactual = 0;
	
	public function __construct() {
		$register = Zend_Registry::getInstance();
		if ($register->cierreactual)
			$this->ejercicioactual = $register->cierreactual->idejercicioactual;
		else {
			$global = ZendExt_GlobalConcept::getInstance();
			$idestructura = $global->Estructura->idestructura;
			$idsubsistema = $register->id_subsistema_pacsoft_instanciado;
			if (!$idsubsistema)
				$idsubsistema = $global->Subsistema->idsubsistema;
			if ($idestructura && $idsubsistema) {
				$integrator = ZendExt_IoC::getInstance();
				$cierreactual = $integrator->parametros->CierreActualSubsistema($idestructura, $idsubsistema);
				if ($cierreactual->idperiodoactual) {
					$register->cierreactual = $cierreactual;
					$this->ejercicioactual = $cierreactual->idejercicioactual;
				}
			}
		}
	}
	
	public function ObtenerConcepto() {
		return $this;
	}
}

?>
