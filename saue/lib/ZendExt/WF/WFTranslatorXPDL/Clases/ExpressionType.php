
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_ExpressionType extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        /*
         * Lo mismo que en EnumerationValue
         */
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement($this->object->getTagName());

        $thisObjectTag->setAttribute('ScriptType',$this->object->getScriptType());
        $thisObjectTag->setAttribute('ScriptVersion', $this->object->getScriptVersion());
        $thisObjectTag->setAttribute('ScriptGrammar', $this->object->getScriptGrammar());

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