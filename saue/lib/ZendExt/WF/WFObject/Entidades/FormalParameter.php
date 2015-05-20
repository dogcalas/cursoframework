<?php

class ZendExt_WF_WFObject_Entidades_FormalParameter extends ZendExt_WF_WFObject_Base_Complex {

    private $mode;
    private $readOnly;
    private $required;
    private $isArray;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'FormalParameter';
    }

    /*
     * Getters
     */

    public function getMode() {
        return $this->mode->getSelectedItem();
    }

    public function getReadOnly() {
        return $this->readOnly;
    }

    public function getRequired() {
        return $this->required;
    }

    public function getIsArray() {
        return $this->isArray;
    }

    public function getDataType() {
        return $this->get('DataType');
    }

    public function getInitialValue() {
        return $this->get('InitialValue');
    }

    public function getDescription() {
        return $this->get('Description');
    }

    public function getLength() {
        return $this->get('Length');
    }

    /*
     * Setters
     */

    public function setRequired($_required) {
        $this->required = $_required;
    }

    public function setIsArray($_isArray) {
        $this->isArray = $_isArray;
    }

    public function setReadOnly($_readOnly) {
        $this->readOnly = $_readOnly;
    }

    public function setMode($_mode) {
        $this->mode->selectItem($_mode);
    }

    /*
     * Abstractions
     */

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_DataType($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ExpressionType($this, 'InitialValue'));
        $this->add(new ZendExt_WF_WFObject_Entidades_Description());
        $this->add(new ZendExt_WF_WFObject_Entidades_Length());

        $modeChoices = array('IN', 'OUT', 'INOUT');
        $this->mode = new ZendExt_WF_WFObject_Base_SimpleChoice('Mode', $modeChoices, NULL);
        return;
    }

    public function toArray() {
        $array = array(
            'Id' => $this->getId(),
            'Name' => $this->getName(),
            'Mode' => $this->getMode(),
            'ReadOnly' => $this->getReadOnly(),
            'Required' => $this->getRequired(),
            'IsArray' => $this->getIsArray(),
            'DataType' => $this->getDataType()->toArray(),
            /* 'ExpressionType'=>  $this->getInitialValue(), */
            'Description' => $this->getDescription()->toArray(),
            'Length' => $this->getLength()->toArray()
        );
        return $array;
    }

}

?>
