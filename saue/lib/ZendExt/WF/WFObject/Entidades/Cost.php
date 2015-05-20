<?php

class ZendExt_WF_WFObject_Entidades_Cost extends ZendExt_WF_WFObject_Base_SimpleElement {

    private $string;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Cost";
    }

    public function clonar() {
        return;
    }

    public function getCost() {
        return $this->getValue();
    }

    public function setCost($_cost) {
        $this->setValue($_cost);
    }
    
    public function toArray() {
        $array = array(
            'Cost' => $this->getCost()
        );
        return $array;
    }

}

?>
