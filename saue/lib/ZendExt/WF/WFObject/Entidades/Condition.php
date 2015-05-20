<?php

class ZendExt_WF_WFObject_Entidades_Condition extends ZendExt_WF_WFObject_Base_Complex {

    private $Type;

    public function __construct($parent, $fillStructure = TRUE) {
        parent::__construct($parent, $fillStructure);
        $this->tagName = 'Condition';
    }

    public function getType() {
        return $this->Type->getSelectedItem();
    }

    public function setType($_type) {
        $this->Type->selectItem($_type);
        $this->parent->setConditionType($_type);
    }

    public function clonar() {
        $clone = new ZendExt_WF_WFObject_Entidades_Condition($this->parent, FALSE);
        
        $clone->add($this->getExpression()->clonar());
        
        $typeChoices = array('CONDITION','OTHERWISE','EXCEPTION','DEFAULTEXCEPTION');
        $clone->Type = new ZendExt_WF_WFObject_Base_SimpleChoice('Type', $typeChoices, NULL);
        
        return $clone;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_ExpressionType($this,'Expression'));
        $typeChoices = array('CONDITION','OTHERWISE','EXCEPTION','DEFAULTEXCEPTION');
        $this->Type = new ZendExt_WF_WFObject_Base_SimpleChoice('Type', $typeChoices, NULL);
        return;
    }

    public function getExpression() {
        return $this->get('Expression');
    }
    
    
    
    public function toArray() {
        $array = array(
            'Type' => $this->getType(),
            'ExpressionType'=>  $this->getExpression()->toArray()
        );
        return $array;
    }

}

?>
