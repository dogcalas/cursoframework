<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_DataField extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objDataType = $this->object->getDataType();
        $myDataType = new ZendExt_WF_WFTranslatorXPDL_Clases_DataType($objDataType);
        $this->addAttribute($myDataType);

        /*$objInitialValue = $this->object->getInitialValue();
        $myInitialValue = new ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType($objInitialValue);
        $this->addAttribute($myInitialValue);*/

        $objLength = $this->object->getLength();
        $myLength = new ZendExt_WF_WFTranslatorXPDL_Clases_Length($objLength);
        $this->addAttribute($myLength);

        /*$objDescription = $this->object->getDescription();
        $myDescription = new ZendExt_WF_WFTranslatorXPDL_Clases_Description($objDescription);
        $this->addAttribute($myDescription);

        $objExtendedAttributes = $this->object->getExtendedAttributes();
        $myExtendedAttributes = new ZendExt_WF_WFTranslatorXPDL_Clases_ExtendedAttributes($objExtendedAttributes);
        $this->addAttribute($myExtendedAttributes);*/
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("DataField");

        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Name', $this->object->getName());
        $thisObjectTag->setAttribute('ReadOnly', $this->object->getReadOnly());
        $thisObjectTag->setAttribute('IsArray', $this->object->getIsArray());
        //$thisObjectTag->setAttribute('Correlation', $this->object->getCorrelation());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
