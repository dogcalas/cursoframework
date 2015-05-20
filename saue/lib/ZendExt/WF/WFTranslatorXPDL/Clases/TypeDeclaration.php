<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TypeDeclaration extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objDataTypes = $this->object->getDataTypes();
        $myDataTypes = new ZendExt_WF_WFTranslatorXPDL_Clases_DataTypes($objDataTypes);
        $this->addAttribute($myDataTypes);

        $objDescription = $this->object->getDescription();
        $myDescription = new ZendExt_WF_WFTranslatorXPDL_Clases_Description($objDescription);
        $this->addAttribute($myDescription);

        $objExtendedAttributes = $this->object->getExtendedAttributes();
        $myExtendedAttributes = new ZendExt_WF_WFTranslatorXPDL_Clases_ExtendedAttributes($objExtendedAttributes);
        $this->addAttribute($myExtendedAttributes);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TypeDeclaration");

        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Name', $this->object->getName());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>