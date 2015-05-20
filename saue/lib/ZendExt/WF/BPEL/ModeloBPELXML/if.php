<?php
class ZendExt_WF_BPEL_ModeloBPELXML_if extends ZendExt_WF_BPEL_Base_baseXML
{
    public function __construct($bpelObject, $desassembleObject = FALSE) {
        parent::__construct($bpelObject, $desassembleObject);
    }
    
    public function desassembleObject() {
        $objCondition = $this->bpelObject->getCondition();
        $myCondition = new ZendExt_WF_BPEL_ModeloBPELXML_condition($objCondition);
        $this->addAttributes($myCondition);
        
        $objActivities = $this->bpelObject->getActivities();
        $myCondition = new ZendExt_WF_BPEL_ModeloBPELXML_activities($objActivities);
        $this->addAttributes($myCondition);
        
        $objElseIfs = $this->bpelObject->getElseIfs();
        $myElseIfs = new ZendExt_WF_BPEL_ModeloBPELXML_cCollection($objElseIfs);
        $this->addAttributes($myElseIfs);
        
        $objElse = $this->bpelObject->getElse();
        $myElse = new ZendExt_WF_BPEL_ModeloBPELXML_else($objElse);
        $this->addAttributes($myElse);
    }
} 	
		
?>