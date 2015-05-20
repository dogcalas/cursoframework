<?php

class ZendExt_WF_BPEL_ModeloBPEL_eventHandlers extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements {

    public function __construct($parent) {
        parent::__construct($parent, 'eventHandlers');
    }
    
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_onEvent($this));
        //$this->add(new ZendExt_WF_BPEL_ModeloBPEL_onAlarm($this));        
    }

}

?>