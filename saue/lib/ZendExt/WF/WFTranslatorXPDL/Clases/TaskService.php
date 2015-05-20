<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TaskService extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $objMessageIn = $this->object->getMessageIn();
        $myMessageIn = new ZendExt_WF_WFTranslatorXPDL_Clases_MessageType($objMessageIn);
        $this->addAttribute($myMessageIn);

        $objMessageOut = $this->object->getMessageOut();
        $myMessageOut = new ZendExt_WF_WFTranslatorXPDL_Clases_MessageType($objMessageOut);
        $this->addAttribute($myMessageOut);

        $objWebServiceOperation = $this->object->getWebServiceOperation();
        $myWebServiceOperation = new ZendExt_WF_WFTranslatorXPDL_Clases_WebServiceOperation($objWebServiceOperation);
        $this->addAttribute($myWebServiceOperation);

        $objWebServiceFaultCatch = $this->object->getWebServiceFaultCatch();
        $myWebServiceFaultCatch = new ZendExt_WF_WFTranslatorXPDL_Clases_WebServiceFaultCatch($objWebServiceFaultCatch);
        $this->addAttribute($myWebServiceFaultCatch);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TaskService");

        $thisObjectTag->setAttribute('Implementation', $this->object->getImplementation());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?> 