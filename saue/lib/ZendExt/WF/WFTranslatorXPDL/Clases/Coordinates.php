<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Coordinates extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Coordinates");
        
        $thisObjectTag->setattribute("XCoordinate", $this->object->getXCoordinate());
        $thisObjectTag->setattribute("YCoordinate", $this->object->getYCoordinate());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>