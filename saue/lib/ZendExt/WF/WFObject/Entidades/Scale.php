<?php

class ZendExt_WF_WFObject_Entidades_Scale extends ZendExt_WF_WFObject_Base_SimpleElement {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Scale";
    }

    public function clonar() {
        return;
    }

    public function getScale() {
        return $this->getValue();
    }

    public function setScale($_scale) {
        $this->setValue($_scale);
    }
    
    public function toArray() {
        $array = array(
            'Scale' => $this->getScale()
        );
        return $array;
    }

}

?>
