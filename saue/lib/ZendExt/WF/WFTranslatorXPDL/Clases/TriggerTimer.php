
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TriggerTimer extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objTimeType = $this->object->getTimerType()->getSelectedItem();

        if ($objTimeType->getTagName() == 'TimeDate') {
            $myTimeDate = new ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType($objTimeType);
            $this->addAttribute($myTimeDate);
            return;
        }
        if ($objTimeType->getTagName() == 'TimeCycle') {
            $myTimeCycle = new ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType($objTimeType);
            $this->addAttribute($myTimeCycle);
            return;
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TriggerTimer");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

    public function fromXPDL($objectTag) {



        $attribs = $objectTag->attributes;

        if ($attribs->length > 0) {
            for ($i = 0; $i < $attribs->length; $i++) {
                $node = $attribs->item($i);
                $prefFuncName = 'set';
                $fullFuncName = $prefFuncName . $node->nodeName;
                $this->object->$fullFuncName($node->nodeValue);
            }
        }

        for ($i = 0; $i < $objectTag->childNodes->length; $i++) {
            $Nonde = $objectTag->childNodes->item($i);
            $NodeName = $Nonde->nodeName;
            $Act_Type = $this->object->getTimerType();
            $Act_Type->selectItem($NodeName);
            $cObj = $Act_Type->getSelectedItem();
            $prefClassName = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
            $ClassName = $prefClassName . $NodeName;
            $newTag = new $ClassName($cObj);
            $newTag->fromXPDL($Nonde);
        }
    }

}
?>


