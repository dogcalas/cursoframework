<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TaskManual extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $objPerformers = $this->object->getPerformers();
        $myPerformers = new ZendExt_WF_WFTranslatorXPDL_Clases_Performers($objPerformers);
        $this->addAttribute($myPerformers);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TaskManual");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?> 