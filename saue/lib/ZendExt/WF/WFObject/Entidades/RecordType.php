<?php

class ZendExt_WF_WFObject_Entidades_RecordType extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "RecordType";
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        //ver si se le agrega la clase menber que contiene a la clase Data types
        return;
    }

}

?>
