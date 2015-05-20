<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_RecordType extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objMember = $this->object->getMember();
        $myMember = new ZendExt_WF_WFTranslatorXPDL_Clases_Member($objMember);
        $this->addAttribute($myMember);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("RecordType");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
