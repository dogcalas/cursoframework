
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Assignments extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objAssignment = $this->object->get($i);
            $myAssignment = new ZendExt_WF_WFTranslatorXPDL_Clases_Assignment($objAssignment);
            $this->addAttribute($myAssignment);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Assignments");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>