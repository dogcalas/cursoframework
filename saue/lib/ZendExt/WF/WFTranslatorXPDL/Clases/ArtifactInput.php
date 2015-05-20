
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_ArtifactInput extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("ArtifactInput");

        $thisObjectTag->setAttribute('ArtifactId', $this->object->getArtifactId());
        $thisObjectTag->setAttribute('RequiredForStart', $this->object->getRequiredForStart());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>