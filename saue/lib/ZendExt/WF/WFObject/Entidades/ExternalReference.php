<?php

class ZendExt_WF_WFObject_Entidades_ExternalReference extends ZendExt_WF_WFObject_Base_Complex {

    private $xref;
    private $location;
    private $namespace;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'ExternalReference';
    }

    public function getxref() {
        return $this->xref;
    }

    public function getlocation() {
        return $this->location;
    }

    public function getnamespace() {
        return $this->namespace;
    }

    public function setnamespace($_nameSpace) {
        $this->namespace = $_nameSpace;
    }

    public function setlocation($_location) {
        $this->location = $_location;
    }

    public function setxref($_xref) {
        $this->xref = $_xref;
    }

    /*
     * Abstractions
     */

    public function clonar() {
        return;
    }

    public function fillStructure() {
        return;
    }
    
    public function toArray() {
        $array = array(
            'xref' => $this->getxref(),
            'location' => $this->getlocation(),
            'namespace' => $this->getnamespace()
        );
        return $array;
    }

}

?>
