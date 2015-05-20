<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Group
 *
 * @author sting
 */
class ZendExt_WF_WFObject_Entidades_Group extends ZendExt_WF_WFObject_Base_Complex {

    //put your code here

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Group';
    }

    /*
     * Getters
     */

    public function getCategory() {
        return $this->get('Category');
    }

    public function getObject() {
        return $this->get('Object');
    }

    /*
     * Abstractions
     */

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Category($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Object($this));
        return;
    }

    public function toArray() {
        $array = array(
            'Id' => $this->getId(),
            'Name' => $this->getName(),
            'Category' => $this->getCategory()->toArray(),
            'Object' => $this->getObject()->toArray()
        );
        return $array;
    }

}

?>
