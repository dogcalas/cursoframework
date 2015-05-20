<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_ConnectorGraphicsInfo extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objCoordinates = $this->object->getCoordinates();
        $myCoordinates = new ZendExt_WF_WFTranslatorXPDL_Clases_Coordinates($objCoordinates);
        $this->addAttribute($myCoordinates);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("ConnectorGraphicsInfo");

        $thisObjectTag->setAttribute('ToolId', $this->object->getToolId());
        $thisObjectTag->setAttribute('IsVisible', $this->object->getIsVisible());
        $thisObjectTag->setAttribute('Page', $this->object->getPage());
        $thisObjectTag->setAttribute('PageId', $this->object->getPageId());
        $thisObjectTag->setAttribute('Style', $this->object->getStyle());
        $thisObjectTag->setAttribute('BorderColor', $this->object->getBorderColor());
        $thisObjectTag->setAttribute('FillColor', $this->object->getFillColor());


        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
