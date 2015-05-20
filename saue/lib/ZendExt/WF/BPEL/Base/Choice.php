<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of choice
 *
 * 
 */
abstract class ZendExt_WF_BPEL_Base_Choice extends ZendExt_WF_BPEL_Base_XMLElement {

    protected $choices;
    protected $choosen;
    private $indice;

    public function __construct($parent, $tagName = null) {
        parent::__construct($parent, $tagName);
        $this->indice = 0;
    }

    //put your code here
    public function clonar() {
        return;
    }

    public function getChoices() {
        return $this->choices;
    }

    public function getSelectedItem() {
        return $this->choosen;
    }

    public function select($index) {
        if (array_key_exists($index, $this->choices)) {
            $this->choosen = $this->choices[$index];
            return $this->getSelectedItem();
        }
        return null;
    }

    public abstract function addChoice($choice);

    public abstract function selectItem($itemName);

    public function toArray() {
        return;
    }

    public function isEmpty() {
        return FALSE;
    }

}

?>
