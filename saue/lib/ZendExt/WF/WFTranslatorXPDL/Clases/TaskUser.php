<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TaskUser extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $objPerformers = $this->object->getPerformers();
        $myPerformers = new ZendExt_WF_WFTranslatorXPDL_Clases_Performers($objPerformers);
        $this->addAttribute($myPerformers);

        $objMessageOut = $this->object->getMessageOut();
        $myMessageOut = new ZendExt_WF_WFTranslatorXPDL_Clases_MessageType($objMessageOut);
        $this->addAttribute($myMessageOut);

        $objMessageIn = $this->object->getMessageIn();
        $myMessageIn = new ZendExt_WF_WFTranslatorXPDL_Clases_MessageType($objMessageIn);
        $this->addAttribute($myMessageIn);

        $objWebServiceOperation = $this->object->getWebServiceOperation();
        $myWebServiceOperation = new ZendExt_WF_WFTranslatorXPDL_Clases_WebServiceOperation($objWebServiceOperation);
        $this->addAttribute($myWebServiceOperation);

        /*$objVariables = $this->object->getVariables();
        $myVariables = new ZendExt_WF_WFTranslatorXPDL_Clases_Variables($objVariables);
        $this->addAttribute($myVariables);*/
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TaskUser");

        $thisObjectTag->setAttribute('Implementation', $this->object->getImplementation());
        //$thisObjectTag->setAttribute('IdRol', $this->object->getIdRol());
        //$thisObjectTag->setAttribute('ActionController', $this->object->getActionController());
        $thisObjectTag->setAttribute('Name', $this->object->getName());
        //$thisObjectTag->setAttribute('Identificador', $this->object->getIdentificador());
        //$thisObjectTag->setAttribute('VarIn', $this->object->getVarIn());
        //$thisObjectTag->setAttribute('VarOut', $this->object->getVarOut());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?> 