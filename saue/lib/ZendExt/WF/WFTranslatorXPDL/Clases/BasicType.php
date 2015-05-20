<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_BasicType extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        if($this->object instanceof ZendExt_WF_WFObject_Entidades_BasicType){
            $length = $this->object->getLength();
            $precision = $this->object->getPrecision();             
            $scale = $this->object->getScale();
            
            $_length = new ZendExt_WF_WFTranslatorXPDL_Clases_Length($length);
            $_precision = new ZendExt_WF_WFTranslatorXPDL_Clases_Precision($precision);
            $_scale = new ZendExt_WF_WFTranslatorXPDL_Clases_Scale($scale);
            
            $this->addAttribute($_length);
            $this->addAttribute($_precision);
            $this->addAttribute($_scale);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("BasicType");
        $thisObjectTag->setAttribute('Type', $this->object->getType());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
