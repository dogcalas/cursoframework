<?php

class ZendExt_WF_WFObject_Entidades_PropertyInput extends ZendExt_WF_WFObject_Base_Complex {

    private $PropertyId;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "PropertyInput";
    }

    public function getPropertyId() {
        return $this->PropertyId;
    }

    public function setPropertyId($_propertyId) {
        $this->PropertyId = $_propertyId;
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        return;
    }

    public function toArray() {
        $array = array(
            'PropertyId' => $this->getPropertyId()
        );
        return $array;
    }

}

?>
