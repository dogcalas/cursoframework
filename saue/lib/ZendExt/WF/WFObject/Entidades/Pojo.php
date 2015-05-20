<?php

class ZendExt_WF_WFObject_Entidades_Pojo extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Pojo";
    }

    /*
     * Getters
     */
    public function getClass() {
        return $this->get('Class');
    }

    public function getMethod() {
        return $this->get('Method');
    }

    /*
     * Abstractions
     */
    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Class());
        $this->add(new ZendExt_WF_WFObject_Entidades_Method());
        return;
    }

}

?>
