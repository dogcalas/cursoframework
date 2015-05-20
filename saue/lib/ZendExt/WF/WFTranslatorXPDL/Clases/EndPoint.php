<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_EndPoint extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objExternalReference = $this->object->getExternalReference();
        $myExternalReference = new ZendExt_WF_WFTranslatorXPDL_Clases_ExternalReference($objExternalReference);
        $this->addAttribute($myExternalReference);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("EndPoint");
        $thisObjectTag->setAttribute('EndPointType', $this->object->getEndPointType());


        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
