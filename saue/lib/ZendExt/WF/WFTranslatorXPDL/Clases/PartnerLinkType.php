<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_PartnerLinkType extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $objRole = $this->object->getMyRole();
        $myRole = new ZendExt_WF_WFTranslatorXPDL_Clases_MyRole($objRole);
        $this->addAttribute($myRole);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("PartnerLinkType");
        
        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Name', $this->object->getName());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>