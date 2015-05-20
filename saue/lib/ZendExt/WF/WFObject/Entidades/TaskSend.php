<?php

class ZendExt_WF_WFObject_Entidades_TaskSend extends ZendExt_WF_WFObject_Entidades_Task {

    private $Implementation;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TaskSend';
    }

    public function getImplementation() {
        return $this->Implementation->getSelectedItem();
    }

    public function setImplementation($_implementation) {
        $this->Implementation->selectItem($_implementation);
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_MessageType("Message", $this));
        $this->add(new ZendExt_WF_WFObject_Entidades_WebServiceOperation($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_WebServiceFaultCatch($this));

        $implementationChoices = array('WebService', 'Other', 'Unspecified');
        $this->Implementation = new ZendExt_WF_WFObject_Base_SimpleChoice('Implementation', $implementationChoices, NULL);
    }

    public function getMessage() {
        return $this->get('Message');
    }

    public function getWebServiceOperation() {
        return $this->get('WebServiceOperation');
    }

    public function getWebServiceFaultCatch() {
        return $this->get('WebServiceFaultCatch');
    }

    public function toArray() {
        $array = array(
            'Implementation' => $this->getImplementation(),
            'MessageType' => $this->getMessage()->toArray(),
            'WebServiceOperation' => $this->getWebServiceOperation()->toArray(),
            'WebServiceFaultCatch' => $this->getWebServiceFaultCatch()->toArray()
        );
        return $array;
    }

}

?>
