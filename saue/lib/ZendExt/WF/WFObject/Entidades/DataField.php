<?php

class ZendExt_WF_WFObject_Entidades_DataField extends ZendExt_WF_WFObject_Base_Complex {

    private $readOnly;
    private $isArray;
    private $correlation;

    public function __construct($parent, $fillStructure = TRUE) {
        parent::__construct($parent,$fillStructure);
        $this->tagName = 'DataField';
    }

    /*
     * Setters
     */

    public function setReadOnly($_readOnly) {
        $this->readOnly = $_readOnly;
    }

    public function setIsArray($_isArray) {
        $this->isArray = $_isArray;
    }

    public function setCorrelation($_correlation) {
        $this->correlation = $_correlation;
    }

    /*
     * Getters
     */

    public function getReadOnly() {
        return $this->readOnly;
    }

    public function getIsArray() {
        return $this->isArray;
    }

    public function getCorrelation() {
        return $this->correlation;
    }

    public function getDataType() {
        return $this->get('DataType');
    }

    public function getInitialValue() {
        return $this->get('InitialValue');
    }

    public function getLength() {
        return $this->get('Length');
    }

    public function getDescription() {
        return $this->get('Description');
    }

    public function getExtendedAttributes() {
        return $this->get('ExtendedAttributes');
    }

    /*
     * Abstractions
     */

    public function clonar() {
        $clone = new ZendExt_WF_WFObject_Entidades_DataField($this->parent,FALSE);
        foreach ($this->items as $cloneElement) {
            $clone->add($cloneElement->clonar());
        }        
        return $clone;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_DataType($this));
        //$this->add(new ZendExt_WF_WFObject_Entidades_ExpressionType('InitialValue', $this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Length($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Description($this));
        //$this->add(new ZendExt_WF_WFObject_Entidades_ExtendedAttributes($this));
        return;
    }

    public function toArray() {
        $array = array(
            'Id' => $this->getId(),
            'Name' => $this->getName(),
            'ReadOnly' => $this->getReadOnly(),
            'IsArray' => $this->getIsArray(),
            'Correlation' => $this->getCorrelation(),
            'DataType' => $this->getDataType()->toArray(),
           /* 'ExpressionType'=>  $this->getInitialValue(),*/
            'Length' => $this->getLength()->toArray(),
            'Description' => $this->getDescription()->toArray(),
            'ExtendedAttributes' => $this->getExtendedAttributes()->toArray()
        );
        return $array;
    }

}

?>
