<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_PartnerLink extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objMyRole = $this->object->getMyRole();
        $myMyRole = new ZendExt_WF_WFTranslatorXPDL_Clases_MyRole($objMyRole);
        $this->addAttribute($myMyRole);

        $objPartnerRole = $this->object->getPartnerRole();
        $myPartnerRole = new ZendExt_WF_WFTranslatorXPDL_Clases_PartnerRole($objPartnerRole);
        $this->addAttribute($myPartnerRole);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("PartnerLink");

        $thisObjectTag->setAttribute('name', $this->object->getName());
        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('PartnerLinkTypeId', $this->object->getPartnerLinkTypeId());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>