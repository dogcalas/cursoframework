<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_MessageFlow extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objMessage = $this->object->getMessage();
        $myMessage = new ZendExt_WF_WFTranslatorXPDL_Clases_MessageType($objMessage);
        $this->addAttribute($myMessage);

        $objObject = $this->object->getObject();
        $myObject = new ZendExt_WF_WFTranslatorXPDL_Clases_Object($objObject);
        $this->addAttribute($myObject);

        $objConnectorGraphicsInfos = $this->object->getConnectorGraphicsInfos();
        $myConnectorGraphicsInfos = new ZendExt_WF_WFTranslatorXPDL_Clases_ConnectorGraphicsInfos($objConnectorGraphicsInfos);
        $this->addAttribute($myConnectorGraphicsInfos);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("MessageFlow");
        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Name', $this->object->getName());
        $thisObjectTag->setAttribute('Source', $this->object->getSource());
        $thisObjectTag->setAttribute('Target', $this->object->getTarget());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>