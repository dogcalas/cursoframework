<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of messageExchanges
 *
 * @author lhia
 */

//revisar partnerlinks
class ZendExt_WF_BPEL_ModeloBPEL_messageExchanges extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements{
    //put your code here
    public function __construct($parent) {
        parent::__construct($parent, 'messageExchanges');
    }
    public function clonar() {
        return;
    }
    public function fillStructure() {
        parent::fillStructure();
        
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_cCollection($this, 'messageExchange'));
    }
    
    public function getMessageExchanged(){
        return $this->get('messageExchanges');
    }
}

?>
