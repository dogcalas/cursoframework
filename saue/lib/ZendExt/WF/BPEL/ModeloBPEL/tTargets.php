<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tTargets
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_tTargets extends ZendExt_WF_BPEL_Base_Collections {

    //put your code here
    public function __construct($parent, $tagName = null) {
        if ($tagName === null) {
            $tagName = 'targets';
        }
        parent::__construct($parent, $tagName);
    }

    public function createObject() {
        return;
    }

    public function clonar() {
        return;
    }

}

?>
