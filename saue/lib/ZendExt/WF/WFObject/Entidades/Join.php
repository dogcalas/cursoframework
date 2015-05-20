<?php

class ZendExt_WF_WFObject_Entidades_Join extends ZendExt_WF_WFObject_Base_Complex {

    private $Type;
    private $ExclusiveType;
    private $IncomingCondition;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Join';
    }

    /*
     * Getters
     */
    public function getType() {
        return $this->Type->getSelectedItem();
    }

    public function getExclusiveType() {
        return $this->ExclusiveType->getSelectedItem();
    }

    public function getIncomingCondition() {
        return $this->IncomingCondition;
    }

    /*
     * Setters
     */
    public function setType($_type) {
        $this->Type->selectItem($_type);
    }

    public function setExclusiveType($_exclusiveType) {
        $this->ExclusiveType->selectItem($_exclusiveType);
    }

    public function setIncomingCondition($_incomingCondition) {
        $this->IncomingCondition = $_incomingCondition;
    }
    
    
    public function toArray() {
        $array = array(
            'Type' => $this->getType(),
            'ExclusiveType' => $this->getExclusiveType(),
            'IncomingCondition' => $this->getIncomingCondition()
        );
        return $array;
    }

    /*
     * Abstractions
     */
    public function clonar() {
        return;
    }

    public function fillStructure() {
        $typeChoices = array('XOR','Exclusive','OR','Inclusive','Complex','AND','Parallel');
        $this->Type = new ZendExt_WF_WFObject_Base_SimpleChoice('Type', $typeChoices, NULL);

        $exclusiveTypeChoices = array('Data','Event');
        $this->ExclusiveType = new ZendExt_WF_WFObject_Base_SimpleChoice('ExclusiveType', $exclusiveTypeChoices, NULL);        
        
        $this->add(new ZendExt_WF_WFObject_Entidades_TransitionRefs($this));
    }
    
    public function getTransitionRefs() {
        return $this->get('TransitionRefs');
    }

}

?>
