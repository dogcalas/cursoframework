
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TypeDeclarations extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objTypeDeclaration = $this->object->get($i);
            $myTypeDeclaration = new ZendExt_WF_WFTranslatorXPDL_Clases_TypeDeclaration($objTypeDeclaration);
            $this->addAttribute($myTypeDeclaration);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TypeDeclarations");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>