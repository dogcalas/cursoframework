<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_WebService extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objWebServiceOperation = $this->object->getWebServiceOperation();
        $myWebServiceOperation = new ZendExt_WF_WFTranslatorXPDL_Clases_WebServiceOperation($objWebServiceOperation);
        $this->addAttribute($myWebServiceOperation);

        $objWebServiceFaultCatch = $this->object->getWebServiceFaultCatch();
        $myWebServiceFaultCatch = new ZendExt_WF_WFTranslatorXPDL_Clases_WebServiceFaultCatch($objWebServiceFaultCatch);
        $this->addAttribute($myWebServiceFaultCatch);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("WebService");

        $thisObjectTag->setAttribute('InputMsgName', $this->object->getInputMsgName());
        $thisObjectTag->setAttribute('OutputMsgName', $this->object->getOutputMsgName());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>

