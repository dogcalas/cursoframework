<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of elseif
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_elseif extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements{
    //put your code here
    public function __construct($parent) {
        parent::__construct($parent, 'elseif');
    }
    
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_condition($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_activity($this));
    }
}
?>
