<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Activities extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objActivity = $this->object->get($i);
            $myActivity = new ZendExt_WF_WFTranslatorXPDL_Clases_Activity($objActivity);
            $this->addAttribute($myActivity);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Activities");
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }
        $objectTag->appendChild($thisObjectTag);
    }

}
?> 

