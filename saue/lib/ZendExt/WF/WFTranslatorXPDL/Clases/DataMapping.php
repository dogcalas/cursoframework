<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_DataMapping extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objActual = $this->object->getActual();
        $myActual = new ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType($objActual);
        $this->addAttribute($myActual);

        $objTestValue = $this->object->getTestValue();
        $myTestValue = new ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType($objTestValue);
        $this->addAttribute($myTestValue);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("DataMapping");

        $thisObjectTag->setAttribute('Formal', $this->object->getFormal());
        $thisObjectTag->setAttribute('Direction', $this->object->getDirection());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>