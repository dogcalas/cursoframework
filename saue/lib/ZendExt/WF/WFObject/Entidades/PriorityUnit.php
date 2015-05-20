<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Priority
 *
 * @author sting
 */
class ZendExt_WF_WFObject_Entidades_PriorityUnit extends ZendExt_WF_WFObject_Base_SimpleElement {

    //put your code here    
    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "PriorityUnit";
    }

    public function clonar() {
        return;
    }

    public function setPriorityUnit($_priorityUnit) {
        $this->setValue($_priorityUnit);
    }

    public function getPriorityUnit() {
        return $this->getValue();
    }

    public function toArray() {
        $array = array(
            'PriorityUnit' => $this->getPriorityUnit()
        );
        return $array;
    }

}

?>
