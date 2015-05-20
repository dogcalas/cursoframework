<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_ExternalReference extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("ExternalReference");

        $thisObjectTag->setAttribute('xref', $this->object->getXREF());
        $thisObjectTag->setAttribute('location', $this->object->getLocation());
        $thisObjectTag->setAttribute('namespace', $this->object->getNamespace());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>

