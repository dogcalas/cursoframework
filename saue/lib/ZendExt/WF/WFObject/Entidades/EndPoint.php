<?php

class ZendExt_WF_WFObject_Entidades_EndPoint extends ZendExt_WF_WFObject_Base_Complex {

    private $EndPointType;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "EndPoint";
    }

    public function getEndPointType() {
        return $this->EndPointType->getSelectedItem();
    }

    public function setEndPointType($_endPoint) {
        $this->EndPointType->selectItem($_endPoint);
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_ExternalReference($this));
        $endPointChoices = array('WSDL', 'Service');
        $this->EndPointType = new ZendExt_WF_WFObject_Base_SimpleChoice('EndPointType', $endPointChoices, NULL);
        return;
    }

    public function getExternalReference() {
        return $this->get('ExternalReference');
    }

    public function toArray() {
        $array = array(
            'EndPointType' => $this->getEndPointType(),
            'ExternalReference' => $this->getExternalReference()->toArray()
        );
        return $array;
    }

}

?>
