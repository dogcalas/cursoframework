<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TriggerResultMessage extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object, $desassembleClass = TRUE) {
        parent::__construct($object, $desassembleClass);
    }

    function desassembleClass() {
        $objMessageType = $this->object->getMessage();
        $myMessageType = new ZendExt_WF_WFTranslatorXPDL_Clases_MessageType($objMessageType);
        $this->addAttribute($myMessageType);

        $objWebServiceOperation = $this->object->getWebServiceOperation();
        $myWebServiceOperation = new ZendExt_WF_WFTranslatorXPDL_Clases_WebServiceOperation($objWebServiceOperation);
        $this->addAttribute($myWebServiceOperation);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TriggerResultMessage");

        $thisObjectTag->setAttribute('CatchThrow', $this->object->getCatchThrow());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>


