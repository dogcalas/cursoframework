<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TriggerResultSignal extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objProperties = $this->object->getProperties();
        $myProperties = new ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType($objProperties);
        $this->addAttribute($myProperties);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TriggerResultSignal");

        $thisObjectTag->setAttribute('Name', $this->object->getName());
        $thisObjectTag->setAttribute('CatchThrow', $this->object->getCatchThrow());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>


