<?php

class ZendExt_WF_WFObject_Entidades_TimeEstimation extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TimeEstimation';
    }

    public function getWaitingTime() {
        return $this->get('WaitingTime');
    }

    public function getWorkingTime() {
        return $this->get('WorkingTime');
    }

    public function getDuration() {
        return $this->get('Duration');
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_WaitingTime($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_WorkingTime($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Duration($this));
        return;
    }

    public function toArray() {
        $array = array(
            'WaitingTime' => $this->getWaitingTime()->toArray(),
            'WorkingTime' => $this->getWorkingTime()->toArray(),
            'Duration' => $this->getDuration()->toArray()
        );
        return $array;
    }

}

?>
