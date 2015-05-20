<?php

class ZendExt_WF_BPEL_ModeloBPEL_if extends ZendExt_WF_BPEL_ModeloBPEL_tActivity {

    public function __construct($parent) {
        parent::__construct($parent,'if');
    }
    
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_condition($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_activities($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_cCollection($this,'elseifs'));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_else($this));
    }

    public function getElseIfs() {
        return $this->get('elseifs');
    }
    
    public function getCondition() {
        return $this->get('condition');
    }
    
    public function getActivities(){
        return $this->get('activities');
    }
    
    public function getElse() {
        return $this->get('else');
    }
    
    
}

?>