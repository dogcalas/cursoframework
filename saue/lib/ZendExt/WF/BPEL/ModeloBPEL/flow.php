<?php

class ZendExt_WF_BPEL_ModeloBPEL_flow extends ZendExt_WF_BPEL_ModeloBPEL_tActivity {

    public function __construct($parent) {
        parent::__construct($parent,'flow');
    }
    
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_links($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_activities($this));
    }
    
    public function getActivities() {
        return $this->get('activities');
    }

}

?>