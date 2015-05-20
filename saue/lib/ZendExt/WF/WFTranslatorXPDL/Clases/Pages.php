
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Pages extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objPage = $this->object->get($i);
            $myPage = new ZendExt_WF_WFTranslatorXPDL_Clases_Page($objPage);
            $this->addAttribute($myPage);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Pages");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>