
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Artifacts extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objArtifact = $this->object->get($i);
            $myArtifact = new ZendExt_WF_WFTranslatorXPDL_Clases_Artifact($objArtifact);
            $this->addAttribute($myArtifact);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Artifacts");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>