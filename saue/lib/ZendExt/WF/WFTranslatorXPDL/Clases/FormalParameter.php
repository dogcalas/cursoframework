<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_FormalParameter extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    
    public function desassembleClass() {
        $objDataType = $this->object->getDataType();
        $myDataType = new ZendExt_WF_WFTranslatorXPDL_Clases_DataType($objDataType);
        $this->addAttribute($myDataType);
        
        $objInitialValue = $this->object->getInitialValue();
        $myInitialValue = new ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType($objInitialValue);
        $this->addAttribute($myInitialValue);
        
        $objDescription= $this->object->getDescription();
        $myDescription= new ZendExt_WF_WFTranslatorXPDL_Clases_Description($objDescription);
        $this->addAttribute($myDescription);

        $objLength= $this->object->getLength();
        $myLength= new ZendExt_WF_WFTranslatorXPDL_Clases_Length($objLength);
        $this->addAttribute($myLength);
               
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("FormalParameter");
        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Mode', $this->object->getMode());
        $thisObjectTag->setAttribute('Name', $this->object->getName());
        $thisObjectTag->setAttribute('ReadOnly', $this->object->getReadOnly());
        $thisObjectTag->setAttribute('Required', $this->object->getRequired());
        $thisObjectTag->setAttribute('IsArray', 'falta  capturar IsArray'/* $this->object->getValue() */);


        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>

