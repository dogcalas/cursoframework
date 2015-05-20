<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_VendorExtensions extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objVendorExtension = $this->object->get($i);
            $myVendorExtension = new ZendExt_WF_WFTranslatorXPDL_Clases_VendorExtension($objVendorExtension);
            $this->addAttribute($myVendorExtension);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("VendorExtensions");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
