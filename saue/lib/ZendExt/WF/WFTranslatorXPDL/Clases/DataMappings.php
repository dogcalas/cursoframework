<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_DataMappings extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {            
            $objDataMapping = $this->object->get($i);
            $myDataMapping = new ZendExt_WF_WFTranslatorXPDL_Clases_DataMapping($objDataMapping);
            $this->addAttribute($myDataMapping);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("DataMappings");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>

