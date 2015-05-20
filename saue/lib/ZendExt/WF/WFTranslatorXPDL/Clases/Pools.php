<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Pools extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objPool = $this->object->get($i);
            $myPool = new ZendExt_WF_WFTranslatorXPDL_Clases_Pool($objPool);
            $this->addAttribute($myPool);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Pools");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>