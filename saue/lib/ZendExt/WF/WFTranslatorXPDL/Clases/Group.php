<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Group extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objCategory = $this->object->getCategory();
        $myCategory = new ZendExt_WF_WFTranslatorXPDL_Clases_Category($objCategory);
        $this->addAttribute($myCategory);

        $objObject = $this->object->getObject();
        $myObject = new ZendExt_WF_WFTranslatorXPDL_Clases_Object($objObject);
        $this->addAttribute($myObject);

        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Group");
        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Name', $this->object->getName());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>