<?php

class ZendExt_WF_WFObject_Entidades_CountryKey extends ZendExt_WF_WFObject_Base_SimpleElement {

    //put your code here    
    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "CountryKey";
    }

    public function clonar() {
        return;
    }

    public function setCountryKey($CountryKey) {
        $this->setValue($CountryKey);
    }

    public function getCountryKey() {
        return $this->getValue();
    }

    public function toArray() {
        $array = array(
            'CountryKey' => $this->getCountryKey()
        );
        return $array;
    }

}

?>
