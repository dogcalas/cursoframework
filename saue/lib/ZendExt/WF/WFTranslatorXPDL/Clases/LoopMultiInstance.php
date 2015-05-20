<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_LoopMultiInstance extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objMI_Condition = $this->object->getMI_ConditionFromStruct();
        $myMI_Condition = new ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType($objMI_Condition);
        $this->addAttribute($myMI_Condition);

        $objComplexMIFlowCondition = $this->object->getComplexMIFlowConditionFromStruct();
        $myComplexMIFlowCondition = new ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType($objComplexMIFlowCondition);
        $this->addAttribute($myComplexMIFlowCondition);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("LoopMultiInstance");

        $thisObjectTag->setAttribute('MI_Conditions', $this->object->getMI_Condition());
        $thisObjectTag->setAttribute('LoopCounter', $this->object->getLoopCounter());
        $thisObjectTag->setAttribute('MI_Ordering', $this->object->getMI_Ordering());
        $thisObjectTag->setAttribute('MI_FlowCondition', $this->object->getMI_FlowCondition());
        $thisObjectTag->setAttribute('ComplexMI_FlowCondition', $this->object->getComplexMI_FlowCondition());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}