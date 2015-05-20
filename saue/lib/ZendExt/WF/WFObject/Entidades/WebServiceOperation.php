<?php

class ZendExt_WF_WFObject_Entidades_WebServiceOperation extends ZendExt_WF_WFObject_Base_Complex {

    private $OperationName;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'WebServiceOperation';
    }

    public function getOperationName() {
        return $this->OperationName;
    }

    public function setOperationName($OperationName) {
        $this->OperationName = $OperationName;
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Partner($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Service($this));
    }

    public function getPartner() {
        return $this->get('Partner');
    }

    public function getService() {
        return $this->get('Service');
    }

    public function toArray() {
        $array = array(
            'Partner' => $this->getPartner()->toArray(),
            'Service' => $this->getService()->toArray()
        );
        return $array;
    }

}

?>
