<?php

class ZendExt_WF_WFObject_Entidades_Limit extends ZendExt_WF_WFObject_Base_SimpleElement {

    private $string;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Limit";
    }

    public function clonar() {
        return;
    }

    public function getstring() {
        return $this->string;
    }

    public function setstring($st) {
        $this->string = $st;
    }
    
    public function toArray() {
        $array = array(
            'Limit' => $this->getstring()
        );
        return $array;
    }

}

?>
