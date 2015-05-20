<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Icon extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Icon");

        $thisObjectTag->setAttribute('XCOORD', $this->object->getXCOORD());
        $thisObjectTag->setAttribute('YCOORD', $this->object->getYCOORD());
        $thisObjectTag->setAttribute('WIDTH', $this->object->getWIDTH());
        $thisObjectTag->setAttribute('HEIGHT', $this->object->getHEIGHT());
        $thisObjectTag->setAttribute('SHAPE', $this->object->getSHAPE());

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
 