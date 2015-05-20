<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Pojo extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

   
    function desassembleClass() {
        $objMethod = $this->object->getMethod();
        $myMethod = new ZendExt_WF_WFTranslatorXPDL_Clases_Method($objMethod);
        $this->addAttribute($myMethod);

        $objClass = $this->object->getClass();
        $myClass = new ZendExt_WF_WFTranslatorXPDL_Clases_Class($objClass);
        $this->addAttribute($myClass);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Pojo");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>

