<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_VariableSalida extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("VariableSalida");
        //$thisObjectTag->setAttribute('Sistema', $this->object->getSistema());
        $thisObjectTag->setAttribute('Objeto', $this->object->getObjeto());
        $thisObjectTag->setAttribute('Campo', $this->object->getCampo());
        $thisObjectTag->setAttribute('Asociado', $this->object->getAsociado());
        $thisObjectTag->setAttribute('Tipo', $this->object->getTipo());
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
	