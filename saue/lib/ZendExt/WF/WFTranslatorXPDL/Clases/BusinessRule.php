<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_BusinessRule extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objRuleName = $this->object->getRuleName();
        $myRuleName = new ZendExt_WF_WFTranslatorXPDL_Clases_RuleName($objRuleName);
        $this->addAttribute($myRuleName);

        $objLocation = $this->object->getLocation();
        $myLocation = new ZendExt_WF_WFTranslatorXPDL_Clases_Location($objLocation);
        $this->addAttribute($myLocation);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("BusinessRule");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>

