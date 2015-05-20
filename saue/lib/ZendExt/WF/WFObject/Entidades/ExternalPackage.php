<?php

class ZendExt_WF_WFObject_Entidades_ExternalPackage extends ZendExt_WF_WFObject_Base_Complex {

    private $hRef;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'ExternalPackage';
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_ExtendedAttributes());
        return;
    }

    public function getExtendedAttributes() {
        return $this->get('ExtendedAttributes');
    }

    public function gethref() {
        return $this->hRef;
    }

    public function sethref($href) {
        $this->hRef = $href;
    }
    
    public function toArray() {
        $array = array(
            'href' => $this->gethref(),
            'Id' => $this->getId(),
            'Name' => $this->getName(),
            'ExtendedAttributes' => $this->getExtendedAttributes()->taArray()
        );
        return $array;
    }

}

?>
