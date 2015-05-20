<?php

class ZendExt_WF_WFObject_Entidades_MessageType extends ZendExt_WF_WFObject_Base_Complex {

    private $From;
    private $To;
    private $FaultName;

    public function __construct($parent, $tagName = "MessageType") {
        parent::__construct($parent);
        $this->tagName = $tagName;
    }

    /*
     * Getters
     */
    public function getFrom() {
        return $this->From;
    }

    public function getFaultName() {
        return $this->FaultName;
    }

    public function getTo() {
        return $this->To;
    }

    public function getParameters() {
        return $this->get('Parameters');
    }

    /*
     * Setters
     */
    public function setTo($_to) {
        $this->To = $_to;
    }

    public function setFrom($_from) {
        $this->From = $_from;
    }

    public function setFaultName($_faultName) {
        $this->FaultName = $_faultName;
    }

    /*
     * Abstractions
     */
    public function clonar() {
        return;
    }

    public function fillStructure() {
        $option = array(new ZendExt_WF_WFObject_Entidades_ActualParameters($this),
            new ZendExt_WF_WFObject_Entidades_DataMappings($this)
        );
        $this->add(new ZendExt_WF_WFObject_Base_ComplexChoice("Parameters", $option, $this));
        return;
    }

   
    public function toArray() {
        $array = array(
            'Id'=>  $this->getId(),
            'Name'=> $this->getName(),
            'From'=>  $this->getFrom(),
            'To'=>  $this->getTo(),
            'FaultName'=>  $this->getFaultName(),
            'Parameters'=>  $this->getParameters()->getSelectedItem()->toArray()
        );
        return $array;
    }

}

?>
