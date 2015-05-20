<?php

Class ZendExt_WF_WFTranslatorXPDL_Clases_NestedLane extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    public function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("NestedLane");

        $thisObjectTag->setattribute("LaneId", $this->object->getLaneId());
        $objectTag->appendChild($thisObjectTag);
    }

}

?>
