<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_MyRole extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("MyRole");
        $thisObjectTag->setAttribute('RoleName', $this->object->getRoleName());
        $thisObjectTag->setAttribute('PortType', $this->object->getPortType());

        /*
         * No itera...
         */
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>