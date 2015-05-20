<?php

/**
 * ZendExt_Traza_Publisher_Action
 * 
 * Publicador de las trazas de acción.
 * 
 * @author René R. Bauta.
 * @copyright ERP-Cuba
 * @package Trace
 * @subpackage Publisher
 * @version 1.0.0
 */
class ZendExt_Trace_Container_Proceso extends ZendExt_Trace_Container_Log {

    /**
     * el id del proceso en la tabla dat_proceso
     *
     * @var Int
     */
    private $_idproceso;

    /**
     * el nombre del proceso en la tabla dat_proceso
     *
     * @var Int
     */
    private $_proceso;
    private $_versionproceso;                         //el version del proceso en la tabla dat_proceso
    private $_idinstancia;                             // id de la tabla de la intancia de proceso
    private $_actividad;
    private $_estadoactividad;    //siempre completado
    private $_idactividad;     //es el id de del evento
    private $_ontologia;     //semanticmodelreference
    private $_fechaevento;
    private $_pl;

    /**
     * Constructor
     *
     * @param String $pidobjeto
     * @param String $pinstancia
     * @param Int $pIdTipo
     * @param String $pUser
     * @param String $pFecha
     * @param String $pHora
     */
    function __construct($pl, $fechaevento, $idproceso, $proceso, $versionproceso, $idinstancia, $actividad, $estadoactividad, $idactividad, $ontologia, $ip_host, $pUser, $pIdRol, $pIdDominio, $pIdCommonStructure, $pRol, $pDominio, $pEstructura) {

        parent :: __construct($ip_host, $pIdRol, $pIdDominio, $pUser, $pIdCommonStructure, $pRol, $pDominio, $pEstructura);
        $this->_pl = $pl;
        $this->_idproceso = $idproceso;
        $this->_proceso = $proceso;
        $this->_versionproceso = $versionproceso;
        $this->_idinstancia = $idinstancia;
        $this->_actividad = $actividad;
        $this->_estadoactividad = $estadoactividad;
        $this->_idactividad = $idactividad;
        $this->_ontologia = $ontologia;
        $this->_fechaevento = $fechaevento;
        parent :: setTraceType(6);
    }

    public function getFechaEvento() {
        return $this->_fechaevento;
    }

    public function getPl() {
        return $this->_pl;
    }

    public function getIdProceso() {
        return $this->_idproceso;
    }

    public function getProceso() {
        return $this->_proceso;
    }

    public function getVersionProceso() {
        return $this->_versionproceso;
    }

    public function getIdInstancia() {
        return $this->_idinstancia;
    }

    public function getActividad() {
        return $this->_actividad;
    }

    public function getEstadoActividad() {
        return $this->_estadoactividad;
    }

    public function getIdActividad() {
        return $this->_idactividad;
    }

    public function getOntologia() {
        return $this->_ontologia;
    }

}

?>
