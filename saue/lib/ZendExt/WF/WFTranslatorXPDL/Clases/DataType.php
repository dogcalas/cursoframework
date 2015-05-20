<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_DataType extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objDataTypes = $this->object->getDataTypes();
        $myDataTypes = new ZendExt_WF_WFTranslatorXPDL_Clases_DataTypes($objDataTypes);
        $this->addAttribute($myDataTypes);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("DataType");

        //$thisObjectTag->setAttribute('Id', 'falta  capturar Id'/* $this->object->getValue() */);
        //$thisObjectTag->setAttribute('Name', 'falta  capturar Id'/* $this->object->getValue() */);
        //$thisObjectTag->setAttribute('ReadOnly', $this->object->getReadOnly() );
        //$thisObjectTag->setAttribute('IsArray', $this->object->getIsArray() );
        //$thisObjectTag->setAttribute('Correlation',$this->object->getCorrelation() );
        //$thisObjectTag->appendChild($doc->createTextNode($this->object->getValue()));

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
