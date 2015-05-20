<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Created extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {

        $thisObjectTag = $doc->createElement("Created");
        $thisObjectTag->appendChild($doc->createTextNode($this->object->getCreated()));

        $objectTag->appendChild($thisObjectTag);
    }

}

?> 