<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Length extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Length");
        $thisObjectTag->appendChild($doc->createTextNode($this->object->getLength()));

        $objectTag->appendChild($thisObjectTag);
    }

}
?>

