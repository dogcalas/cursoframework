<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Ejb extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

   

    function desassembleClass() {
        $objJndiName = $this->object->getJndiName();
        $myJndiName = new ZendExt_WF_WFTranslatorXPDL_Clases_JndiName($objJndiName);
        $this->addAttribute($myJndiName);
        
        $objHomeClass = $this->object->getHomeClass();
        $myHomeClass = new ZendExt_WF_WFTranslatorXPDL_Clases_HomeClass($objHomeClass);
        $this->addAttribute($myHomeClass);
        
        $objMethod = $this->object->getMethod();
        $myMethod = new ZendExt_WF_WFTranslatorXPDL_Clases_Method($objMethod);
        $this->addAttribute($myMethod);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Ejb");
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>

