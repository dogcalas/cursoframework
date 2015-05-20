<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TaskReceive extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

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
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TaskReceive");

        $thisObjectTag->setAttribute('Instantiate', $this->object->getInstantiate());
        $thisObjectTag->setAttribute('Implementation', $this->object->getImplementation());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?> 