<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataObjectInstance
 *
 * @author yriverog
 */
class ZendExt_WF_WFObject_Entidades_DataObjectInstance extends ZendExt_WF_WFObject_Base_Complex {
    
    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'DataObjectInstance';
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $dataField = new ZendExt_WF_WFObject_Entidades_DataField($this);
        $this->add($dataField);
        
        return;
    }
}

?>
