<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Method extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

   
    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Method");
        $thisObjectTag->appendChild($doc->createTextNode($this->object->getMethod()));

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

