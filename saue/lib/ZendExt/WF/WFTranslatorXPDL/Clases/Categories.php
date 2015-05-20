<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Categories extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objCategory = $this->object->get($i);
            $myCategory = new ZendExt_WF_WFTranslatorXPDL_Clases_Category($objCategory);
            $this->addAttribute($myCategory);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Categories");
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }
        $objectTag->appendChild($thisObjectTag);
    }

}
?>

