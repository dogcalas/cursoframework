<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Countrykey extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    public function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Countrykey");
        $thisObjectTag->appendChild($doc->createTextNode($this->object->getCountryKey()));        

        $objectTag->appendChild($thisObjectTag);
    }

}

?>