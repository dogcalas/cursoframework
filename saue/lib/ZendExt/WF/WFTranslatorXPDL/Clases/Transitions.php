<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Transitions extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object, $desassembleClass = TRUE) {
        parent::__construct($object, $desassembleClass);
    }

    public function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objTransition = $this->object->get($i);
            $myTransition = new ZendExt_WF_WFTranslatorXPDL_Clases_Transition($objTransition);
            $this->addAttribute($myTransition);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Transitions");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?> 

