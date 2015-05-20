<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_StartEvent extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object, $desassembleClass = TRUE) {
        parent::__construct($object, $desassembleClass);
    }

    function desassembleClass() {
        $objTypeStartEvent = $this->object->getResult()->getSelectedItem();
        if ($objTypeStartEvent instanceof ZendExt_WF_WFObject_Entidades_TriggerResultMessage) {
            $myTriggerResultMessage = new ZendExt_WF_WFTranslatorXPDL_Clases_TriggerResultMessage($objTypeStartEvent);
            $this->addAttribute($myTriggerResultMessage);
            return;
        }
        if ($objTypeStartEvent instanceof ZendExt_WF_WFObject_Entidades_TriggerTimer) {
            $myTriggerTimer = new ZendExt_WF_WFTranslatorXPDL_Clases_TriggerTimer($objTypeStartEvent);
            $this->addAttribute($myTriggerTimer);
            return;
        }
        if ($objTypeStartEvent instanceof ZendExt_WF_WFObject_Entidades_TriggerConditional) {
            $myTriggerConditional = new ZendExt_WF_WFTranslatorXPDL_Clases_TriggerConditional($objTypeStartEvent);
            $this->addAttribute($myTriggerConditional);
            return;
        }
        if ($objTypeStartEvent instanceof ZendExt_WF_WFObject_Entidades_TriggerResultSignal) {
            $myTriggerResultSignal = new ZendExt_WF_WFTranslatorXPDL_Clases_TriggerResultSignal($objTypeStartEvent);
            $this->addAttribute($myTriggerResultSignal);
            return;
        }
        if ($objTypeStartEvent instanceof ZendExt_WF_WFObject_Entidades_TriggerMultiple) {
            $myTriggerMultiple = new ZendExt_WF_WFTranslatorXPDL_Clases_TriggerMultiple($objTypeStartEvent);
            $this->addAttribute($myTriggerMultiple);
            return;
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("StartEvent");

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
            $node = $objectTag->childNodes->item($i);
            $nodeName = $node->nodeName;

            if ($node->nodeType === XML_ELEMENT_NODE) {
                $this->object->getResult()->selectItem($nodeName);
                $newObject = $this->object->getResult()->getSelectedItem();
                $prefClassName = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
                $className = $prefClassName . $nodeName;
                $newTag = new $className($newObject, FALSE);
                $newTag->fromXPDL($node);
            }
        }
    }

}

?>
