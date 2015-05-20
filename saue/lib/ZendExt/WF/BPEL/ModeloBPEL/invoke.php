<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of invoke
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_invoke extends ZendExt_WF_BPEL_ModeloBPEL_tActivity{
    //put your code here
    public function __construct($parent) {
        parent::__construct($parent, 'invoke');
    }
    
    /*
     *     +
     
	<xsd:element name="correlations" type="tCorrelationsWithPattern" minOccurs="0"/>
	<xsd:element ref="catch" minOccurs="0" maxOccurs="unbounded"/>
	<xsd:element ref="catchAll" minOccurs="0"/>
	<xsd:element ref="compensationHandler" minOccurs="0"/>
	<xsd:element ref="toParts" minOccurs="0"/>
	<xsd:element ref="fromParts" minOccurs="0"/>    
     */
    public function fillStructure() {
        parent::fillStructure();
        
        /*
         * missing:
         * <xsd:element name="correlations" type="tCorrelationsWithPattern" minOccurs="0"/>
         */
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_catch($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_catchAll($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_compensationHandler($this));
        /*
         * missing:
         * <xsd:element ref="toParts" minOccurs="0"/>
         */        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_fromParts($this));
    }
}

?>
