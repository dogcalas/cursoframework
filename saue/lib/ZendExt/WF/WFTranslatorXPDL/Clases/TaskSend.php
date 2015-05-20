<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TaskSend extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $objMessage = $this->object->getMessage();
        $myMessage = new ZendExt_WF_WFTranslatorXPDL_Clases_MessageType($objMessage);
        $this->addAttribute($myMessage);

        $objWebServiceOperation = $this->object->getWebServiceOperation();
        $myWebServiceOperation = new ZendExt_WF_WFTranslatorXPDL_Clases_WebServiceOperation($objWebServiceOperation);
        $this->addAttribute($myWebServiceOperation);

        $objWebServiceFaultCatch = $this->object->getWebServiceFaultCatch();
        $myWebServiceFaultCatch = new ZendExt_WF_WFTranslatorXPDL_Clases_WebServiceFaultCatch($objWebServiceFaultCatch);
        $this->addAttribute($myWebServiceFaultCatch);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TaskSend");
        
        $thisObjectTag->setAttribute('Implementation', $this->object->getImplementation());
        
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?> 