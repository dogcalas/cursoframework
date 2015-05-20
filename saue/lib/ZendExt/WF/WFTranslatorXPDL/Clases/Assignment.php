<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Assignment extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objExpression = $this->object->getExpression();
        $myExpressionType = new ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType($objExpression);
        $this->addAttribute($myExpressionType);

        $objTarget = $this->object->getTarget();
        $myTarget = new ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType($objTarget);
        $this->addAttribute($myTarget);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Assignment");
        $thisObjectTag->setAttribute('AssignTime', 'falta  capturar AssignTime'/* $this->object->getValue() */);

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>