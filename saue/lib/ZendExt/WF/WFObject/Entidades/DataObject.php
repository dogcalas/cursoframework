<?php

class ZendExt_WF_WFObject_Entidades_DataObject extends ZendExt_WF_WFObject_Base_Complex {

    private $State;
    private $RequiredForStart;
    private $ProducedAtCompletion;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'DataObject';
    }

    /*
     * Getters
     */
    public function getState() {
        return $this->state;
    }

    public function getRequiredForStart() {
        return $this->RequiredForStart;
    }

    public function getProducedAtCompletion() {
        return $this->ProducedAtCompletion;
    }

    /*
     * Setters
     */
    public function setState($_state) {
        $this->State = $_state;
    }

    public function setRequiredForStart($_requiredForStart) {
        $this->RequiredForStart = $_requiredForStart;
    }

    public function setProducedAtCompletion($_produceAtCompletion) {
        $this->ProducedAtCompletion = $_produceAtCompletion;
    }

    /*
     * Abstractions
     */
    public function clonar() {
        return;
    }

    public function fillStructure() {
        /*
         * Considerar revision
         */
        
        return;
    }
    
    
    public function toArray() {
        $array = array(
            'Id' => $this->getId(),
            'Name' => $this->getName(),
            'State' => $this->getState(),
            'RequiredForStart' => $this->getRequiredForStart(),
            'ProducedAtCompletion' => $this->getProducedAtCompletion()
        );
        return $array;
    }

}

?>
