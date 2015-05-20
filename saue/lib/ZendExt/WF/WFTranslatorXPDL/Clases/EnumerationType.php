<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_EnumerationType extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    

    function desassembleClass() {
        $enumerationValue = $this->object->getEnumerationValue();
        $myEnumerationValue = new ZendExt_WF_WFTranslatorXPDL_Clases_EnumerationValue($enumerationValue);
        $this->addAttribute($myEnumerationValue);
    }


    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("EnumerationType");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
