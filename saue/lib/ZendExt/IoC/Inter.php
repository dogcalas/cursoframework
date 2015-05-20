<?php
/**
 * ZendExt_IoC_Inter
 * Integrador de servicios de negocio entre componentes (a lo interno de un modulo).
 * 
 * @author Manuel, Yoandry Morejon Borbon
 * @package ZendExt
 * @copyright UCID-ERP Cuba
 * @version 1.0-0
 */
class ZendExt_IoC_Inter extends ZendExt_IoC {
	
	/**
	 * Constructor de la clase, es privado para impedir que pueda 
	 * ser instanciado y de esta forma garantizar que la instancia 
	 * sea un singlenton
	 * 
	 * @return void
	 */
	private function __construct() {
		$this->idXMLIoC = 'iocinter';
		$aspectxml = ZendExt_FastResponse::getXML('aspect');
		$this->iocTrace = (string) $aspectxml->beginTraceIoC['active'];
		$this->iocExceptionTrace = (string) $aspectxml->failedTraceIoC['active'];
	}
	
	/**
	 * Obtencion de la instancia de la clase, ya que esta no puede ser 
	 * instanciada directamente debido a que es un singlenton
	 * 
	 * @return ZendExt_IoC_Inter | null - Instancia de la clase
	 */
	static public function getInstance() {
		static $instance;
		if (!isset($instance))
			$instance = new self();
		return $instance;
	}
	
	
}
