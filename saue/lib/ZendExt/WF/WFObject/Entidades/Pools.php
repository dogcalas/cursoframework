<?php

class ZendExt_WF_WFObject_Entidades_Pools extends ZendExt_WF_WFObject_Base_Collections {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Pools';
    }

    public function clonar() {
        return;
    }

    public function createObject() {
        return new ZendExt_WF_WFObject_Entidades_Pool($this);
    }

}

?>
