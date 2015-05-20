<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of receive
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_receive extends ZendExt_WF_BPEL_ModeloBPEL_tActivity {

    //put your code here
    
    /* /  ----  attributes  -----/ */
    /*
      <xsd:attribute name="partnerLink" type="xsd:NCName" use="required"/>
      <xsd:attribute name="portType" type="xsd:QName" use="optional"/>
      <xsd:attribute name="operation" type="xsd:NCName" use="required"/>
      <xsd:attribute name="variable" type="BPELVariableName" use="optional"/>
      <xsd:attribute name="createInstance" type="tBoolean" default="no"/>
      <xsd:attribute name="messageExchange" type="xsd:NCName" use="optional"/>
     */
    private $partnerLink;
    private $portType;
    private $operation;
    private $variable;
    private $createInstance;
    private $messageExchange;

    public function __construct($parent) {
        parent::__construct($parent, 'receive');
    }

    public function fillStructure() {
        parent::fillStructure();

        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_correlations($this));
        /*
         * Missing:
         * 
            <xsd:element ref="toParts" minOccurs="0"/>         
         */
    }

    public function clonar() {
        return;
    }

    public function initAttributes() {
        $this->partnerLink = new ZendExt_WF_BPEL_Base_attribute($this,'partnerLink', true, null, 'string');
        $this->portType = new ZendExt_WF_BPEL_Base_attribute($this, 'portType',false, null, 'string');
        $this->operation = new ZendExt_WF_BPEL_Base_attribute($this, 'operation', true, null, 'string');
        $this->variable = new ZendExt_WF_BPEL_Base_attribute($this, 'variable',false, null, 'string');
        $this->createInstance = new ZendExt_WF_BPEL_Base_attribute($this, 'createInstance',false, null, 'bool', 'false');
        $this->messageExchange = new ZendExt_WF_BPEL_Base_attribute($this,'messageExchange', false, null, 'string');
    }

}

?>
