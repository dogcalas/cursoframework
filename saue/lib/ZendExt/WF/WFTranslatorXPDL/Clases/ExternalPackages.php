<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_ExternalPackages extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objExternalPackage = $this->object->get($i);
            $myExternalPackage = new ZendExt_WF_WFTranslatorXPDL_Clases_ExternalPackage($objExternalPackage);
            $this->addAttribute($myExternalPackage);
        }
    }

    /* public function fromXPDL($objectTag,$myclase)                 


      } */

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("ExternalPackages");
        // $thisObjectTag->setAttribute('AssignTime', 'falta  capturar AssignTime'/* $this->object->getValue() */);

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
