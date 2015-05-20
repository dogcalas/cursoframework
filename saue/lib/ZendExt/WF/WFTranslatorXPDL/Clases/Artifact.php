<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Artifact extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {

        $objTypeArt = $this->object->getObject();
        $myObject = new ZendExt_WF_WFTranslatorXPDL_Clases_Object($objTypeArt);
        $this->addAttribute($myObject);

        $objGroup = $this->object->getGroup();
        $myGroup = new ZendExt_WF_WFTranslatorXPDL_Clases_Group($objGroup);
        $this->addAttribute($myGroup);

        $objDataObject = $this->object->getDataObject();
        $myDataObject = new ZendExt_WF_WFTranslatorXPDL_Clases_DataObject($objDataObject);
        $this->addAttribute($myDataObject);

        $objNodeGraphicsInfos = $this->object->getNodeGraphicsInfos();
        $myNodeGraphicsInfos = new ZendExt_WF_WFTranslatorXPDL_Clases_NodeGraphicsInfos($objNodeGraphicsInfos);
        $this->addAttribute($myNodeGraphicsInfos);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Artifact");
        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Name', $this->object->getName());
        $thisObjectTag->setAttribute('ArtifactType', $this->object->getArtifactType());
        $thisObjectTag->setAttribute('TextAnnotation', 'falta  capturar TextAnnotation'/* $this->object->getValue() */);
        $thisObjectTag->setAttribute('Group', 'falta  capturar Group'/* $this->object->getValue() */);

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>