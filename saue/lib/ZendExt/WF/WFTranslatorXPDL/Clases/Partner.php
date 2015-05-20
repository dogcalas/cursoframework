<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Partner extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Partner");

        $thisObjectTag->setAttribute('PartnerLinkId', $this->object->getPartnerLinkId());
        $thisObjectTag->setAttribute('RoleType', $this->object->getRoleType());


        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>