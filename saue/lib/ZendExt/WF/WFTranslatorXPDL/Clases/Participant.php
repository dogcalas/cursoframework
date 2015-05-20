<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Participant extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $objParticipantType = $this->object->getParticipantType();
        $myParticipantType = new ZendExt_WF_WFTranslatorXPDL_Clases_ParticipantType($objParticipantType);

        $objDescription = $this->object->getDescription();
        $myDescription = new ZendExt_WF_WFTranslatorXPDL_Clases_Description($objDescription);

        $this->addAttribute($myParticipantType);
        $this->addAttribute($myDescription);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Participant");

        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Name', $this->object->getName());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }
        $objectTag->appendChild($thisObjectTag);
    }

}

?>