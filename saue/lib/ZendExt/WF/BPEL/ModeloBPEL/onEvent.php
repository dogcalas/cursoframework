<?php

class ZendExt_WF_BPEL_ModeloBPEL_onEvent extends ZendExt_WF_BPEL_ModeloBPEL_tOnMsgCommon {
    
    private $messageType;
    private $element;
    
    public function __construct($parent) {
        parent::__construct($parent);
    }

    public function initAttributes() {
        parent::initAttributes();
        
        $this->messageType = new ZendExt_WF_BPEL_Base_attribute($this, 'messageType', false, null, 'string');
        $this->element = new ZendExt_WF_BPEL_Base_attribute($this, 'element', false, null, 'string');        
    }
}

?>