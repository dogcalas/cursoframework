<?php

class ZendExt_WF_WFObject_Entidades_Loop extends ZendExt_WF_WFObject_Base_Complex {

    private $LoopType;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Loop";
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $options = array
            (
            new ZendExt_WF_WFObject_Entidades_LoopStandard($this),
            new ZendExt_WF_WFObject_Entidades_LoopMultiInstance($this)
        );
        $this->add(new ZendExt_WF_WFObject_Base_ComplexChoice('LoopTypes', $options, $this));
        
        $loopTypeChoices = array('Standard','MultiInstance');
        $this->LoopType = new ZendExt_WF_WFObject_Base_SimpleChoice('LoopType', $loopTypeChoices, NULL);
        return;
    }

    public function getLoopTypes() {
        return $this->get('LoopTypes');
    }

    public function getLoopType() {
        return $this->LoopType->getSelectedItem();
    }

    public function setLoopType($_loopType) {
        $this->LoopType->selectItem($_loopType);
    }
    
    public function toArray() {
        $array = array(
            'LoopType'=>$this->getLoopType()
        );
        return $array;
    }

}

?>
