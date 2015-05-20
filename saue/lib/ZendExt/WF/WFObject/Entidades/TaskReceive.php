<?php

class ZendExt_WF_WFObject_Entidades_TaskReceive extends ZendExt_WF_WFObject_Base_Complex {

    private $Instantiate;
    private $Implementation;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TaskReceive';
    }

    /*
     * Getters
     */

    public function getInstantiate() {
        return $this->Instantiate;
    }

    public function getImplementation() {
        return $this->Implementation->getSelectedItem();
    }

    public function getMessage() {
        return $this->get('Message');
    }

    public function getWebServiceOperation() {
        return $this->get('WebServiceOperation');
    }

    /*
     * Setters
     */

    public function setInstantiate($Instantiate) {
        $this->Instantiate = $Instantiate;
    }

    public function setImplementation($_implementation) {
        $this->Implementation->selectItem($_implementation);
    }

    /*
     * Abstractions
     */

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_MessageType("Message", $this));
        $this->add(new ZendExt_WF_WFObject_Entidades_WebServiceOperation($this));

        $implementationChoices = array('WebService', 'Other', 'Unspecified');
        $this->Implementation = new ZendExt_WF_WFObject_Base_SimpleChoice('Implementation', $implementationChoices, NULL);
        return;
    }

    public function toArray() {
        $array = array(
            'Instantiate' => $this->getInstantiate(),
            'Implementation' => $this->getImplementation(),
            'MessageType' => $this->getMessage()->toArray(),
            'WebServiceOperation' => $this->getWebServiceOperation()->toArray()
        );
        return $array;
    }

}

?>
