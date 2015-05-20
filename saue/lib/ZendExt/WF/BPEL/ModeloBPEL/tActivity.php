<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tActivity
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_tActivity extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements {

    //put your code here
    private $supressJoinFailure;

    public function __construct($parent, $tagName = null) {
        parent::__construct($parent, $tagName);
    }

    public function fillStructure() {
        parent::fillStructure();

        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_targets($this));
        //$this->add(new ZendExt_WF_BPEL_ModeloBPEL_sources($this));
    }

    public function initAttributes() {
        $this->name = new ZendExt_WF_BPEL_Base_attribute($this, 'name', false, null, 'bool', 'false');
        $this->supressJoinFailure = new ZendExt_WF_BPEL_Base_attribute($this, 'supressJoinFailure', false, null, 'bool', 'false');
    }

}

?>
