<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_DataFields extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objDataField = $this->object->get($i);
            $myDataField = new ZendExt_WF_WFTranslatorXPDL_Clases_DataField($objDataField);
            $this->addAttribute($myDataField);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("DataFields");

        //$thisObjectTag->setAttribute('Id', 'falta  capturar Id'/* $this->object->getValue() */);
        //$thisObjectTag->appendChild($doc->createTextNode($this->object->getValue()));

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
