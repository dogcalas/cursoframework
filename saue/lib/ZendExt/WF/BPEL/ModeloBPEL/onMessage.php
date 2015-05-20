<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of onMessage
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_onMessage extends ZendExt_WF_BPEL_ModeloBPEL_tOnMsgCommon{
    //put your code here
    public function __construct($parent) {
        parent::__construct($parent, 'onMessage');
    }
    
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_activities($this));
    }
}

?>
