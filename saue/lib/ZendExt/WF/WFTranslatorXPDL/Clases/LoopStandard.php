<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_LoopStandard extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    public function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objLoopCondition = $this->object->getLoopConditionFromStruct();
        $myLoopCondition = new ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType($objLoopCondition);
        $this->addAttribute($myLoopCondition);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("LoopStandard");
        $thisObjectTag->setAttribute('LoopCondition', $this->object->getLoopCondition());
        $thisObjectTag->setAttribute('LoopCounter', $this->object->getLoopCounter());
        $thisObjectTag->setAttribute('LoopMaximum', $this->object->getLoopMaximum());
        $thisObjectTag->setAttribute('TestTime', $this->object->getTestTime());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
