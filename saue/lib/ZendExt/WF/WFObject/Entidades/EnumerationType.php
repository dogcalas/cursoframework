<?php

class ZendExt_WF_WFObject_Entidades_EnumerationType extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "EnumerationType";
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_EnumerationValue($this));
        return;
    }

    public function getEnumerationValue() {
        return $this->get('EnumerationValue');
    }

    public function toArray() {
        $array = array(
            'EnumerationValue' => $this->getEnumerationValue()->toArray()
        );
        return $array;
    }

}

?>
