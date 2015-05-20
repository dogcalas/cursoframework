<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_ActualParameters extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objActualParameter = $this->object->get($i);
            $myActualParameter = new ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType($objActualParameter);
            $this->addAttribute($myActualParameter);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("ActualParameters");
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?> 