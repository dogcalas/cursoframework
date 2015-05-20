
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Associations extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objAssociation = $this->object->get($i);
            $myAssociation = new ZendExt_WF_WFTranslatorXPDL_Clases_Association($objAssociation);
            $this->addAttribute($myAssociation);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Associations");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>