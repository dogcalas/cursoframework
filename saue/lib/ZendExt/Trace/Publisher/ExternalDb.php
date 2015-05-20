<?php
	/**
	 * Clase para la implementación de los publicadores de las trazas en bases de datos, para aplicaciones que no usen los aspectos de Sauxe
	 *   
	 * @author Damián Pérez Alfonso
	 * @copyright Sauxe
	 * @package Traces
	 * @subpackage Publisher
	 * @version 1.0.0 
	 */

	class ZendExt_Trace_Publisher_ExternalDb extends ZendExt_Trace_Publisher_Db{
		/**
		 * Método de persistencia de los logs en la BD.
		 *
		 * @param ZendExt_Trace_Container_Log $pPublisher
		 */
		function save ($pContainer) {
                    parent::save($pContainer);
                    $this->_conn->commit();                                        
                }
                
	}
?>
