	<?php 
	/**
	 * ZendExt_Trace_Publisher_Data
	 * 
	 * Clase para la gestión de las trazas de datos
	 * 
	 * @author Omar Antonio Díaz Peña.
	 * @package Trace.
	 * @subpackage Publisher.
	 * @copyright ERP-Cuba.
	 * @version 1.0.0.
	 */
	class ZendExt_Trace_Container_Data extends ZendExt_Trace_Container_Log {
		/**
		 * Esquema
		 *
		 * @var String
		 */
		 private $_schema;
		 
		 /**
		  * Tabla
		  *
		  * @var String
		  */
		 private $_table;
		 
		 /**
		  * Acción
		  *
		  * @var String
		  */
		 private $_id_operacion;
                 private $_id_objeto;
                 private $_accion;


                 /**
		  * Constructor
		  *
		  * @param String $pSchema
		  * @param String $pTable
		  * @param String $pAction
		  * @param Int $pIdTipo
		  * @param String $pUser
		  * @param Int $pIdCommonStructure
		  * @param String $pFecha
		  * @param String $pHora
		  */
		 function __construct($pSchema, $pTable, $pIdObjeto, $pIdOperacion, $pAction, $ip_host, $pUser, $pIdRol, $pIdDominio, $pIdCommonStructure, $pRol, $pDominio, $pEstructura) {
		 	parent :: __construct ($ip_host, $pUser, $pIdRol, $pIdDominio, $pIdCommonStructure, $pRol, $pDominio, $pEstructura);
		 	
		 	$this->_schema = $pSchema;
		 	$this->_table = $pTable;
                        $this->_id_objeto = $pIdObjeto;
		 	$this->_id_operacion = $pIdOperacion;
                        $this->_accion = $pAction;
		 	parent::setTraceType(6);
		 }
		 
		 /**
		  * Esquema
		  *
		  * @return String
		  */
		 function getSchema() {
		 	return $this->_schema;
		 }

                 function getAction () {
                     return $this->_accion;
                 }

                 /**
		  * Accion
		  *
		  * @return String
		  */
		 function getIdOperacion() {
		 	return $this->_id_operacion;
		 }

         function getIdObjeto () {
			if(is_array($this->_id_objeto)){
				return implode('_',$this->_id_objeto);
			}
            return $this->_id_objeto;
         }

                 function setIdOperacion($pIdOperacion) {
		 	$this->_id_operacion = $pIdOperacion;
		 }
		 
		 /**
		  * Tabla
		  *
		  * @return String
		  */
		 function getTable() {
		 	return $this->_table;
		 } 
	}
?>
