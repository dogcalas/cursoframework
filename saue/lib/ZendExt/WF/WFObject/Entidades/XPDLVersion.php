<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of XPDLVersion
 *
 * @author sting
 */
class ZendExt_WF_WFObject_Entidades_XPDLVersion extends ZendExt_WF_WFObject_Base_SimpleElement {

    //put your code here    
    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "XPDLVersion";
    }

    public function clonar() {
        $c = new ZendExt_WF_WFObject_Simple_XPDLVersion();
        $c->setValue($this->value);
        return $c;
    }

    public function setXPDLVersion($pxpdlVersion) {
        $this->setValue($pxpdlVersion);
    }

    public function getXPDLVersion() {
        return $this->getValue();
    }

    public function toArray() {
        $array = array(
            'XPDLVersion' => $this->getXPDLVersion()
        );
        return $array;
    }

}

?>
