<?php

class ZendExt_WF_WFObject_Entidades_HomeClass extends ZendExt_WF_WFObject_Base_SimpleElement {

    private $string;

    public function __construct() {
        parent::__construct();
        $this->tagName = 'HomeClass';
    }

    public function setstring($st) {
        $this->string = $st;
    }

    public function getstring() {
        return $this->string();
    }

    public function fillStructure() {

        return;
    }

    public function clonar() {
        return;
    }

}

?>
