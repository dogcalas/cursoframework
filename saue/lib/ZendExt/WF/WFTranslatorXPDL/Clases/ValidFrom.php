<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_ValidFrom extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("ValidFrom");        
        $thisObjectTag->appendChild($doc->createTextNode($this->object->getValidFrom()));
        $objectTag->appendChild($thisObjectTag);
    }

}

?>
