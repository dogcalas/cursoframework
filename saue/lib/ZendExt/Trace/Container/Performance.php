<?php
	class ZendExt_Trace_Container_Performance extends ZendExt_Trace_Container_Action  {
		private $_exec_time;
        private $_memory;
		
		function __construct($pExecTime, $pMemory, $pFault, $pBegin, $pReference, $pController, $pAction, $pIpHost, $pUser, $pIdRol, $pIdDominio,  $pIdCommonStructure, $pRol, $pDominio, $pEstructura, $pFecha = null, $pHora = null) {
			parent :: __construct($pFault, $pBegin, $pReference, $pController, $pAction, $pIpHost, $pUser, $pIdRol, $pIdDominio, $pIdCommonStructure, $pRol, $pDominio, $pEstructura, $pFecha, $pHora);
			parent :: setTraceType(3);
			$this->_exec_time = $pExecTime;
            $this->_memory = $pMemory;
		}
		
		function getExecTime () {
			return $this->_exec_time;
		}

        function getMemory () {
            return $this->_memory;
        }
	}
?>
