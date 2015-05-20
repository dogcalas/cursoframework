<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of faultHandlers
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_faultHandlers extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements{
    //put your code here
    public function __construct($parent) {
        parent::__construct($parent, 'faultHandlers');
    }
    
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_catch($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_catchAll($this));
    }
}

?>
