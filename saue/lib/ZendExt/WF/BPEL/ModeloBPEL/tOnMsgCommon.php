<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tOnMsgCommon
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_tOnMsgCommon extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements {

    //put your code here
    /*
	<xsd:attribute name="partnerLink" type="xsd:NCName" use="required"/>
	<xsd:attribute name="portType" type="xsd:QName" use="optional"/>
	<xsd:attribute name="operation" type="xsd:NCName" use="required"/>
	<xsd:attribute name="messageExchange" type="xsd:NCName" use="optional"/>
	<xsd:attribute name="variable" type="BPELVariableName" use="optional"/>
     */
    private $partnerLink;
    private $portType;
    private $operation;
    private $messageExchange;
    private $variable;

    public function __construct($parent, $tagName = null) {
        $tagName = $tagName === null ? 'tOnMsgCommon' : $tagName;
        parent::__construct($parent, $tagName);
    }

    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_correlations($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_fromParts($this));
    }

    public function initAttributes() {
        $this->partnerLink = new ZendExt_WF_BPEL_Base_attribute($this, 'partnerLink', true, null, 'string');
        $this->portType = new ZendExt_WF_BPEL_Base_attribute($this, 'portType', false, null, 'string');        
        $this->operation = new ZendExt_WF_BPEL_Base_attribute($this, 'operation', true, null, 'string');
        $this->messageExchange = new ZendExt_WF_BPEL_Base_attribute($this, 'messageExchange', false, null, 'string');
        $this->variable = new ZendExt_WF_BPEL_Base_attribute($this, 'variable', false, null, 'string');        
    }

}

?>
