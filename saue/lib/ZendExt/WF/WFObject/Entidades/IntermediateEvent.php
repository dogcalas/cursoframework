<?php

class ZendExt_WF_WFObject_Entidades_IntermediateEvent extends ZendExt_WF_WFObject_Base_Complex {

    private $Trigger;
    private $Implementation;
    private $Target;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'IntermediateEvent';
        $this->parent = $parent;

        $choicesTrigger = array(
            'Message', 'Timer', 'Conditional', 'Signal', 'Multiple'
        );
        $this->Trigger = new ZendExt_WF_WFObject_Base_SimpleChoice('Trigger', $choicesTrigger, $this);

        $choicesImplementation = array(
            'WebService', 'Other', 'Unspecified'
        );
        $this->Implementation = new ZendExt_WF_WFObject_Base_SimpleChoice('Implementation', $choicesImplementation, $this);
    }

    public function getTrigger() {
        return $this->Trigger->getSelectedItem();
    }

    public function getImplementation() {
        return $this->Implementation->getSelectedItem();
    }

    public function getTarget() {
        return $this->Target;
    }

    public function setTrigger($_trigger) {
        $this->Trigger->select($_trigger);

        if ($this->Trigger->getSelectedItem() === 'Message') {
            $this->Implementation->selectItem('WebService');
        }

        $items = $this->get('Result')->getChoices();
        $i = 0;
        foreach ($items as $value) {
            $pos = stripos($value->getTagName(), $_trigger);
            if ($pos != FALSE) {
                $this->get('Result')->select($i);
                break;
            }
            else
                $i++;
        }
    }

    public function setImplementation($Implementation) {
        if ($this->Implementation->getSelectedItem() != $Implementation) {
            $this->Implementation->select($Implementation);
        }
    }

    public function setTarget($Target) {
        $this->Target = $Target;
    }

    public function getResult() {
        return $this->get('Result');
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Base_ComplexChoice('Result', array(
                    new ZendExt_WF_WFObject_Entidades_TriggerResultMessage($this),
                    new ZendExt_WF_WFObject_Entidades_TriggerTimer($this),
                    new ZendExt_WF_WFObject_Entidades_ResultError($this),
                    new ZendExt_WF_WFObject_Entidades_TriggerResultCompensation($this),
                    new ZendExt_WF_WFObject_Entidades_TriggerConditional($this),
                    new ZendExt_WF_WFObject_Entidades_TriggerResultLink($this),
                    new ZendExt_WF_WFObject_Entidades_TriggerResultCancel($this),
                    new ZendExt_WF_WFObject_Entidades_TriggerResultSignal($this),
                    new ZendExt_WF_WFObject_Entidades_TriggerIntermediateMultiple($this)
                ), $this));
    }

    public function toArray() {
        $array = array(
            'Trigger' => $this->getTarget(),
            'Implementation' => $this->getImplementation(),
            'Target' => $this->getTarget(),
            'Result' => $this->getResult()->getSelectedItem()->toArray()
        );
        return $array;
    }

}

?>
