<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of query
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_query extends ZendExt_WF_BPEL_Base_SimpleElement{
    //put your code here
    private $queryLanguage;
    
    public function __construct($parent) {
        parent::__construct($parent, 'query');
    }
    
    public function clonar() {
        return;
    }
    
    public function initAttributes() {
        $this->queryLanguage = new ZendExt_WF_BPEL_Base_attribute(null, 'queryLanguage', false, null, 'string');
    }
}

?>
