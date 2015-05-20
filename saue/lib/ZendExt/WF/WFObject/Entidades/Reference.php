<?php

class ZendExt_WF_WFObject_Entidades_Reference extends ZendExt_WF_WFObject_Base_Complex {

    private $ActivityId;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Reference';
    }

    public function getActivityId() {
        return $this->ActivityId;
    }

    public function setActivityId($_activityId) {
        $this->ActivityId = $_activityId;
    }

    public function clonar() {
        
    }


    public function toArray() {
        $array = array(
            'ActivityId' => $this->getActivityId()
        );
        return $array;
    }


    public function fillStructure() {
        return;
    }


}

?>
