<?php

class ZendExt_WF_BPEL_ModeloBPEL_scope extends ZendExt_WF_BPEL_ModeloBPEL_tActivity {
    /*
	<xsd:attribute name="isolated" type="tBoolean" default="no"/>
	<xsd:attribute name="exitOnStandardFault" type="tBoolean"/>    
     */
    private $isolated;
    private $exitOnStandardFault;
    
    public function __construct($parent) {
        parent::__construct($parent,'forEach');
    }

    
    /*
	<xsd:element ref="partnerLinks" minOccurs="0"/>
	<xsd:element ref="messageExchanges" minOccurs="0"/>
	<xsd:element ref="variables" minOccurs="0"/>
	<xsd:element ref="correlationSets" minOccurs="0"/>
	<xsd:element ref="faultHandlers" minOccurs="0"/>
	<xsd:element ref="compensationHandler" minOccurs="0"/>
	<xsd:element ref="terminationHandler" minOccurs="0"/>
	<xsd:element ref="eventHandlers" minOccurs="0"/>
	<xsd:group ref="activity" minOccurs="1"/>
     */    
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_partnerLinks($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_messageExchanges($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_variables($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_correlationSets($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_faultHandlers($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_compensationHandler($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_terminationHandler($this));
        /*
         * Esta es la ultima que se pone
         */
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_activities($this));
    }
    
    public function initAttributes() {        
        $this->isolated = new ZendExt_WF_BPEL_Base_attribute($this, 'isolated', false, null, 'boolean');
        $this->exitOnStandardFault = new ZendExt_WF_BPEL_Base_attribute($this, 'exitOnStandardFault', false, null, 'boolean', false);
    }

}

?>
