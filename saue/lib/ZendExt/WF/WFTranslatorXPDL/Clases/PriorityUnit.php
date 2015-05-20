<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_PriorityUnit extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $PriorityUnit = $doc->createElement("PriorityUnit");
        $PriorityUnit->appendChild($doc->createTextNode($this->object->getPriorityUnit()));
        $objectTag->appendChild($PriorityUnit);
    }

}

?>
