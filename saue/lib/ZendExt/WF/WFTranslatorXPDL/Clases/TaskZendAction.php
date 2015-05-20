<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ZendAction
 *
 * @author yriverog
 */
class ZendExt_WF_WFTranslatorXPDL_Clases_TaskZendAction extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    //put your code here
    function __construct($object, $dessasemble = TRUE) {
        parent::__construct($object,$dessasemble);
    }

    public function desassembleClass() {
        $objVariables = $this->object->getVariables();
        $myVariables = new ZendExt_WF_WFTranslatorXPDL_Clases_Variables($objVariables);
        $this->addAttribute($myVariables);        
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TaskZendAction");
        $thisObjectTag->setAttribute('ZendAction', $this->object->getZendAction());
        $thisObjectTag->setAttribute('SystemId', $this->object->getSystemId());
        $thisObjectTag->setAttribute('Controller', $this->object->getController());
        $thisObjectTag->setAttribute('Action', $this->object->getAction());
        $thisObjectTag->setAttribute('IdRol', $this->object->getIdRol());
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }
        $objectTag->appendChild($thisObjectTag);        
    }

}

?>
