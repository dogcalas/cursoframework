<?php

class ZendExt_WF_WFObject_Entidades_Priority extends ZendExt_WF_WFObject_Base_SimpleElement {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Priority";
    }

    public function clonar() {
        return;
    }

    public function getPriority() {
        return $this->value;
    }

    public function setPriority($priority) {
        $this->value = $priority;
    }
    
    public function toArray() {
        $array = array(
            'Priority' => $this->getPriority()
        );
        return $array;
    }

}

?>
