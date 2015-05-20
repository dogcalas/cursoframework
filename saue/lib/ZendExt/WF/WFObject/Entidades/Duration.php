<?php

class ZendExt_WF_WFObject_Entidades_Duration extends ZendExt_WF_WFObject_Base_SimpleElement {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Duration";
    }

    public function clonar() {
        return;
    }

    public function getDuration() {
        return $this->value;
    }

    public function setDuration($duration) {
        $this->value = $duration;
    }
    
    public function toArray() {
        $array = array(
            'Duration' => $this->getDuration()
        );
        return $array;
    }

}

?>
