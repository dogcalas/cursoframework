<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_LayoutInfo extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("LayoutInfo");
        $thisObjectTag->setAttribute('PixelsPerMillimeter', $this->object->PixelsPerMillimeter());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
