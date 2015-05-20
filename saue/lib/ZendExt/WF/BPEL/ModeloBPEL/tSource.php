<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tSource
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_tSource extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements {

    //put your code here
    public function __construct($parent) {
        parent::__construct($parent, 'source');
    }

    public function fillStructure() {
        parent::fillStructure();
    }

    public function clonar() {
        return;
    }

}

?>
