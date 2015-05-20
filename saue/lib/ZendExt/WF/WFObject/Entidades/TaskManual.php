<?php

class ZendExt_WF_WFObject_Entidades_TaskManual extends ZendExt_WF_WFObject_Entidades_Task {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TaskManual';
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Performers($this));
        return;
    }

    public function getPerformers() {
        return $this->get('Performers');
    }

}

?>
