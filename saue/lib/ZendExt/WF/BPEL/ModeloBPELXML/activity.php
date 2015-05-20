<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Activity
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPELXML_activity extends ZendExt_WF_BPEL_Base_baseXML{
  
    public function __construct($bpelObject, $desassembleObject = FALSE) {
        parent::__construct($bpelObject, $desassembleObject);
    }
    
    public function toXML(DOMDocument $domDocument, $domElement) {
        $counter = count($this->attributes);
        for ($i = 0; $i < $counter; $i++) {
            $this->attributes[$i]->toXML($domDocument, $domElement);
        }
    }
}

?>
