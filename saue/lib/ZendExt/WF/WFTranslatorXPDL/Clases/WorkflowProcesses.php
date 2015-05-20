<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_WorkflowProcesses extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        for ($i = 0; $i < $this->object->count(); $i++) {
            $objWorkflowProcess = $this->object->get($i);
            $myWorkflowProcess = new ZendExt_WF_WFTranslatorXPDL_Clases_WorkflowProcess($objWorkflowProcess);
            $this->addAttribute($myWorkflowProcess);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("WorkflowProcesses");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
