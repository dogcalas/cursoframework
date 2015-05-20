<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of compensateScope
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_compensateScope extends ZendExt_WF_BPEL_ModeloBPEL_tActivity{
    //put your code here
    private $target;
    
    public function __construct($parent) {
        parent::__construct($parent, 'compensateScope');
    }
    public function initAttributes() {
        $this->target = new ZendExt_WF_BPEL_Base_attribute($this, 'target');
    }
}

?>
