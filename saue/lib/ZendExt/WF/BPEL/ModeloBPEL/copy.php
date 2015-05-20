<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of copy
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_copy extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements{
    //put your code here
    public function __construct($parent) {
        parent::__construct($parent, 'copy');
    }
    public function fillStructure() {
        parent::fillStructure();
        
        
    }
    public function initAttributes() {
        print_r('impl initAttributes copy modelobpel');
    }
}

?>
