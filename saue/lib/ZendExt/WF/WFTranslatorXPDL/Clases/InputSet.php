
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_InputSet extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objInput = $this->object->getInput();
        $objArtifactInput = $this->object->getArtifactInput();
        $objPropertyInput = $this->object->getPropertyInput();

        $myInput = new ZendExt_WF_WFTranslatorXPDL_Clases_Input($objInput);
        $myArtifactInput = new ZendExt_WF_WFTranslatorXPDL_Clases_ArtifactInput($objArtifactInput, $bool);
        $myPropertyInput = new ZendExt_WF_WFTranslatorXPDL_Clases_PropertyInput($objPropertyInput, $bool);

        $this->addAttribute($myInput);
        $this->addAttribute($myArtifactInput);
        $this->addAttribute($myPropertyInput);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("InputSet");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>