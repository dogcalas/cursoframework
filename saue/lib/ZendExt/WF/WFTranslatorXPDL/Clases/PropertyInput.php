

<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_PropertyInput extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("PropertyInput");

        $thisObjectTag->setAttribute('PropertyId', 'falta  capturar PropertyId'/* $this->object->getValue() */);

        /*
         * No itera...
         */
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>