<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_IORules extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objExpression = $this->object->getExpression();
        $myExpression = new ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType($objExpression);
        $this->addAttribute($myExpression);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("IORules");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>