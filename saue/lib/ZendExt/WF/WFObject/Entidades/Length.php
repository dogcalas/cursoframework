<?php

class ZendExt_WF_WFObject_Entidades_Length extends ZendExt_WF_WFObject_Base_SimpleElement {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Length";
    }

    /*public function clonar() {
        return;
    }*/

    public function getLength() {
        return $this->getValue();
    }

    public function setLength($_length) {
        $this->setValue($_length);
    }
    
    
    public function toArray() {
        $array = array(
            'Length' => $this->getLength()
        );
        return $array;
    }

}

?>
