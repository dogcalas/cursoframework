<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Loop extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objLoopType = $this->object->getLoopTypes()->getSelectedItem();

        if ($objLoopType instanceof ZendExt_WF_WFObject_Entidades_LoopStandard) {
            $myLoopStandard = new ZendExt_WF_WFTranslatorXPDL_Clases_LoopStandard($objLoopType);
            $this->addAttribute($myLoopStandard);
            return;
        }
        if ($objLoopType instanceof ZendExt_WF_WFObject_Entidades_LoopMultiInstance) {
            $myLoopMultiInstance = new ZendExt_WF_WFTranslatorXPDL_Clases_LoopMultiInstance($objLoopType);
            $this->addAttribute($myLoopMultiInstance);
            return;
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Loop");

        $thisObjectTag->setAttribute('LoopType', $this->object->getLoopType());

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
            $Act_Type = $this->object->getLoopTypes();
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