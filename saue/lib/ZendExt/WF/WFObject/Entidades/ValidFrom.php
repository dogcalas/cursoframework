<?php

class ZendExt_WF_WFObject_Entidades_ValidFrom extends ZendExt_WF_WFObject_Base_SimpleElement {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'ValidFrom';
    }

    public function clonar() {
        return;
    }

    public function getValidFrom() {
        return $this->getValue();
    }

    public function setValidFrom($_validFrom) {
        $this->setValue($_validFrom);
    }

    public function toArray() {
        $array = array(
            'ValidFrom' => $this->getValidFrom()
        );
        return $array;
    }

}

?>
