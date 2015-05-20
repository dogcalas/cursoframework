<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of links
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_links extends ZendExt_WF_BPEL_Base_Collections {

    //put your code here
    public function __construct($parent) {
        parent::__construct($parent, 'links');
    }

    public function clonar() {
        
    }

    public function createObject() {
        return new ZendExt_WF_BPEL_ModeloBPEL_link($this);
    }

}

?>
