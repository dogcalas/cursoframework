
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_PartnerLinks extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objPartnerLink = $this->object->get($i);
            $myPartnerLink = new ZendExt_WF_WFTranslatorXPDL_Clases_PartnerLink($objPartnerLink);
            $this->addAttribute($myPartnerLink);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("PartnerLinks");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>