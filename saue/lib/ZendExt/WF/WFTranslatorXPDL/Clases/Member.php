<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Member extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

   

    function desassembleClass() {
        $objDataTypes = $this->object->getDataTypes();
        $myDataTypes = new ZendExt_WF_WFTranslatorXPDL_Clases_DataTypes($objDataTypes);
        $this->addAttribute($myDataTypes);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Member");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>

