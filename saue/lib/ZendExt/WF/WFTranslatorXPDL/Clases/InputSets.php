
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_InputSets extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objInputSet = $this->object->get($i);
            $myInputSet = new ZendExt_WF_WFTranslatorXPDL_Clases_InputSet($objInputSet);

            $this->addAttribute($myInputSet);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("InputSets");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

    

}

?>
