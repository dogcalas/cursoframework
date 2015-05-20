<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Split extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $objTransitionRefs = $this->object->getTransitionRefs();
        $myTransitionRefs = new ZendExt_WF_WFTranslatorXPDL_Clases_TransitionRefs($objTransitionRefs);
        $this->addAttribute($myTransitionRefs);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Split");
        $thisObjectTag->setAttribute('Type', $this->object->getType());
        $thisObjectTag->setAttribute('ExclusiveType', $this->object->getExclusiveType());
        $thisObjectTag->setAttribute('OutgoingCondition', $this->object->getOutgoingCondition());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>