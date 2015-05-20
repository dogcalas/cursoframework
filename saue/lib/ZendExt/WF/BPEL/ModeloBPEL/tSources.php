<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tSources
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_tSources extends ZendExt_WF_BPEL_Base_Collections {

    //put your code here
    public function __construct($parent, $tagName = null) {
        if ($tagName === null) {
            $tagName = 'targets';
        }
        parent::__construct($parent, $tagName);
    }

    public function clonar() {
        return;
    }

    public function createObject() {
        return;
    }

}

?>
