<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_HomeClass extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("HomeClass");
        //$thisObjectTag->appendChild($doc->createTextNode('falta'));
        
        /*
         * Puede o no ejecutarse
         */
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>

