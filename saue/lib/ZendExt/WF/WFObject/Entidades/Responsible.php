<?php

class ZendExt_WF_WFObject_Entidades_Responsible extends ZendExt_WF_WFObject_Base_SimpleElement {

    //put your code here    
    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Responsible";
    }

    public function clonar() {
        return;
    }

    public function toArray() {
        $array = array(
            'Responsible' => $this->getResponsible()
        );
        return $array;
	}
    public function setResponsible($_responsible) {
        $this->setValue($_responsible);

    }


    public function getResponsible() {
        return $this->getValue();
    }
}

?>
