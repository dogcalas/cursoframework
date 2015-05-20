<?php

class ZendExt_WF_WFObject_Entidades_PartnerRole extends ZendExt_WF_WFObject_Base_Complex {

    private $RoleName;
    private $ServiceName;
    private $PortName;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'PartnerRole';
    }

    /*
     * Setters
     */
    public function setRoleName($_roleName) {
        $this->RoleName = $_roleName;
    }

    public function setServiceName($_serviceName) {
        $this->ServiceName = $_serviceName;
    }

    public function setPortName($_portName) {
        $this->PortName = $_portName;
    }

    /*
     * Getters
     */
    public function getEndPoint() {
        return $this->get('EndPoint');
    }

    public function getRoleName() {
        return $this->RoleName;
    }

    public function getServiceName() {
        return $this->ServiceName;
    }

    public function getPortName() {
        return $this->PortName;
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
