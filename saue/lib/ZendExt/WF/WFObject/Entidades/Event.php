<?php

class ZendExt_WF_WFObject_Entidades_Event extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Event';
    }

    public function getId() {
        return $this->parent->getId();
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $options = array
            (
            new ZendExt_WF_WFObject_Entidades_StartEvent($this),
            new ZendExt_WF_WFObject_Entidades_IntermediateEvent($this),
            new ZendExt_WF_WFObject_Entidades_EndEvent($this)
        );
        $this->add(new ZendExt_WF_WFObject_Base_ComplexChoice('EventType', $options, $this));
        return;
    }

    public function getEventType() {
        return $this->get('EventType');
    }

    public function toArray() {
        $array = array(
            'EventType' => $this->getEventType()->toArray()
        );
        return $array;
    }
    
    public function toName() {
        return $this->getEventType()->getSelectedItem()->toName();
    }

}

?>
