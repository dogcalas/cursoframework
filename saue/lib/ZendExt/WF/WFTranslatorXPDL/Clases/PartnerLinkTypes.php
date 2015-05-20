
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_PartnerLinkTypes extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objPartnerLinkType = $this->object->get($i);
            $myPartnerLinkType = new ZendExt_WF_WFTranslatorXPDL_Clases_PartnerLinkType($objPartnerLinkType);
            $this->addAttribute($myPartnerLinkType);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("PartnerLinkTypes");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>