<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_ConformanceClass extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("ConformanceClass");
        
        $thisObjectTag->setAttribute('GraphConformance', $this->object->getGraphConformance());
        $thisObjectTag->setAttribute('BPMNModelPortabilityConformance', $this->object->getBPMNModelPortabilityConformance());

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
