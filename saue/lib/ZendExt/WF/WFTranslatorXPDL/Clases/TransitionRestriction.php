<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TransitionRestriction extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $isNullJoin = $this->object->isNullJoin();
        $isNullSplit = $this->object->isNullSplit();

        if($isNullJoin === FALSE){
            $objJoin = $this->object->getJoin();
            $myJoin = new ZendExt_WF_WFTranslatorXPDL_Clases_Join($objJoin);        
            $this->addAttribute($myJoin);
        }
        if($isNullSplit === FALSE){
            $objSplit = $this->object->getSplit();
            $mySplit = new ZendExt_WF_WFTranslatorXPDL_Clases_Split($objSplit);        
            $this->addAttribute($mySplit);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TransitionRestriction");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
