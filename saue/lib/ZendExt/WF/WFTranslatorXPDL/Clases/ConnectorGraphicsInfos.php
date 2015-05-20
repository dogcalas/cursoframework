<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_ConnectorGraphicsInfos extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objConnectorGraphicsInfo = $this->object->get($i);
            $myConnectorGraphicsInfo = new ZendExt_WF_WFTranslatorXPDL_Clases_ConnectorGraphicsInfo($objConnectorGraphicsInfo);
            $this->addAttribute($myConnectorGraphicsInfo);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("ConnectorGraphicsInfos");
        
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?> 

