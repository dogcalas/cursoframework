<?php

class ZendExt_WF_WFObject_Entidades_TaskService extends ZendExt_WF_WFObject_Base_Complex {

    private $Implementation;
    private $servicioIOC;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TaskService';
    }

    /*
     * Setters
     */

    //Re-definir esto...
    public function setServicioIOC($_servicioIoC) {
        $this->servicioIOC = $_servicioIoC;
    }

    public function setImplementation($_implementation) {
        $this->Implementation->selectItem($_implementation);
    }

    /*
     * Getters
     */

    public function getId() {
        return $this->parent->getId();
    }

    public function getServicioIOC() {
        return $this->servicioIOC;
    }

    public function getMessageIn() {
        return $this->get('MessageIn');
    }

    public function getMessageOut() {
        return $this->get('MessageOut');
    }

    public function getWebServiceOperation() {
        return $this->get('WebServiceOperation');
    }

    public function getWebServiceFaultCatch() {
        return $this->get('WebServiceFaultCatch');
    }

    public function getImplementation() {
        return $this->Implementation->getSelectedItem();
    }

    /*
     * Overriden methods
     */

    public function toName() {
        return 'ServiceTask';
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_MessageIn($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_MessageOut($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_WebServiceOperation($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_WebServiceFaultCatch($this));

        $implementationChoices = array('IOC_Service', 'WebService', 'Other', 'Unspecified');
        $this->Implementation = new ZendExt_WF_WFObject_Base_SimpleChoice('Implementation', $implementationChoices, NULL);
        return;
    }

    public function toArray() {
        $array = array(
            'Implementation' => $this->getImplementation(),
            'MessageIn' => $this->getMessageIn()->toArray(),
            'MessageOut' => $this->getMessageOut()->toArray(),
            'WebServiceOperation' => $this->getWebServiceOperation()->toArray(),
            'WebServiceFaultCatch' => $this->getWebServiceFaultCatch()->toArray()
        );
        return $array;
    }

}

?>
