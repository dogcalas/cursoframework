<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tExtensibleElements
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements extends ZendExt_WF_BPEL_Base_Complex {

    //put your code here
    public function __construct($parent, $tagName = null) {
        parent::__construct($parent, $tagName);
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_documentation($this));
    }

    public function clonar() {
        return;
    }

}

?>
