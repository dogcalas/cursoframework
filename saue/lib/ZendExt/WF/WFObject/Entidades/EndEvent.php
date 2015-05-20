<?php

class ZendExt_WF_WFObject_Entidades_EndEvent extends ZendExt_WF_WFObject_Base_Complex {

    private $Trigger;
    private $Implementation;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'EndEvent';

        $choicesTrigger = array(
            'Message', 'Cancel', 'Compensation', 'Terminate', 'Error', 'Signal', 'Multiple'
        );
        $this->Trigger = new ZendExt_WF_WFObject_Base_SimpleChoice('Trigger', $choicesTrigger, $this);

        $choicesImplementation = array(
            'WebService', 'Other', 'Unspecified'
        );
        $this->Implementation = new ZendExt_WF_WFObject_Base_SimpleChoice('Implementation', $choicesImplementation, $this);
    }

    public function getResult() {
        return $this->get('Result')->getSelectedItem();
    }

    public function getId() {
        return $this->parent->getId();
    }

    public function getImplementation() {
        return $this->Implementation;
    }
    
    public function getTrigger() {
        return $this->Trigger->getSelectedItem();
    }

    public function setResult($result) {        
        if ($result === 'Message' || $result === 'TriggerResultMessage') {
            $this->Implementation->selectItem('WebService');
            $result = 'Message';
        }
        $this->Trigger->selectItem($result);
        $i = 0;
        $items = $this->get('Result')->getChoices();
        foreach ($items as $value) {
            $pos = stripos($value->getTagName(), $result);
            if ($pos !== FALSE) {
                $this->get('Result')->select($i);
                break;
            }
            else
                $i++;
        }
    }

    public function setImplementation($implementation) {
        if ($this->Implementation->getSelectedItem() !== $implementation) {
            $this->Implementation->select($implementation);
        }
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Base_ComplexChoice('Result', array(
                    new ZendExt_WF_WFObject_Entidades_TriggerResultMessage($this),
                    new ZendExt_WF_WFObject_Entidades_ResultError($this),
                    new ZendExt_WF_WFObject_Entidades_TriggerResultCompensation($this),
                    new ZendExt_WF_WFObject_Entidades_TriggerResultSignal($this),
                    new ZendExt_WF_WFObject_Entidades_ResultMultiple($this)
                   ), $this));
    }

    public function toArray() {
        $array = array(
            'Result' => $this->getResult()->toArray(),
            'Implementation' => $this->getImplementation()->toArray(),
                /* 'ComplexChoice'=>  $this->getResult()->toArray() */
        );
        return $array;
    }
    
    public function toName() {
        return 'End Event';
    }

}

?>
