<?php

class ZendExt_WF_WFObject_Entidades_WorkingTime extends ZendExt_WF_WFObject_Base_SimpleElement {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "WorkingTime";
    }

    public function clonar() {
        return;
    }

    public function getWorkingTime(){
        return $this->getValue();
    }

    public function setWorkingTime($workingTime) {
        $this->setValue($workingTime);
    }
    
    public function toArray() {
        $array = array(
            'WorkingTime'=>  $this->getWorkingTime()
        );
        return $array;
    }

}

?>
