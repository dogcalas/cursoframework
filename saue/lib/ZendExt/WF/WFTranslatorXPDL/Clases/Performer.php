<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Performer extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisobjectTag = $doc->createElement("Performer");
        $thisobjectTag->appendChild($doc->createTextNode($this->object->getPerformer()));
        $objectTag->appendChild($thisobjectTag);
    }

}
?> 
