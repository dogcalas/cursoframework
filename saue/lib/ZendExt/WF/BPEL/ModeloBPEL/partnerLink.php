<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of partnerLink
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_partnerLink extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements {

    //put your code here
    /*
    <xsd:attribute name="name" type="xsd:NCName" use="required"/>
    <xsd:attribute name="partnerLinkType" type="xsd:QName" use="required"/>
    <xsd:attribute name="myRole" type="xsd:NCName"/>
    <xsd:attribute name="partnerRole" type="xsd:NCName"/>
    <xsd:attribute name="initializePartnerRole" type="tBoolean"/>     
     */
    private $name;
    private $partnerLinkType;
    private $myRole;
    private $partnerRole;
    private $initializePartnerRole;

    public function __construct($parent) {
        parent::__construct($parent, 'partnerLink');
    }

    public function initAttributes() {
        $this->name = new ZendExt_WF_BPEL_Base_attribute($this, 'name', false, null, 'string');
        $this->partnerLinkType = new ZendExt_WF_BPEL_Base_attribute($this, 'partnerLinkType', true, null, 'string');
        $this->myRole = new ZendExt_WF_BPEL_Base_attribute($this, 'myRole', false, null, 'string');
        $this->partnerRole = new ZendExt_WF_BPEL_Base_attribute($this, 'partnerRole', false, null, 'string');
        $this->initializePartnerRole = new ZendExt_WF_BPEL_Base_attribute($this, 'initializePartnerRole', false, null, 'boolean');
    }

}

?>
