<?php

class ZendExt_WF_WFObject_Entidades_Role extends ZendExt_WF_WFObject_Base_Complex {

    private $portType;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Role';
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        return;
    }

    public function getName() {
        return $this->portType;
    }

    public function setportType($_portType) {
        $this->portType = $_portType;
    }

}

?>
