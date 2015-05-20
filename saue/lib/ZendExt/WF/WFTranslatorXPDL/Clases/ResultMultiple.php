<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_ResultMultiple extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $objTriggerResultMessage = $this->object->getTriggerResultMessage();
        $myTriggerResultMessage = new ZendExt_WF_WFTranslatorXPDL_Clases_TriggerResultMessage($objTriggerResultMessage);
        $this->addAttribute($myTriggerResultMessage);

        $objTriggerResultLink = $this->object->getTriggerResultLink();
        $myTriggerResultLink = new ZendExt_WF_WFTranslatorXPDL_Clases_TriggerResultLink($objTriggerResultLink);
        $this->addAttribute($myTriggerResultLink);

        $objTriggerResultCompensation = $this->object->getTriggerResultCompensation();
        $myTriggerResultCompensation = new ZendExt_WF_WFTranslatorXPDL_Clases_TriggerResultCompensation($objTriggerResultCompensation);
        $this->addAttribute($myTriggerResultCompensation);

        $objResultError = $this->object->getResultError();
        $myResultError = new ZendExt_WF_WFTranslatorXPDL_Clases_ResultError($objResultError);
        $this->addAttribute($myResultError);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("ResultMultiple");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?> 
