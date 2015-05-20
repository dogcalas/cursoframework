<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TimeEstimation extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }


    function desassembleClass() {
        $objWaitingTime = $this->object->getWaitingTime();
        $objWorkingTime = $this->object->getWorkingTime();
        $objDuration = $this->object->getDuration();
        
        $myWaitingTime = new ZendExt_WF_WFTranslatorXPDL_Clases_WaitingTime($objWaitingTime);
        $myWorkingTime = new ZendExt_WF_WFTranslatorXPDL_Clases_WorkingTime($objWorkingTime);
        $myDuration = new ZendExt_WF_WFTranslatorXPDL_Clases_Duration($objDuration);
        
        $this->addAttribute($myWaitingTime);
        $this->addAttribute($myWorkingTime);
        $this->addAttribute($myDuration);
       
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TimeEstimation");
        
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }


}

?>
