<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_ExternalPackage extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objExtendedAttributes = $this->object->getExtendedAttributes();
        $myExtendedAttributes = new ZendExt_WF_WFTranslatorXPDL_Clases_ExtendedAttributes($objExtendedAttributes);
        $this->addAttribute($myExtendedAttributes);
    }

    /* public function fromXPDL($objectTag,$myclase)                 


      } */

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("ExternalPackage");
        $thisObjectTag->setAttribute('href', $this->object->gethref());
        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Name', $this->object->getName());
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
