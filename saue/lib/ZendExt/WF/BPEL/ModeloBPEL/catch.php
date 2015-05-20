<?php

class ZendExt_WF_BPEL_ModeloBPEL_catch extends ZendExt_WF_BPEL_ModeloBPEL_tActivityContainer {

    /*
	<xsd:attribute name="faultName" type="xsd:QName"/>
	<xsd:attribute name="faultVariable" type="BPELVariableName"/>
	<xsd:attribute name="faultMessageType" type="xsd:QName"/>
	<xsd:attribute name="faultElement" type="xsd:QName"/>     
     */
    protected $faultName;
    protected $faultVariable;
    private $faultMessageType;
    private $faultElement;

    public function __construct($parent) {
        parent::__construct($parent, 'catch');
    }

    public function getfaultName() {
        return $this->faultName->getValue();
    }

    public function getfaultVariable() {
        return $this->faultVariable->getValue();
    }
    
    public function getfaultMessageType() {
        return $this->faultMessageType->getValue();
    }

    public function getfaultElement() {
        return $this->faultElement->getValue();
    }  
    
    public function initAttributes() {
        $this->faultName = new ZendExt_WF_BPEL_Base_attribute($this, 'faultName', false, null, 'string');
        $this->faultVariable = new ZendExt_WF_BPEL_Base_attribute($this, 'faultVariable', false, null, 'string');        
        $this->faultMessageType = new ZendExt_WF_BPEL_Base_attribute($this, 'faultMessageType', false, null, 'string');
        $this->faultElement = new ZendExt_WF_BPEL_Base_attribute($this, 'faultElement', false, null, 'string');        
    }

}

?>