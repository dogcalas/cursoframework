<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Object extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objCategories = $this->object->getCategories();
        $myCategories = new ZendExt_WF_WFTranslatorXPDL_Clases_Categories($objCategories);
        $this->addAttribute($myCategories);

        $objDocumentation = $this->object->getDocumentation();
        $myDocumentation = new ZendExt_WF_WFTranslatorXPDL_Clases_Documentation($objDocumentation);
        $this->addAttribute($myDocumentation);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Object");
        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Name', $this->object->getName());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>

