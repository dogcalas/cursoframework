<?php

class ZendExt_WF_WFObject_Entidades_TriggerMultiple extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TriggerMultiple';
    }

    public function getTriggerResultMessage() {
        return $this->get('TriggerResultMessage');
    }

    public function getTriggerTimer() {
        return $this->get('TriggerTimer');
    }

    public function getTriggerConditional() {
        return $this->get('TriggerConditional');
    }

    public function getTriggerResultLink() {
        return $this->get('TriggerResultLink');
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_TriggerResultMessage($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_TriggerTimer($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_TriggerConditional($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_TriggerResultLink($this));
        return;
    }

    public function toArray() {
        $array = array(
            'TriggerResultMessage' => $this->getTriggerResultMessage()->toArray(),
            'TriggerTimer' => $this->getTriggerTimer()->toArray(),
            'TriggerConditional' => $this->getTriggerConditional()->toArray(),
            'TriggerResultLink' => $this->getTriggerResultLink()->toArray()
        );
        return $array;
    }

}

?>
