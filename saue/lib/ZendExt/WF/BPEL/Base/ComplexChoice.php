<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ComplexChoice
 *
 * 
 */
abstract class ZendExt_WF_BPEL_Base_ComplexChoice extends ZendExt_WF_BPEL_Base_Choice {

    public function __construct($parent, $tagName = null) {
        parent::__construct($parent, $tagName);
        $this->initialize();
    }

    //put your code here

    public function get($_itemName) {
        try {
            $this->selectItem($_itemName);
            return $this->getSelectedItem();
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function selectItem($itemName) {
        $selected = $this->select($itemName);
        $oneSelected = $selected !== NULL;
        if (!$oneSelected) {
            throw new Exception($itemName . ' no es una opción válida.');
        } else {
            $this->create();
        }
    }
    
    public function getSelectedObject() {
        return $this->choices[$this->getSelectedItem()];
    }

    /*
     * Overriden methods
     */

    public function toName() {
        return $this->getSelectedItem()->toName();
    }

    protected function add($item) {
        $this->choices[$item] = $item;
    }
    
    public function addChoice($choice) {
        $this->add($choice);
    }

    public abstract function initialize();

    public abstract function create();
}

?>
