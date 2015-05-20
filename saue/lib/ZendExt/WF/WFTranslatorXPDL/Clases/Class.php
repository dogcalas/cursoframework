<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Class extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object,$bool) {
        parent::__construct($object,$bool);
    }
    function desassembleClass() {
        $bool=TRUE;
        
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Class");

        //$thisObjectTag->setAttribute('Id', 'falta  capturar Id'/* $this->object->getValue() */);
        //$thisObjectTag->setAttribute('Name', 'falta  capturar Name'/* $this->object->getValue() */);
        //$thisObjectTag->setAttribute('ReadOnly', $this->object->getReadOnly() );
        //$thisObjectTag->setAttribute('IsArray', $this->object->getIsArray() );
        //$thisObjectTag->setAttribute('Correlation',$this->object->getCorrelation() );
        $thisObjectTag->appendChild($doc->createTextNode('falta'));

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>

