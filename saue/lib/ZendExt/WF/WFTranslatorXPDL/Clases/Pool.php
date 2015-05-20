<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Pool extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $objLanes = $this->object->getLanes();
        $myLanes = new ZendExt_WF_WFTranslatorXPDL_Clases_Lanes($objLanes);
        $this->addAttribute($myLanes);

        $objObject = $this->object->getObject();
        $myObject = new ZendExt_WF_WFTranslatorXPDL_Clases_Object($objObject);
        $this->addAttribute($myObject);
        
        $objNodeGraphicsInfos = $this->object->getNodeGraphicsInfos();
        $myNodeGraphicsInfos = new ZendExt_WF_WFTranslatorXPDL_Clases_NodeGraphicsInfos($objNodeGraphicsInfos);
        $this->addAttribute($myNodeGraphicsInfos);        
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Pool");

        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Name', $this->object->getName());
        $thisObjectTag->setAttribute('Orientation', $this->object->getOrientation());
        $thisObjectTag->setAttribute('Process', $this->object->getProcess());
        $thisObjectTag->setAttribute('Participant', $this->object->getParticipant());
        $thisObjectTag->setAttribute('BoundaryVisible', $this->object->getBoundaryVisible());
        $thisObjectTag->setAttribute('MainPool', $this->object->getMainPool());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>