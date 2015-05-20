<?php

class ZendExt_WF_WFObject_Entidades_DataMapping extends ZendExt_WF_WFObject_Base_Complex {

    private $Formal;
    private $Direction;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'DataMapping';
    }

    /*
     * Getters
     */

    public function getActual() {
        return $this->get('Actual');
    }

    public function getTestValue() {
        return $this->get('TestValue');
    }

    public function getFormal() {
        return $this->Formal;
    }

    public function getDirection() {
        return $this->Direction->getSelectedItem();
    }

    /*
     * Setters
     */

    public function setDirection($_direction) {
        $this->Direction->selectItem($_direction);
    }

    public function setFormal($_formal) {
        $this->Formal = $_formal;
    }

    /*
     * Abstractions
     */

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_ExpressionType($this,'Actual'));
        $this->add(new ZendExt_WF_WFObject_Entidades_ExpressionType($this,'TestValue'));

        $directionChoices = array('IN', 'OUT', 'INOUT');
        $this->Direction = new ZendExt_WF_WFObject_Base_SimpleChoice('Direction', $directionChoices, NULL);
    }

    public function toArray() {
        $array = array(
            'Formal' => $this->getFormal(),
            'Direction' => $this->getDirection(),
            /*'ExpressionType' => $this->getActual(),
            'ExpressionType' => $this->getTestValue()*/
        );
        return $array;
    }

}

?>
