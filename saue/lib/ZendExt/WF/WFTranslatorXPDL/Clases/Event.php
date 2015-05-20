<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Event extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object, $desassembleClass = TRUE) {
        parent::__construct($object, $desassembleClass);
    }

    function desassembleClass() {
        $objTypeEvent = $this->object->getEventType()->getSelectedItem();
        if ($objTypeEvent instanceof ZendExt_WF_WFObject_Entidades_StartEvent) {
            $myStartEvent = new ZendExt_WF_WFTranslatorXPDL_Clases_StartEvent($objTypeEvent);
            $this->addAttribute($myStartEvent);
            return;
        }
        if ($objTypeEvent instanceof ZendExt_WF_WFObject_Entidades_IntermediateEvent) {
            $myIntermediateEvent = new ZendExt_WF_WFTranslatorXPDL_Clases_IntermediateEvent($objTypeEvent);
            $this->addAttribute($myIntermediateEvent);
            return;
        }
        if ($objTypeEvent instanceof ZendExt_WF_WFObject_Entidades_EndEvent) {
            $myEndEvent = new ZendExt_WF_WFTranslatorXPDL_Clases_EndEvent($objTypeEvent);
            $this->addAttribute($myEndEvent);
            return;
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Event");

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
                $eventType = $this->object->getEventType();                
                $eventType->selectItem($nodeName);
                $newObject = $eventType->getSelectedItem();
                $prefClassName = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
                $className = $prefClassName . $nodeName;
                $newTag = new $className($newObject, FALSE);
                $newTag->fromXPDL($node);
            }
        }
    }

}

?>
