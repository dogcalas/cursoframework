<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_NodeGraphicsInfo extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $objCoordinates = $this->object->getCoordinates();
        $myCoordinates = new ZendExt_WF_WFTranslatorXPDL_Clases_Coordinates($objCoordinates);
        $this->addAttribute($myCoordinates);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("NodeGraphicsInfo");

        $thisObjectTag->setattribute("ToolId", $this->object->getToolId());
        $thisObjectTag->setattribute("IsVisible", $this->object->getIsVisible());
        $thisObjectTag->setattribute("PageId", $this->object->getPageId());
        $thisObjectTag->setattribute("LaneId", $this->object->getLaneId());
        $thisObjectTag->setattribute("Height", $this->object->getHeight());
        $thisObjectTag->setattribute("Width", $this->object->getWidth());
        $thisObjectTag->setattribute("BorderColor", $this->object->getBorderColor());
        $thisObjectTag->setattribute("FillColor", $this->object->getFillColor());
        $thisObjectTag->setattribute("Shape", $this->object->getShape());


        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>