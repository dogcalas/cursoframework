<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TaskScript extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $myScript = $this->object->getScript();
        $myScript = new ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType($myScript);
        $this->addAttribute($myScript);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TaskScript");
        
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?> 