<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IdRef
 *
 * @author sting
 */
class ZendExt_WF_WFObject_Entidades_IdRef extends ZendExt_WF_WFObject_Base_SimpleElement {

    //put your code here    
    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "IdRef";
    }

    public function clonar() {
        return;
    }

}

?>
