<?php

class ZendExt_WF_WFObject_Entidades_EnumerationValue extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        /* <sequence>
         * <xsd:any namespace="##other" processContents="lax" minOccurs="0" maxOccurs="unbounded"/>
         */
        return;
    }

    public function toArray() {
        $array = array(
            'Name' => $this->getName()
        );
        return $array;
    }

}

?>
