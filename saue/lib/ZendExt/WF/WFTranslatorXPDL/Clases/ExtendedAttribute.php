<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_ExtendedAttribute extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("ExtendedAttribute");

        $thisObjectTag->setAttribute('Name', $this->object->getName());
        $thisObjectTag->setAttribute('Value', $this->object->getValue());


        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
