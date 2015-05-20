<?php

class ZendExt_WF_WFObject_Entidades_Documentation extends ZendExt_WF_WFObject_Base_SimpleElement {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Documentation';
    }

    public function fillStructure() {
        return;
    }

    public function clonar() {
        return;
    }

    public function setDocumentation($_documentation) {
        $this->setValue($_documentation);
    }

    public function getDocumentation() {
        return $this->getValue();
    }
    
    public function toArray() {
        $array = array(
            'Documentation' => $this->getDocumentation()
        );
        return $array;
    }

}

?>
