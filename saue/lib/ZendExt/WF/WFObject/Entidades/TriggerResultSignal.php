<?php

class ZendExt_WF_WFObject_Entidades_TriggerResultSignal extends ZendExt_WF_WFObject_Base_Complex {

    private $CatchThrow;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TriggerResultSignal';
    }

    public function getCatchThrow() {
        return $this->CatchThrow->getSelectedItem();
    }

    public function setCatchThrow($_catchThrow) {
        $this->CatchThrow->selectItem($_catchThrow);
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_ExpressionType($this,'Properties'));
        
        $catchThrowChoices = array('CATCH','THROW');
        $this->CatchThrow = new ZendExt_WF_WFObject_Base_SimpleChoice('CatchTrow', $catchThrowChoices, NULL);
    }

    public function getProperties() {
        return $this->get('Properties');
    }

    public function toArray() {
        $array = array(
            'CatchThrow' => $this->getCatchThrow(),
            'Name' => $this->getName(),
            'Properties' => $this->getProperties()->toArray()
        );
        return $array;
    }

}

?>
