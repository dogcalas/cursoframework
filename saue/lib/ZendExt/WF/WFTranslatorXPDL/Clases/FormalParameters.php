<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_FormalParameters extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objFormalParameter = $this->object->get($i);
            $myFormalParameter = new ZendExt_WF_WFTranslatorXPDL_Clases_FormalParameter($objFormalParameter);
            $this->addAttribute($myFormalParameter);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("FormalParameters");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>

