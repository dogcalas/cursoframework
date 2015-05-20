<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Version extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    public function __construct($object) {

        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Version");        
        $thisObjectTag->appendChild($doc->createTextNode($this->object->getVersion()));
        $objectTag->appendChild($thisObjectTag);
    }

}

?>
