<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Page extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Page");
        $thisObjectTag->setAttribute('Name', $this->object->getName());
        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Height', $this->object->getHeight());
        $thisObjectTag->setAttribute('Width', $this->object->getWidth());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>