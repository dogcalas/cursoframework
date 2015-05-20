<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of to
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_to extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements{
    //put your code here
    /*
	<xsd:attribute name="expressionLanguage" type="xsd:anyURI"/>
	<xsd:attribute name="variable" type="BPELVariableName"/>
	<xsd:attribute name="part" type="xsd:NCName"/>
	<xsd:attribute name="property" type="xsd:QName"/>
	<xsd:attribute name="partnerLink" type="xsd:NCName"/>     
     */
    private $expressionLanguage;
    private $variable;
    private $part;
    private $property;
    private $partnerLink;
    
    public function __construct($parent) {
        parent::__construct($parent, 'to');
    }
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_query($this));
    }
    public function initAttributes() {
        $this->expressionLanguage = new ZendExt_WF_BPEL_Base_attribute($this, 'expressionLanguage', false, null, 'string');
        $this->variable = new ZendExt_WF_BPEL_Base_attribute($this, 'variable', false, null, 'string');
        $this->part = new ZendExt_WF_BPEL_Base_attribute($this, 'part', false, null, 'string');
        $this->property = new ZendExt_WF_BPEL_Base_attribute($this, 'property', false, null, 'string');
        $this->partnerLink = new ZendExt_WF_BPEL_Base_attribute($this, 'partnerLink', false, null, 'string');
    }
}

?>
