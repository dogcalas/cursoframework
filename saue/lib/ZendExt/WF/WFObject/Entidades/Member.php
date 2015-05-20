<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Member
 *
 * @author yriverog
 */
class ZendExt_WF_WFObject_Entidades_Member extends ZendExt_WF_WFObject_Base_Complex {
    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Member';
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_DataTypes($this));
        return;
    }
}

?>
