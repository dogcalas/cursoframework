<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Limit extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    /* public function fromXPDL($objectTag,$myclase)                 


      } */

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Limit");
        $thisObjectTag->appendChild($doc->createTextNode($this->object->getValue()));

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?> 