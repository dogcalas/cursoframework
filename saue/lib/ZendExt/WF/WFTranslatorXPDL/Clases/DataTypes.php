<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_DataTypes extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objType = $this->object->getSelectedItem();
        $classInstance = 'ZendExt_WF_WFTranslatorXPDL_Clases_' . $objType->getTagName();
        $myObjType = new $classInstance($objType);
        $this->addAttribute($myObjType);
    }

    public function toXPDL($doc, &$objectTag) {
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $objectTag);
        }
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
            $Act_Type = $this->object->getDatatype();
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
