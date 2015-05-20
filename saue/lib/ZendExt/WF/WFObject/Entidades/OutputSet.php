<?php

class ZendExt_WF_WFObject_Entidades_OutputSet extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'OutputSet';
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Output($this));
        return;
    }

    public function getOutput() {
        return $this->get('Output');
    }

    public function toArray() {
        $array = array(
            'Output' => $this->getOutput()->toArray()
        );
        return $array;
    }

}

?>
