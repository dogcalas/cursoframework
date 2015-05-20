<?php

class ZendExt_WF_BPEL_ModeloBPEL_forEach extends ZendExt_WF_BPEL_ModeloBPEL_tActivity {
    /*
	<xsd:attribute name="counterName" type="BPELVariableName" use="required"/>
	<xsd:attribute name="parallel" type="tBoolean" use="required"/>     
     */
    private $counterName;
    private $parallel;
    
    public function __construct($parent) {
        parent::__construct($parent,'forEach');
    }
    
    
    /*
	<xsd:element ref="startCounterValue" minOccurs="1"/>
	<xsd:element ref="finalCounterValue" minOccurs="1"/>
	<xsd:element ref="completionCondition" minOccurs="0"/>
	<xsd:element ref="scope" minOccurs="1"/>
     */
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_tExpression($this, 'startCounterValue'));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_tExpression($this, 'finalCounterValue'));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_completionCondition($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_scope($this));
    }
    
    public function initAttributes() {
        $this->counterName = new ZendExt_WF_BPEL_Base_attribute($this, 'counterName', true, null, 'string');
        $this->parallel = new ZendExt_WF_BPEL_Base_attribute($this, 'parallel', true, null, 'boolean');
    }

}

?>
