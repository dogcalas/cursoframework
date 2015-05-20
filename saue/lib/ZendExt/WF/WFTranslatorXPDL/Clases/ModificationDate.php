<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_ModificationDate extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("ModificationDate");
        $thisObjectTag->appendChild($doc->createTextNode($this->object->getModificationDate()));
        
        /*
         * No itera
         */
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
