
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_ExtendedAttributes extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objExtendedAttribute = $this->object->get($i);
            $myExtendedAttribute = new ZendExt_WF_WFTranslatorXPDL_Clases_ExtendedAttribute($objExtendedAttribute);
            $this->addAttribute($myExtendedAttribute);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("ExtendedAttributes");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
