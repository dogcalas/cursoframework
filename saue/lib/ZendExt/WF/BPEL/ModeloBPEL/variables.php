<?php

class ZendExt_WF_BPEL_ModeloBPEL_variables extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements {

    public function __construct($parent) {
        parent::__construct($parent, 'variables');
    }
    
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_cCollection($this, 'variables'));
    }
    
    public function getVariables() {
        return $this->get('variables');
    }

}

?>