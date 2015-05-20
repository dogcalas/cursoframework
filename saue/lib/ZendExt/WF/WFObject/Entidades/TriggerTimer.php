<?php

class ZendExt_WF_WFObject_Entidades_TriggerTimer extends ZendExt_WF_WFObject_Base_Complex {

    private $TimeDate;  /*Deprecated*/
    private $TimeCycle; /*Deprecated*/

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TriggerTimer';
    }

    public function clonar() {
        return;
    }

    public function getTimeDate() {
        return $this->TimeDate;
    }

    public function setTimeDate($_timeDate) {
        $this->TimeDate = $_timeDate;
    }

    public function getTimer() {
        return $this->get('Timer');
    }

    public function getTimeCycle() {
        return $this->TimeCycle;
    }

    public function setTimeCycle($_timeCycle) {
        $this->TimeCycl = $_timeCycle;
    }

    public function fillStructure() {
        $options = array(new ZendExt_WF_WFObject_Entidades_ExpressionType($this,'TimeDate'),
            new ZendExt_WF_WFObject_Entidades_ExpressionType($this,'TimeCycle')
        );
        $this->add(new ZendExt_WF_WFObject_Base_ComplexChoice('TimerType', $options, $this));
        return;
    }

    public function getTimerType() {
        return $this->get('TimerType');
    }

    public function toArray() {
        $array = array(
            'TimeDate' => $this->getTimeDate(),
            'TimeCycle' => $this->getTimeCycle(),
            'TimerType' => $this->getTimerType()->toArray()
        );
        return $array;
    }

}

?>
