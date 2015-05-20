<?php

class ZendExt_WF_BPEL_ModeloBPEL_repeatUntil extends ZendExt_WF_BPEL_ModeloBPEL_tActivity {

    public function __construct($parent) {
        parent::__construct($parent,'repeatUntil');
    }

    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_activity($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_condition($this));
    }
}

?>