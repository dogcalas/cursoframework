<?php

class ZendExt_WF_WFObject_Entidades_BusinessRule extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'BusinessRule';
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_RuleName($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Location($this));
        return;
    }

    public function getRuleName() {
        return $this->get('RuleName');
    }

    public function getLocation() {
        return $this->get('Location');
    }

}

?>
