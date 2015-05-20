<?php

class ZendExt_WF_BPEL_ModeloBPEL_throw extends ZendExt_WF_BPEL_ModeloBPEL_tActivity {

    protected $faultName;
    protected $faultVariable;

    public function __construct($parent) {
        parent::__construct($parent, 'throw');
    }

    public function getfaultName() {
        return $this->faultName;
    }

    public function getfaultVariable() {
        return $this->faultVariable;
    }
    
    public function initAttributes() {
        $this->faultName = new ZendExt_WF_BPEL_Base_attribute($this,'faultName', true, null, 'string');
        $this->faultVariable = new ZendExt_WF_BPEL_Base_attribute($this, 'faultVariable',false, null, 'string');        
    }

}

?>