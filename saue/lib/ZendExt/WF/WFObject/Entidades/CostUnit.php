<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cost
 *
 * @author sting
 */
class ZendExt_WF_WFObject_Entidades_CostUnit extends ZendExt_WF_WFObject_Base_SimpleElement {

    //put your code here

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "CostUnit";
    }

    public function clonar() {
        return;
    }

    public function setCostUnit($pxpdlVersion) {
        $this->setValue($pxpdlVersion);
    }

    public function getCostUnit() {
        return $this->getValue();
    }

    public function toArray() {
        $array = array(
            'CostUnit' => $this->getCostUnit()
        );
        return $array;
    }

}

?>
