<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_FormLayout extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        /*
         * <xsd:anyAttribute namespace="##other" processContents="lax"/>
         */
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("FormLayout");

        /*
         * <xsd:anyAttribute namespace="##other" processContents="lax"/>
         */
        //Never happens
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>

