<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of variable
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_variable extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements{
    //put your code here
    /*
	<xsd:attribute name="name" type="BPELVariableName" use="required"/>
	<xsd:attribute name="messageType" type="xsd:QName" use="optional"/>
	<xsd:attribute name="type" type="xsd:QName" use="optional"/>
	<xsd:attribute name="element" type="xsd:QName" use="optional"/>     
     */ 
    private $name;
    private $messageType;
    private $type;
    private $element;

    public function __construct($parent) {
        parent::__construct($parent, 'variable');
    }
    
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_from($this));
    }
    
    public function initAttributes() {
        $this->name = new ZendExt_WF_BPEL_Base_attribute($this, 'name', false, null, 'string');        
        $this->messageType = new ZendExt_WF_BPEL_Base_attribute($this, 'messageType', false, null, 'string');        
        $this->type = new ZendExt_WF_BPEL_Base_attribute($this, 'type', false, null, 'string');        
        $this->element = new ZendExt_WF_BPEL_Base_attribute($this, 'element', false, null, 'string');        
    }
}

?>
