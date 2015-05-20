
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_MessageFlows extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objMessageFlow = $this->object->get($i);
            $myMessageFlow = new ZendExt_WF_WFTranslatorXPDL_Clases_MessageFlow($objMessageFlow);
            $this->addAttribute($myMessageFlow);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("MessageFlows");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>