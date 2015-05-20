
<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Output extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object,$bool) {
        parent::__construct($object,$bool);
    }

   
    function desassembleClass() {
        $bool=TRUE;
        
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Output");

        $thisObjectTag->setAttribute('ArtifactId', 'falta  capturar ArtifactId'/* $this->object->getValue() */);
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>