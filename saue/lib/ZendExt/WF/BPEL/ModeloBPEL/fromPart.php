<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of fromPart
 *
 * @author lhia
 */
/*
 * 		
 <xsd:complexContent>
  <xsd:extension base="tExtensibleElements">
    <xsd:attribute name="part" type="xsd:NCName" use="required"/>
    <xsd:attribute name="toVariable" type="BPELVariableName" use="required"/>
  </xsd:extension>
 </xsd:complexContent>
 */
class ZendExt_WF_BPEL_ModeloBPEL_fromPart extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements{
    //put your code here
    private $part;
    private $toVariable;
    
    public function __construct($parent) {
        parent::__construct($parent, 'fromPart');
    }
    
    public function clonar() {
        return;
    }
    
    public function initAttributes() {
        $this->part = new ZendExt_WF_BPEL_Base_attribute($this, 'part', true, null, 'string');
        $this->toVariable = new ZendExt_WF_BPEL_Base_attribute($this,'toVariable', true, null, 'string');
    }
}

?>
