<?php

class ZendExt_WF_BPEL_ModeloBPEL_sequence extends ZendExt_WF_BPEL_ModeloBPEL_tActivity {

    
    public function __construct($parent, $tagName = null) {
        parent::__construct($parent, 'sequence');
    }
    
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_activities($this));
    }
    
    public function clonar() {
        return;
    }
    
    public function getActivities() {
        return $this->get('activities');
    }

}

?>