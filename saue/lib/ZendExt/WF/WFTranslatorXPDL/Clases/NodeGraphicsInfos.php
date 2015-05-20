<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_NodeGraphicsInfos extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objNodeGraphicsInfo = $this->object->get($i);
            $myNodeGraphicsInfo = new ZendExt_WF_WFTranslatorXPDL_Clases_NodeGraphicsInfo($objNodeGraphicsInfo);
            $this->addAttribute($myNodeGraphicsInfo);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("NodeGraphicsInfos");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>