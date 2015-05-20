<?php

class ZendExt_WF_WFObject_Entidades_MyRole extends ZendExt_WF_WFObject_Base_SimpleElement {

    private $RoleName;
    private $PortType;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'MyRole';
    }

    public function getRoleName() {
        return $this->RoleName;
    }

    public function setRoleName($_roleName) {
        $this->RoleName = $_roleName;
    }

    public function getportType() {
        return $this->PortType;
    }

    public function setPortType($_portType) {
        $this->portType = $_portType;
    }

    public function clonar() {
        return;
    }

}

?>
