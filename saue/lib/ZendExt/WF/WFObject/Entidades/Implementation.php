<?php

class ZendExt_WF_WFObject_Entidades_Implementation extends ZendExt_WF_WFObject_Base_ComplexChoice {

    public function __construct($choices, $parent) {
        $this->tagName = 'Implementation';
        parent::__construct($this->tagName, $choices, $parent);

        $this->fillStructure();
    }

    private function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_DataFields($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_InputSets($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_OutputSets($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_IORules($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Loop($this));
        return;
    }

    public function getDataFields() {
        return $this->get('DataFields');
    }

    public function getInputSets() {
        return $this->get('InputSets');
    }

    public function getOutputSets() {
        return $this->get('OutputSets');
    }

    public function getIORules() {
        return $this->get('IORules');
    }

    public function getLoop() {
        return $this->get('Loop');
    }

}

?>
