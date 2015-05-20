<?php

class ZendExt_WF_WFObject_Entidades_LoopStandard extends ZendExt_WF_WFObject_Base_Complex {

    private $LoopCondition;
    private $LoopCounter;
    private $LoopMaximum;
    private $TestTime;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "LoopStandard";
    }

    /*
     * Getters
     */

    public function getLoopConditionFromStruct() {
        return $this->get('LoopCondition');
    }

    public function getLoopCondition() {
        return $this->LoopCondition;
    }

    public function getLoopCounter() {
        return $this->LoopCounter;
    }

    public function getLoopMaximum() {
        return $this->LoopMaximum;
    }

    public function getTestTime() {
        return $this->TestTime;
    }

    /*
     * Setters
     */

    public function setTestTime($_testTime) {
        $this->TestTime->selectItem($_testTime);
    }

    public function setLoopMaximum($_loopMaximum) {
        $this->LoopMaximum = $_loopMaximum;
    }

    public function setLoopCounter($_loopCounter) {
        $this->LoopCounter = $_loopCounter;
    }

    public function setLoopCondition($_loopCondition) {
        $this->LoopCondition = $_loopCondition;
    }

    /*
     * Abstractions
     */

    public function clonar() {
        return;
    }


    public function toArray() {
        $array = array(
            'LoopCondition' => $this->getLoopCondition(),
            'LoopCounter' => $this->getLoopCounter(),
            'LoopMaximum' => $this->getLoopMaximum(),
            'TestTime' => $this->getTestTime()

                /* 'ExpressionType'=>  $this->getLoopConditions()->toArray() */
        );
        return $array;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_ExpressionType('LoopCondition', $this));

        $testTimeChoices = array('Before', 'After');
        $this->TestTime = new ZendExt_WF_WFObject_Base_SimpleChoice('TestTime', $testTimeChoices, NULL);
        return;
    }
}

?>
