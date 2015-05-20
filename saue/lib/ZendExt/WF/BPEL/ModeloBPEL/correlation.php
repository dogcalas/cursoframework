<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of correlation
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_correlation extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements {
    //put your code here
    private $set;
    private $initiate;
    
    public function __construct($parent) {
        parent::__construct($parent, 'correlation');
    }
    
    public function initAttributes() {
        $this->set = new ZendExt_WF_BPEL_Base_attribute($this, 'set',$isRequired = false, null , 'string');
        $this->initiate = new ZendExt_WF_BPEL_Base_attribute($this, 'initiate',$isRequired = false, 'no' , 'string');
    }
}

?>
