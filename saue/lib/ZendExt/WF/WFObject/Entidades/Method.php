<?php

class ZendExt_WF_WFObject_Entidades_Method extends ZendExt_WF_WFObject_Base_SimpleElement {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Method';
    }

    public function setMethod($_method) {
        $this->setValue($_method);
    }

    public function getMethod() {
        return $this->getValue();
    }

    public function clonar() {
        return;
    }

}

?>
