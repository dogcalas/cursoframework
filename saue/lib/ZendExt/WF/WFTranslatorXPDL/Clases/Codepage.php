<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Codepage extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    public function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Codepage");
        $thisObjectTag->appendChild($doc->createTextNode($this->object->getCodePage()));

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
	