<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModificationDate
 *
 * @author sting
 */
class ZendExt_WF_WFObject_Entidades_ModificationDate extends ZendExt_WF_WFObject_Base_SimpleElement {

    //put your code here    
    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "ModificationDate";
    }

    public function clonar() {
        $c = new ZendExt_WF_WFObject_Simple_XPDLVersion();
        $c->setValue($this->value);
        return $c;
    }

    public function setModificationDate($_modificationDate) {
        $this->setValue($_modificationDate);
    }

    public function getModificationDate() {
        return $this->getValue();
    }

    public function toArray() {
        $array = array(
            'ModificationDate' => $this->getModificationDate()
        );
        return $array;
    }

}

?>
