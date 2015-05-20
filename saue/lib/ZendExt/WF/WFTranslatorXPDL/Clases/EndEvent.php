<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_EndEvent extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object, $desassembleClass = TRUE) {
        parent::__construct($object, $desassembleClass);
    }

    function desassembleClass() {
        $objEndEvent = $this->object->getResult();
        $resultPrefix = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
        $resultType = $objEndEvent->getTagName();
        $result = $resultPrefix . $resultType;

        $myObjEndEvent = new $result($objEndEvent);
        $this->addAttribute($myObjEndEvent);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("EndEvent");
        $thisObjectTag->setAttribute('Result', $this->object->getResult());
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
                $this->object->setResult($nodeName);
                $newObject = $this->object->getResult();
                $prefClassName = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
                $className = $prefClassName . $nodeName;
                $newTag = new $className($newObject, FALSE);
                $newTag->fromXPDL($node);
            }
        }
    }

}

?>
