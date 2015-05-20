<?php

class ZendExt_WF_WFObject_Entidades_TaskScript extends ZendExt_WF_WFObject_Entidades_Task {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TaskScript';
    }

    public function getScript() {
        return $this->get('Script');
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_ExpressionType('Script', $this));
        return;
    }

}

?>
