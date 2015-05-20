<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_MessageType extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object, $dessasemble = TRUE) {
        parent::__construct($object, $dessasemble);
    }

    function desassembleClass() {
        $objParametersType = $this->object->getParameters()->getSelectedItem();

        if ($objParametersType instanceof ZendExt_WF_WFObject_Entidades_ExpressionType) {
            $myExpressionType = new ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType($objParametersType);
            $this->addAttribute($myExpressionType);
            return;
        }

        if ($objParametersType instanceof ZendExt_WF_WFObject_Entidades_DataMappings) {
            $myDataMappings = new ZendExt_WF_WFTranslatorXPDL_Clases_DataMappings($objParametersType);
            $this->addAttribute($myDataMappings);
            return;
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $tagElement = $this->object->getTagName();
        $thisObjectTag = $doc->createElement($tagElement);

        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Name', $this->object->getName());
        $thisObjectTag->setAttribute('From', $this->object->getFrom());
        $thisObjectTag->setAttribute('To', $this->object->getTo());
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
            $Act_Type = $this->object->getParameters();
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
