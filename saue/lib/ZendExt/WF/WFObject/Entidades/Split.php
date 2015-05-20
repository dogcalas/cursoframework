<?php

class ZendExt_WF_WFObject_Entidades_Split extends ZendExt_WF_WFObject_Base_Complex {

    private $Type;
    private $ExclusiveType;
    private $OutgoingCondition;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Split';
    }

    /*
     * Getters
     */
    public function getTransitionRefs() {
        return $this->get('TransitionRefs');
    }

    public function getType() {
        return $this->Type->getSelectedItem();
    }

    public function getExclusiveType() {
        return $this->ExclusiveType->getSelectedItem();
    }

    public function getOutgoingCondition() {
        return $this->OutgoingCondition;
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

    public function setOutgoingCondition($_outgoingCondition) {
        $this->OutgoingCondition = $_outgoingCondition;
    }
    
    
    public function toArray() {
        $array = array(
            'Type'=>  $this->getType(),
            'TransitionRefs'=>  $this->getTransitionRefs()->toarray(),
            
                
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
        $this->add(new ZendExt_WF_WFObject_Entidades_TransitionRefs($this));

        $exclusiveTypeChoices = array('Data', 'Event');
        $this->ExclusiveType = new ZendExt_WF_WFObject_Base_SimpleChoice('Type', $exclusiveTypeChoices, NULL);

        $typeChoices = array('XOR','Exclusive','OR','Inclusive','Complex','AND','Parallel');
        $this->Type = new ZendExt_WF_WFObject_Base_SimpleChoice('Type', $typeChoices, NULL);
    }

}

?>