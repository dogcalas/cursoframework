<?php

class ZendExt_WF_WFObject_Entidades_Description extends ZendExt_WF_WFObject_Base_SimpleElement {

    //private $string;
    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Description';
    }

    public function setDescription($description) {
        $this->setValue($description);
    }

    public function getDescription() {
        return $this->getValue();
    }
    /*public function clonar() {
        return;
    }*/
    public function toArray() {
        $array = array(
            'Description' => $this->getDescription()
        );
        return $array;
    }

}

?>
