<?php

class ZendExt_WF_BPEL_ModeloBPEL_process extends ZendExt_WF_BPEL_Base_Complex {

    private $targetNamespace;
    private $queryLanguage;
    private $expressionLanguage;
    private $suppressJoinFailure;
    private $exitOnStandardFault;

    public function __construct($parent) {
        parent::__construct($parent);
    }
    
    public function fillStructure() {
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_extensions($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_activities($this));
    }
    
    public function getActivities() {
        return $this->get('activities');
    }
    
    public function clonar() {
        return;
    }
    
    /*
	<xsd:attribute name="name" type="xsd:NCName" use="required"/>
	<xsd:attribute name="targetNamespace" type="xsd:anyURI" use="required"/>
	<xsd:attribute name="queryLanguage" type="xsd:anyURI" default="urn:oasis:names:tc:wsbpel:2.0:sublang:xpath1.0"/>
	<xsd:attribute name="expressionLanguage" type="xsd:anyURI" default="urn:oasis:names:tc:wsbpel:2.0:sublang:xpath1.0"/>
	<xsd:attribute name="suppressJoinFailure" type="tBoolean" default="no"/>
	<xsd:attribute name="exitOnStandardFault" type="tBoolean" default="no"/>     
     */
    public function initAttributes() {
        $this->name = new ZendExt_WF_BPEL_Base_attribute($this,'name', true, null, 'string');
        $this->targetNamespace = new ZendExt_WF_BPEL_Base_attribute($this,'targetNamespace', true, null, 'string');
        $this->queryLanguage = new ZendExt_WF_BPEL_Base_attribute($this,'queryLanguage', true, null, 'string');
        $this->expressionLanguage = new ZendExt_WF_BPEL_Base_attribute($this,'expressionLanguage', true, null, 'string');
        $this->suppressJoinFailure = new ZendExt_WF_BPEL_Base_attribute($this,'suppressJoinFailure', true, null, 'bool','false');
        $this->exitOnStandardFault = new ZendExt_WF_BPEL_Base_attribute($this,'exitOnStandardFault', true, null, 'bool','false');
    }

}

?>