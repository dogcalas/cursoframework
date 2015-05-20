<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_IntermediateEvent extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    public function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $objIntermediateEvent = $this->object->getResult()->getSelectedItem();

        $objIntermediateEventTagName = $objIntermediateEvent->getTagName();
        $classPreffix = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
        $class = $classPreffix . $objIntermediateEventTagName;
        $myTriggerResultClassInstance = new $class($objIntermediateEvent);
        $this->addAttribute($myTriggerResultClassInstance);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("IntermediateEvent");
        $thisObjectTag->setAttribute('Trigger', $this->object->getTrigger());
        $thisObjectTag->setAttribute('Implementation', $this->object->getImplementation());

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

            $Act_Type = $this->object->getTriggerType();
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
