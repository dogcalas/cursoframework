<?php

class ZendExt_WF_WFObject_Entidades_BasicType extends ZendExt_WF_WFObject_Base_Complex {

    private $type;

    public function __construct($parent, $fillStructure = TRUE) {
        parent::__construct($parent,$fillStructure);
        $this->tagName = 'BasicType';
    }

    public function clonar() {
        $clone = new ZendExt_WF_WFObject_Entidades_BasicType($this->parent, FALSE);
        
        $typeChoices = array('STRING', 'FLOAT', 'INTEGER', 'REFERENCE', 'DATETIME', 'DATE', 'TIME', 'BOOLEAN', 'PERFORMER');
        $clone->type = new ZendExt_WF_WFObject_Base_SimpleChoice('Type', $typeChoices, NULL, $this->type->getSelectedIndex());
        
        $structElements = $this->items;
        
        foreach ($structElements as $structElement) {
            $cloneElement = $structElement->clonar();
            $clone->add($cloneElement);
        }
        return $clone;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Length($this));
        //$this->add(new ZendExt_WF_WFObject_Entidades_Precision($this));
        //$this->add(new ZendExt_WF_WFObject_Entidades_Scale($this));

        $typeChoices = array('STRING', 'FLOAT', 'INTEGER', 'REFERENCE', 'DATETIME', 'DATE', 'TIME', 'BOOLEAN', 'PERFORMER');
        $this->type = new ZendExt_WF_WFObject_Base_SimpleChoice('Type', $typeChoices, NULL);
        
        return;
    }
    
    public function getLength() {
        return $this->get('Length');
    }
    
    public function getPrecision() {
        return $this->get('Precision');
    }    

    public function getScale() {
        return $this->get('Scale');
    }    
    
    public function getType() {
        return $this->type->getSelectedItem();
    }

    public function setType($_type) {
        $this->type->selectItem($_type);
    }
    
    
    public function toArray() {
        $array = array(
            'Type' => $this->getType(),
            'Length' => $this->getLength()->toArray(),
            'Precision' => $this->getPrecision()->toArray(),
            'Scale' => $this->getScale()->toArray()
        );
        return $array;
    }

}

?>
