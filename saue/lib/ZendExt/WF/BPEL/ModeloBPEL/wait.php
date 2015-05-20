<?php

class ZendExt_WF_BPEL_ModeloBPEL_wait extends ZendExt_WF_BPEL_ModeloBPEL_tActivity {

    public function __construct($parent) {
        parent::__construct($parent,'wait');
    }
    
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_Base_cComplexChoice($this, array('for','until'), 'waiting'));
    }
    
}

?>