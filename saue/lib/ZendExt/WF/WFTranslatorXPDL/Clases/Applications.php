
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Applications extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objApplication = $this->object->get($i);
            $myApplication = new ZendExt_WF_WFTranslatorXPDL_Clases_Application($objApplication);
            $this->addAttribute($myApplication);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Applications");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>