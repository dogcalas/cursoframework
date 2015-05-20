<?php

class ZendExt_WF_WFObject_Entidades_FormLayout extends ZendExt_WF_WFObject_Base_SimpleElement {

    private $anyType;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'FormLayout';
    }

    public function setanyType($st) {
        $this->anyType = $st;
    }

    public function getanyType() {
        return $this->anyType();
    }

    public function fillStructure() {

        return;
    }

    public function clonar() {
        return;
    }

}

?>
