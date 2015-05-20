<?php

class ZendExt_WF_WFObject_Entidades_Xslt extends ZendExt_WF_WFObject_Base_SimpleElement {

    private $location;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Xslt';
    }

    public function setlocation($_location) {
        $this->location = $_location;
    }

    public function getlocation() {
        return $this->location;
    }

    public function fillStructure() {
        /*
         *  <xsd:sequence>
         *      <xsd:any namespace="##other" processContents="lax" minOccurs="0" maxOccurs="unbounded"/>
         *  </xsd:sequence>
         */
        return;
    }

    public function clonar() {
        return;
    }

}

?>
