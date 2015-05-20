<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_DataObject extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("DataObject");
        
        $thisObjectTag->setAttribute('Id', 'falta  capturar Id'/* $this->object->getValue() */);
        $thisObjectTag->setAttribute('Name', 'falta  capturar Name'/* $this->object->getValue() */);
        $thisObjectTag->setAttribute('State', 'falta  capturar State'/* $this->object->getValue() */);

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>