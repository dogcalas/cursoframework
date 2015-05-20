
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_OutputSets extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objOutputSet = $this->object->get($i);
            $myOutputSet = new ZendExt_WF_WFTranslatorXPDL_Clases_OutputSet($objOutputSet);
            $this->addAttribute($myOutputSet);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("OutputSets");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
