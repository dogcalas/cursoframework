<?php

class ZendExt_WF_WFObject_Entidades_IORules extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'IORules';
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_ExpressionType($this, 'Expression'));
        return;
    }

    public function getExpression() {
        return $this->get('Expression');
    }

    public function toArray() {
        $array = array(
            'ExpressionType' => $this->getExpression()->toArray()
        );
        return $array;
    }

}

?>
