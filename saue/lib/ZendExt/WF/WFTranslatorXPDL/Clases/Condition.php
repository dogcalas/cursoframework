<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Condition extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objTypeCondition = $this->object->getExpression();
        $myExpressionType = new ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType($objTypeCondition);
        $this->addAttribute($myExpressionType);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Condition");
        $thisObjectTag->setAttribute('Type', $this->object->getType());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
