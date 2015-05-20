<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of completionCondition
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_completionCondition extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements{
    //put your code here
    public function __construct($parent) {
        parent::__construct($parent, 'completionCondition');
    }
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_tExpression($this, 'branches'));
    }
    
}

?>
