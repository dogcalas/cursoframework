<?php

class ZendExt_WF_WFObject_Entidades_StartEvent extends ZendExt_WF_WFObject_Base_Complex {

    private $Trigger;
    private $Implementation;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'StartEvent';
    }

    public function getTrigger() {
        return $this->Trigger->getSelectedItem();
    }

    public function getImplementation() {
        return $this->Implementation;
    }

    public function getResult() {
        return $this->get('Result');
    }

    public function setTrigger($_trigger) {
        $this->Trigger->selectItem($_trigger);
    }

    public function setImplementation($_implementation) {
        $this->Implementation->selectItem($_implementation);
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $optionsTrigger = array('None','Message', 'Timer', 'Conditional', 'Signal', 'Multiple');
        $this->Trigger = new ZendExt_WF_WFObject_Base_SimpleChoice('Trigger', $optionsTrigger, $this);

        $implementationChoices = array('WebService','Other', 'Unspecified');
        $this->Implementation = new ZendExt_WF_WFObject_Base_SimpleChoice('Implementation', $implementationChoices, $this);

        $options = array
            (
            new ZendExt_WF_WFObject_Entidades_TriggerResultMessage($this),
            new ZendExt_WF_WFObject_Entidades_TriggerTimer($this),
            new ZendExt_WF_WFObject_Entidades_TriggerConditional($this),
            new ZendExt_WF_WFObject_Entidades_TriggerResultSignal($this),
            new ZendExt_WF_WFObject_Entidades_TriggerMultiple($this)
        );
        $this->add(new ZendExt_WF_WFObject_Base_ComplexChoice('Result', $options, $this));
        return;
    }

    public function getTriggerType() {
        return $this->get('TriggerType');
    }

    public function toArray() {
        $array = array(
            'Trigger' => $this->getTrigger(),
            'Implementation' => $this->getImplementation(),
            'Result' => $this->getResult()
        );
        return $array;
    }
    
    public function toName() {
        return 'Start Event';
    }

}

?>
