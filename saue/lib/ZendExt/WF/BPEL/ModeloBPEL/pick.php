<?php

class ZendExt_WF_BPEL_ModeloBPEL_pick extends ZendExt_WF_BPEL_ModeloBPEL_tActivity {

    /*
     * <xsd:attribute name="createInstance" type="tBoolean" default="no"/>
     */
    private $createInstance;
    
    public function __construct($parent) {
        parent::__construct($parent,'pick');
    }
    
    public function fillStructure(){
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_onMessage($this));
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_onAlarm($this));
    }

    public function initAttributes(){
        $this->createInstance = new ZendExt_WF_BPEL_Base_attribute(null,'createInstance', false, null, 'boolean');
    }
    
}

?>