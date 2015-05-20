<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Join extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Join");

        $thisObjectTag->setAttribute('Type', $this->object->getType());
        $thisObjectTag->setAttribute('ExclusiveType', $this->object->getExclusiveType());
        $thisObjectTag->setAttribute('IncomingCondition', $this->object->getIncomingCondition());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>