<?php

class ZendExt_WF_WFObject_Entidades_Precision extends ZendExt_WF_WFObject_Base_SimpleElement {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Precision";
    }

    public function clonar() {
        return;
    }

    public function getPrecision() {
        return $this->getValue();
    }

    public function setPrecision($_precision) {
        $this->setValue($_precision);
    }
    
    
    public function toArray() {
        $array = array(
            'Precision' => $this->getPrecision()
        );
        return $array;
    }

}

?>
