<?php

class ZendExt_WF_WFObject_Entidades_Service extends ZendExt_WF_WFObject_Base_Complex {

    private $ServiceName;
    private $PortName;

    public function __construct($parent) {

        parent::__construct($parent);
        $this->tagName = "Service";
    }

    /*
     * Getters
     */
    public function getServiceName() {
        return $this->ServiceName;
    }

    public function getPortName() {
        return $this->PortName;
    }

    public function getEndPoint() {
        return $this->get('EndPoint');
    }

    /*
     * Setters
     */

    public function setPortName($_portName) {
        $this->PortName = $_portName;
    }

    public function setServiceName($_service) {
        $this->ServiceName = $_service;
    }

    /*
     * Abstractions
     */

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_EndPoint($this));
        return;
    }

}

?>
