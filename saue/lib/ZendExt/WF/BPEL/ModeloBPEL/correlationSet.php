<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of correlationSet
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_correlationSet extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements {

    //put your code here
    /*
      <xsd:attribute name="properties" type="QNames" use="required"/>
      <xsd:attribute name="name" type="xsd:NCName" use="required"/>
     */
    public function __construct($parent) {
        parent::__construct($parent, 'correlationSet');
    }

    public function initAttributes() {
        $this->properties = new ZendExt_WF_BPEL_Base_attribute($this, 'properties', true, null, 'string');
        $this->name = new ZendExt_WF_BPEL_Base_attribute($this, 'name', true, null, 'boolean', false);
    }

}

?>
