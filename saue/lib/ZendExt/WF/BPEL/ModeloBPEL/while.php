<?php

class ZendExt_WF_BPEL_ModeloBPEL_while extends ZendExt_WF_BPEL_ModeloBPEL_tActivity {

    public function __construct($parent) {
        parent::__construct($parent,'while');
    }
    
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_condition($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_activities($this));
    }

}

?>