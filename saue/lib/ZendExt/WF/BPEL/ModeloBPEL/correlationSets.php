<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of correlationSets
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_correlationSets extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements{
    //put your code here
    public function __construct($parent) {
        parent::__construct($parent, 'correlationSets');
    }
    
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_cCollection($this, 'correlationSets'));
    }
    
    public function getCorrelationSets() {
        return $this->get('correlationSets');
    }
}

?>
