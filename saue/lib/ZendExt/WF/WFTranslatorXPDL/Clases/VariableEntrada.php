<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_VariableEntrada extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("VariableEntrada");
        
        $thisObjectTag->setAttribute('Actividad', $this->object->getActividad());
        $thisObjectTag->setAttribute('Objeto', $this->object->getObjeto());
        $thisObjectTag->setAttribute('Campo', $this->object->getCampo());
        $thisObjectTag->setAttribute('PostVariable', $this->object->getPostVariable());
        $thisObjectTag->setAttribute('Tipo', $this->object->getTipo());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
	