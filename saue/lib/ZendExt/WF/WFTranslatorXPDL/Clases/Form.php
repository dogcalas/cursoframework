<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Form extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleXPDL() {
        return;
    }

    function desassembleClass() {
        $objFormLayout = $this->object->getFormLayout();
        $myFormLayout = new ZendExt_WF_WFTranslatorXPDL_Clases_FormLayout($objFormLayout);
        $this->addAttribute($myFormLayout);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement('Form');

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>

