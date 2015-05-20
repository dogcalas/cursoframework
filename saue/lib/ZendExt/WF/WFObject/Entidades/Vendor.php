<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Vendor
 *
 * @author sting
 */
class ZendExt_WF_WFObject_Entidades_Vendor extends ZendExt_WF_WFObject_Base_SimpleElement {

    //put your code here    
    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Vendor";
    }

    public function clonar() {
        $c = new ZendExt_WF_WFObject_Simple_Vendor();
        $c->setValue($this->value);
        return $c;
    }

    public function setVendor($_vendor) {
        $this->setValue($_vendor);
    }

    public function getVendor() {
        return $this->getValue();
    }

    public function toArray() {
        $array = array(
            'Vendor' => $this->getvendor()
        );
        return $array;
    }

}

?>
