<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_ProcessHeader extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

   
    public function desassembleClass() {

        $objCreated = $this->object->getCreated();
        $myCreated = new ZendExt_WF_WFTranslatorXPDL_Clases_Created($objCreated);
        $this->addAttribute($myCreated);

        $objDescription = $this->object->getDescription();
        $myDescription = new ZendExt_WF_WFTranslatorXPDL_Clases_Description($objDescription);
        $this->addAttribute($myDescription);

        $objPriority = $this->object->getPriority();
        $myPriority = new ZendExt_WF_WFTranslatorXPDL_Clases_Priority($objPriority);
        $this->addAttribute($myPriority);

        $objLimit = $this->object->getLimit();
        $myLimit = new ZendExt_WF_WFTranslatorXPDL_Clases_Limit($objLimit);
        $this->addAttribute($myLimit);

        $objTimeEstimation = $this->object->getTimeEstimation();
        $myTimeEstimation = new ZendExt_WF_WFTranslatorXPDL_Clases_TimeEstimation($objTimeEstimation);
        $this->addAttribute($myTimeEstimation);

        $objValidFrom = $this->object->getValidFrom();
        $myValidFrom = new ZendExt_WF_WFTranslatorXPDL_Clases_ValidFrom($objValidFrom);
        $this->addAttribute($myValidFrom);

        $objValidTo = $this->object->getValidTo();
        $myValidTo = new ZendExt_WF_WFTranslatorXPDL_Clases_ValidTo($objValidTo);
        $this->addAttribute($myValidTo);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("ProcessHeader");
        $thisObjectTag->setAttribute('DurationUnit', $this->object->getDurationUnit());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
