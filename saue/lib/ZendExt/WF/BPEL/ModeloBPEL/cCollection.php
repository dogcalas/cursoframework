<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cCollection
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_cCollection extends ZendExt_WF_BPEL_Base_Collections{
    //put your code here
    public function __construct($parent, $tagName = null) {
        parent::__construct($parent, $tagName);
    }
    public function clonar() {
        return;
    }
    public function createObject() {
        $elementCreate = substr($this->tagName, 0, strlen($this->tagName) - 1);
        $classPreffix = 'ZendExt_WF_BPEL_ModeloBPEL_';
        $className = $classPreffix.$elementCreate;
        return new $className($this);
    }
}

?>
