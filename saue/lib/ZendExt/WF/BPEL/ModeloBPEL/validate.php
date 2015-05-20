<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of validate
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_validate extends ZendExt_WF_BPEL_ModeloBPEL_tActivity{
    //put your code here
    /*
        <xsd:attribute name="variables" use="required" type="BPELVariableNames"/>     
     */
    private $variables;
    
    public function __construct($parent) {
        parent::__construct($parent, 'validate');
    }
    public function initAttributes() {
        $this->variables = new ZendExt_WF_BPEL_Base_attribute($this, 'variables', true, null, 'string');
    }
}

?>
