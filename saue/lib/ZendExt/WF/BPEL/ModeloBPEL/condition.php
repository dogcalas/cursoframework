<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of condition
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_condition extends ZendExt_WF_BPEL_ModeloBPEL_tExpression {

    //put your code here
    public function __construct($parent, $tagName = null) {
        $tagName = $tagName === null ? 'condition' : $tagName;
        parent::__construct($parent, $tagName);
    }

}

?>
