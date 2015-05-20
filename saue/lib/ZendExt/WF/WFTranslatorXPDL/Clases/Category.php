<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Category extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;        
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Category");

        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Name', $this->object->getName());

        /*
         * no debe tener nada... verificamos de todos modos.
         */
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>

