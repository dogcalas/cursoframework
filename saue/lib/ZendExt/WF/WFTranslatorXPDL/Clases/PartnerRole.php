<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_PartnerRole extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objEndPoint = $this->object->getEndPoint();
        $myEndPoint = new ZendExt_WF_WFTranslatorXPDL_Clases_EndPoint($objEndPoint);
        $this->addAttribute($myEndPoint);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("PartnerRole");
        
        $thisObjectTag->setAttribute('RoleName', $this->object->getRoleName());
        $thisObjectTag->setAttribute('ServiceName', $this->object->getServiceName());
        $thisObjectTag->setAttribute('PortName', $this->object->getPortName());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>