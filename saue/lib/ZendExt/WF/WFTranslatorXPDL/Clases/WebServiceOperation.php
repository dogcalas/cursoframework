<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_WebServiceOperation extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objPartner = $this->object->getPartner();
        $myPartner = new ZendExt_WF_WFTranslatorXPDL_Clases_Partner($objPartner);
        $this->addAttribute($myPartner);

        $objService = $this->object->getService();
        $myService = new ZendExt_WF_WFTranslatorXPDL_Clases_Service($objService);
        $this->addAttribute($myService);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("WebServiceOperation");

        $thisObjectTag->setAttribute('OperationName', $this->object->getOperationName());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
