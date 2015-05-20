<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Variables extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object, $dessasemble) {
        parent::__construct($object, $dessasemble);
    }

    function desassembleClass() {
        print_r('en ZendExt_WF_WFTranslatorXPDL_Clases_Variables I died');die;
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objTypeVariables = $this->object->get($i);

            if ($objTypeVariables instanceof ZendExt_WF_WFObject_Entidades_VariableEntrada) {
                $myVariableEntrada = new ZendExt_WF_WFTranslatorXPDL_Clases_VariableEntrada($objTypeVariables);
                $this->addAttribute($myVariableEntrada);
            }
            if ($objTypeVariables instanceof ZendExt_WF_WFObject_Entidades_VariableSalida) {
                $myVariableSalida = new ZendExt_WF_WFTranslatorXPDL_Clases_VariableSalida($objTypeVariables);
                $this->addAttribute($myVariableSalida);
            }
        }        
    }

    public function treatCollection($collection, $node) {
        print_r(' ZendExt_WF_WFTranslatorXPDL_Clases_Variables...');die;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Variables");

        //$thisObjectTag->setAttribute('Id', 'falta  capturar Id'/* $this->object->getValue() */);
        //$thisObjectTag->setAttribute('Id_action', 'string'/* $this->object->getValue() */);
        //$thisObjectTag->setAttribute('Id_rol', 'string'/* $this->object->getValue() */);
        //$thisObjectTag->setAttribute('Servicio_dir', 'string'/* $this->object->getValue() */);

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
