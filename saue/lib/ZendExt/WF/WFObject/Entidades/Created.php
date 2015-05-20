<?php

class ZendExt_WF_WFObject_Entidades_Created extends ZendExt_WF_WFObject_Base_SimpleElement {

    //put your code here

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Created";
    }

    public function clonar() {
        $c = new ZendExt_WF_WFObject_Simple_Created();
        $c->setValue($this->value);
        return $c;
    }

    public function setCreated($Created) {
        $this->setValue($Created);
    }

    public function getCreated() {
        return $this->getValue();
    }

    public function toArray() {
        $array = array(
            'Created' => $this->getCreated()
        );
        return $array;
    }

}

?>
