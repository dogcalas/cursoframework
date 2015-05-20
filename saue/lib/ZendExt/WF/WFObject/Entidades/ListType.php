<?php

class ZendExt_WF_WFObject_Entidades_ListType extends ZendExt_WF_WFObject_Base_SimpleElement {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "ListType";
    }

    public function clonar() {
        return;
    }

}

?>
