<?php

class ZendExt_WF_WFObject_Entidades_TransitionRef extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TransitionRef';
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        return;
    }

    public function toArray() {
        $array = array(
            'Id' => $this->getId(),
            'Name' => $this->getName()
        );
        return $array;
    }

}

?>