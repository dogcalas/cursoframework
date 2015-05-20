<?php

class ZendExt_WF_WFObject_Entidades_ValidTo extends ZendExt_WF_WFObject_Base_SimpleElement {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'ValidTo';
    }

    public function clonar() {
        return;
    }

    public function getValidTo() {
        return $this->getValue();
    }

    public function setValidTo($_validTo) {
        $this->setValue($_validTo);
    }
    
    public function toArray() {
        $array = array(
            'ValidTo' => $this->getValidTo()
        );
        return $array;
    }

}

?>
