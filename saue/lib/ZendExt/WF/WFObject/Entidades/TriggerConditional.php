<?php

class ZendExt_WF_WFObject_Entidades_TriggerConditional extends ZendExt_WF_WFObject_Base_Complex {

    private $ConditionName;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TriggerConditional';
    }

    public function getConditionName() {
        return $this->ConditionName;
    }

    public function setConditionName($_conditionName) {
        $this->ConditionName = $_conditionName;
    }

    public function getExpression() {
        return $this->get('Expression');
    }

    public function clonar() {

        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_ExpressionType($this, 'Expression'));

        return;
    }

    public function toArray() {
        $array = array(
            'ConditionName' => $this->getConditionName(),
            'Expression' => $this->getExpression()->toArray()
        );
        return $array;
    }

}

?>
