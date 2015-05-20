<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of partnerLinks
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_partnerLinks extends ZendExt_WF_BPEL_Base_Collections {

    //put your code here
    public function __construct($parent) {
        parent::__construct($parent, 'partnerLinks');
    }

    public function clonar() {
        return;
    }

    public function createObject() {
        return new ZendExt_WF_BPEL_ModeloBPEL_partnerLink($this);
    }

}

?>
