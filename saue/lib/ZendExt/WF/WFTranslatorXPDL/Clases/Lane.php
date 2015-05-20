<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Lane extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $objNodeGraphicsInfos = $this->object->getNodeGraphicsInfos();
        $myNodeGraphicsInfos = new ZendExt_WF_WFTranslatorXPDL_Clases_NodeGraphicsInfos($objNodeGraphicsInfos);
        $this->addAttribute($myNodeGraphicsInfos);

        $objObject = $this->object->getObject();
        $myObject = new ZendExt_WF_WFTranslatorXPDL_Clases_Object($objObject);
        $this->addAttribute($myObject);


        $objPerformers = $this->object->getPerformers();
        $myPerformers = new ZendExt_WF_WFTranslatorXPDL_Clases_Performers($objPerformers);
        $this->addAttribute($myPerformers);

        $objNestedLane = $this->object->getNestedLane();
        $myNestedLane = new ZendExt_WF_WFTranslatorXPDL_Clases_NestedLane($objNestedLane);
        $this->addAttribute($myNestedLane);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Lane");

        $thisObjectTag->setattribute("Id", $this->object->getId());
        $thisObjectTag->setattribute("Name", $this->object->getTagName());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>