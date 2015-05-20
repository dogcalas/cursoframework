<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_XPDLVersion extends ZendExt_WF_WFTranslatorXPDL_Base_Base{

    function __construct($object) {
        parent::__construct($object);
    }


   function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("XPDLVersion");
        $thisObjectTag->appendChild($doc->createTextNode($this->object->getXPDLVersion()));
        $objectTag->appendChild($thisObjectTag);
    }

}

?> 
