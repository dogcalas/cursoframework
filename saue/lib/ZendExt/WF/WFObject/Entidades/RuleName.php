<?php

class ZendExt_WF_WFObject_Entidades_RuleName extends ZendExt_WF_WFObject_Base_SimpleElement {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'RuleName';
    }

    public function setRuleName($_ruleName) {
        $this->setValue($_ruleName);
    }

    public function getRuleName() {
        return $this->getValue();
    }

    public function clonar() {
        return;
    }

}

?>
