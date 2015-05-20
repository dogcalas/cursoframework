<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Participants extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objParticipant = $this->object->get($i);
            $myParticipant = new ZendExt_WF_WFTranslatorXPDL_Clases_Participant($objParticipant);
            $this->addAttribute($myParticipant);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Participants");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>