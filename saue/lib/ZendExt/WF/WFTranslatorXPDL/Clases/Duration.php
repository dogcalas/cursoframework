<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Duration extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Duration");
        $thisObjectTag->appendChild($doc->createTextNode($this->object->getDuration()));
        $objectTag->appendChild($thisObjectTag);
    }

}

?>
