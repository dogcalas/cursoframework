<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Lanes extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objLane = $this->object->get($i);
            $myLane = new ZendExt_WF_WFTranslatorXPDL_Clases_Lane($objLane);
            $this->addAttribute($myLane);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Lanes");


        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }
        $objectTag->appendChild($thisObjectTag);
    }

}

?>