<?php

class ZendExt_WF_WFObject_Entidades_Location extends ZendExt_WF_WFObject_Base_SimpleElement {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Location';
    }

    public function setLocation($_location) {
        /*
         * anyURI
         */
        $this->setValue($_location);
    }

    public function getLocation() {
        return $this->getValue();
    }

    public function fillStructure() {

        return;
    }

    public function clonar() {
        return;
    }

}

?>
