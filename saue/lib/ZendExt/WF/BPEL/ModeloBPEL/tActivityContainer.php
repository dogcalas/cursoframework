<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tActivityContainer
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_tActivityContainer extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements{
    //put your code here
    public function __construct($parent, $tagName = null) {
        parent::__construct($parent, $tagName);
    }
    
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_activities($this));
    }
    
    public function getActivities() {
        return $this->get('activities');
    }
}

?>
