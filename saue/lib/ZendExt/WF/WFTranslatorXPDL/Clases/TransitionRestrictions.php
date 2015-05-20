<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TransitionRestrictions extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objTransitionRestriction = $this->object->get($i);
            $myTransitionRestriction = new ZendExt_WF_WFTranslatorXPDL_Clases_TransitionRestriction($objTransitionRestriction);
            $this->addAttribute($myTransitionRestriction);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TransitionRestrictions");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }
    
    public function fromXPDL($objectTag) {
        $this->treatCollection($this->object, $objectTag);
    }

}
?> 

