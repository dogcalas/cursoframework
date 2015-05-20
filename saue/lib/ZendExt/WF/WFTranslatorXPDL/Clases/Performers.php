<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Performers extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objPerformer = $this->object->getPerformer();
        $myPerformer = new ZendExt_WF_WFTranslatorXPDL_Clases_Performer($objPerformer);
        $this->addAttribute($myPerformer);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Performers");
        
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?> 
