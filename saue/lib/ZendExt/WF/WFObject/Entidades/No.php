<?php

Class ZendExt_WF_WFObject_Entidades_No extends ZendExt_WF_WFObject_Base_SimpleElement {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'No';
    }

    public function clonar() {
        return;
    }

}

?>
