<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Association extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objObject = $this->object->getObject();
        $myObject = new ZendExt_WF_WFTranslatorXPDL_Clases_Object($objObject, $bool);
        $this->addAttribute($myObject);

        $objConnectorGraphicsInfos = $this->object->getConnectorGraphicsInfos();
        $myConnectorGraphicsInfos = new ZendExt_WF_WFTranslatorXPDL_Clases_ConnectorGraphicsInfos($objConnectorGraphicsInfos);
        $this->addAttribute($myConnectorGraphicsInfos);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Association");
        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Source', $this->object->getSource());
        $thisObjectTag->setAttribute('Target', $this->object->getTarget());
        $thisObjectTag->setAttribute('Name', 'falta  capturar Name'/* $this->object->getValue() */);
        $thisObjectTag->setAttribute('AssociationDirection', $this->object->getAssociationDirection());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>