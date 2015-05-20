
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Responsibles extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    public function __construct($object) {

        parent::__construct($object);
    }

    function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objResponsible = $this->object->get($i);
            $myResponsible = new ZendExt_WF_WFTranslatorXPDL_Clases_Responsible($objResponsible);
            $this->addAttribute($myResponsible);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Responsibles");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>