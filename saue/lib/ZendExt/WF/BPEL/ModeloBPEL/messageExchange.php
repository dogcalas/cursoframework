<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of messageExchange
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_messageExchange extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements{
    //put your code here
    /*
        <xsd:attribute name="name" type="xsd:NCName" use="required"/>     
     */
    private $name;
    public function __construct($parent) {
        parent::__construct($parent, 'messageExchange');
    }
    
    public function initAttributes() {
        $this->name = new ZendExt_WF_BPEL_Base_attribute($this, 'name', true, null, 'boolean');
    }
}

?>
