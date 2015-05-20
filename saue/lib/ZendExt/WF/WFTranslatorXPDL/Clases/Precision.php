<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Precision
 *
 * @author yriverog
 */
class ZendExt_WF_WFTranslatorXPDL_Clases_Precision extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    public function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Precision");
        $thisObjectTag->appendChild($doc->createTextNode($this->object->getPrecision()));

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
