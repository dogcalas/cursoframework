<?php

class ZendExt_WF_WFObject_Entidades_Version extends ZendExt_WF_WFObject_Base_SimpleElement {

    //put your code here    
    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Version";
    }

    public function clonar() {
        return;
    }

    public function setVersion($_version) {
        $this->setValue($_version);
    }

    public function getVersion() {
        return $this->getValue();
    }

    public function toArray() {
        $array = array(
            'Version' => $this->getVersion()
        );
        return $array;
    }

}

?>
