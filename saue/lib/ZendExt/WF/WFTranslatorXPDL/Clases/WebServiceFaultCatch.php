<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_WebServiceFaultCatch extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objMessage = $this->object->getMessage();
        $myMessage = new ZendExt_WF_WFTranslatorXPDL_Clases_MessageType($objMessage);
        $this->addAttribute($myMessage);
        
        $objParameter = $this->object->getParameters()->getSelectedItem();
        $classPreffix = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
        $class = $classPreffix.$objParameter->getTagName();
        $myParameter = new $class($objParameter);
        $this->addAttribute($myParameter);        
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("WebServiceFaultCatch");

        $thisObjectTag->setAttribute('FaultName', $this->object->getFaultName());

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

            if ($NodeName == 'TransitionRef' || $NodeName == 'BlockActivity') {

                $Act_Type = $this->object->getParameters();
                $Act_Type->selectItem($NodeName);
                $cObj = $Act_Type->getSelectedItem();
                $prefClassName = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
                $ClassName = $prefClassName . $NodeName;
                $newTag = new $ClassName($cObj);
                $newTag->fromXPDL($Nonde);
            } else {
                $prefFuncName = 'get';
                $fullFuncName = $prefFuncName . $NodeName;
                $cObject = $this->object->$fullFuncName();
                $prefClassName = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
                $ClassName = $prefClassName . $NodeName;
                $newTag = new $ClassName($cObject);
                $newTag->fromXPDL($Nonde);
            }
        }
    }

}

?>
