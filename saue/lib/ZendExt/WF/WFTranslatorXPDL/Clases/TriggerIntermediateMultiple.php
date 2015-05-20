<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TriggerIntermediateMultiple extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }


    function desassembleClass() {
        $objTriggerResultMessage=$this->object->getTriggerResultMessage();
        $myTriggerResultMessage = new ZendExt_WF_WFTranslatorXPDL_Clases_TriggerResultMessage($objTriggerResultMessage);
        $this->addAttribute($myTriggerResultMessage);
        
        $objTriggerTimer=$this->object->getTriggerTimer();
        $myTriggerTimer = new ZendExt_WF_WFTranslatorXPDL_Clases_TriggerTimer($objTriggerTimer);
        $this->addAttribute($myTriggerTimer);
        
        $objResultError=$this->object->getResultError();
        $myResultError = new ZendExt_WF_WFTranslatorXPDL_Clases_ResultError($objResultError);
        $this->addAttribute($myResultError);
        
        $objTriggerConditional=$this->object->getTriggerConditional();
        $myTriggerConditional = new ZendExt_WF_WFTranslatorXPDL_Clases_TriggerConditional($objTriggerConditional);
        $this->addAttribute($myTriggerConditional);
        
        $objTriggerResultLink=$this->object->getTriggerResultLink();
        $myTriggerResultLink = new ZendExt_WF_WFTranslatorXPDL_Clases_TriggerResultLink($objTriggerResultLink);
        $this->addAttribute($myTriggerResultLink);
        
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TriggerIntermediateMultiple");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>