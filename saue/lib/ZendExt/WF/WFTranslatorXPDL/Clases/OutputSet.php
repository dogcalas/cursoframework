
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_OutputSet extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objOutput = $this->object->getOutput();
        $myOutput = new ZendExt_WF_WFTranslatorXPDL_Clases_Output($objOutput);
        $this->addAttribute($myOutput);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("OutputSet");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>