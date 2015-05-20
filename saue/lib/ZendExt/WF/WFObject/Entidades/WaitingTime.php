<?php

class ZendExt_WF_WFObject_Entidades_WaitingTime extends ZendExt_WF_WFObject_Base_SimpleElement {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "WaitingTime";
    }

    public function clonar() {
        return;
    }

    public function getWaitingTime() {
        return $this->getValue();
    }

    public function setWaitingTime($_waitingTime) {
        $this->setValue($_waitingTime);
    }

    public function toArray() {
        $array = array(
            'WaitingTime' => $this->getWaitingTime()
        );
        return $array;
    }

}

?>
