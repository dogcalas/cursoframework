<?php

class ZendExt_WF_WFObject_Entidades_ResultMultiple extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'ResultMultiple';
    }

    public function getTriggerResultMessage() {
        return $this->get('TriggerResultMessage');
    }

    public function getResultError() {
        return $this->get('ResultError');
    }

    public function getTriggerResultCompensation() {
        return $this->get('TriggerResultCompensation');
    }

    public function getTriggerResultLink() {
        return $this->get('TriggerResultLink');
    }

    /*
     * Abstractions
     */
    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_TriggerResultMessage($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_TriggerResultLink($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_TriggerResultCompensation($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ResultError($this));
    }

    public function toArray() {
        $array = array(
            'TriggerResultMessage' => $this->getTriggerResultMessage()->toArray(),
            'TriggerResultLink' => $this->getTriggerResultLink()->toArray(),
            'TriggerResultCompensation' => $this->getTriggerResultCompensation()->toArray(),
            'ResultError' => $this->getResultError()->toArray()
        );
        return $array;
    }

}

?>
