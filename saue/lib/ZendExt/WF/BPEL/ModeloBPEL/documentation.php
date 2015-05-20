<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of documentation
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_documentation extends ZendExt_WF_BPEL_Base_SimpleElement{
    //put your code here
    public function __construct($parent) {
        parent::__construct($parent, 'documentation');
    }
    
    public function clonar() {
        return;
    }
    
    public function isEmpty() {        
        return empty($this->value);
    }
}

?>
