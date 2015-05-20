<?php

class ZendExt_WF_WFObject_Entidades_WebServiceFaultCatch extends ZendExt_WF_WFObject_Base_Complex {

    private $FaultName;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'WebServiceFaultCatch';
    }

    public function getFaultName() {
        return $this->FaultName;
    }

    public function setFaultName($FaultName) {
        $this->FaultName = $FaultName;
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_MessageType($this,'Message'));
        $options = array(
            new ZendExt_WF_WFObject_Entidades_BlockActivity($this),
            new ZendExt_WF_WFObject_Entidades_TransitionRefs($this)
        );
        $this->add(new ZendExt_WF_WFObject_Base_ComplexChoice('Parameters', $options, $this));
    }

    public function getMessage() {
        return $this->get('Message');
    }

    public function getParameters() {
        return $this->get('Parameters');
    }

    public function toArray() {
        $array = array(
            'FaultName' => $this->getFaultName(),
            'Message' => $this->getMessage()->toArray(),
            'Parameters' => $this->getParameters()->toArray()
        );
        return $array;
    }

}

?>
