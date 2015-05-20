<?php

class ZendExt_WF_WFObject_Entidades_LoopMultiInstance extends ZendExt_WF_WFObject_Base_Complex {

    private $MI_Condition;
    private $LoopCounter;
    private $MI_Ordering;
    private $MI_FlowCondition;
    private $ComplexMI_FlowCondition;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "LoopMultiInstance";
    }

    /*
     * Getters
     */
    public function getMI_ConditionFromStruct() {
        return $this->get('MI_Condition');
    }

    public function getComplexMIFlowConditionFromStruct() {
        return $this->get('ComplexMI_FlowCondition');
    }

    public function getMI_Condition() {
        return $this->MI_Condition;
    }

    public function getLoopCounter() {
        return $this->LoopCounter;
    }

    public function getMI_Ordering() {
        return $this->MI_Ordering->getSelectedItem();
    }

    public function getMI_FlowCondition() {
        return $this->MI_FlowCondition;
    }

    public function getComplexMI_FlowCondition() {
        return $this->ComplexMI_FlowCondition;
    }

    /*
     * Setters
     */
    public function setMI_FlowCondition($mi_flowcondition) {
        $this->MI_FlowCondition = $mi_flowcondition;
    }

    public function setComplexMI_FlowCondition($complexMI_FlowCondition) {
        $this->ComplexMI_FlowCondition = $complexMI_FlowCondition;
    }

    public function setMI_Ordering($mi_ordering) {
        $this->MI_Ordering->selectItem($mi_ordering);
    }

    public function setLoopCounter($_loopCounter) {
        $this->LoopCounter = $_loopCounter;
    }

    public function setMI_Condition($_mi_conditions) {
        $this->MI_Condition = $_mi_conditions;
    }

    /*
     * Abstractions
     */
    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_ExpressionType('MI_Condition', $this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ExpressionType('ComplexMI_FlowCondition', $this));

        $MI_OrderingChoices = array('Sequential', 'Parallel');
        $this->MI_Ordering = new ZendExt_WF_WFObject_Base_SimpleChoice('MI_Ordering', $MI_OrderingChoices, NULL);

        $MI_FlowConditionChoices = array('None', 'One', 'All', 'Complex');
        $this->MI_FlowCondition = new ZendExt_WF_WFObject_Base_SimpleChoice('MI_FlowCondition', $MI_FlowConditionChoices, NULL);
        return;
    }

    public function toArray() {
        $array = array(
            'MI_Condition' => $this->getMI_Conditions(),
            'LoopCounter' => $this->getLoopCounter(),
            'MI_Ordering' => $this->getMI_Ordering(),
            'MI_FlowCondition' => $this->getMI_FlowCondition(),
            'ComplexMI_FlowCondition' => $this->getComplexMI_FlowCondition(),
                /* 'ExpressionType'=>  $this->getMI_Condition()->toArray(),
                  'ExpressionType'=>  $this->getComplexMIFlowCondition()->toArray()
                 */
        );
        return $array;
    }

}

?>
