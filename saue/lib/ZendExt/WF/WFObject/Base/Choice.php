<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of choice
 *
 * @author yriverog
 */
abstract class ZendExt_WF_WFObject_Base_Choice extends ZendExt_WF_WFObject_Base_XMLElement {

    protected $choices;
    protected $choosen;
    protected $selectedIndex;

    private $indice;

    public function __construct($tagName, $parent, $selectedIndex = -1) {
        parent::__construct($parent);

        $this->indice = 0;
        $this->tagName = $tagName;
        $this->selectedIndex = $selectedIndex;
        $this->choosen = $this->choices[$this->selectedIndex];
    }
    
    //put your code here
    public function clonar() {
        return;
    }

    public function getChoices() {
        return $this->choices;
    }

    public function getSelectedItem() {
        /*
         * Por defecto, si no se ha seleccionado
         * ningun elemento, entonces el elemento
         * seleccionado sera el primero de las 
         * posibles opciones
         */
        if($this->selectedIndex == -1){
            $this->select(0);
        }
        return $this->choosen;
    }

    public function getSelectedIndex() {
        return $this->selectedIndex;
    }

    public function select($index) {
        $this->choosen = $this->choices[$index];
        $this->selectedIndex = $index;
    }

    protected function add($item) {
        $this->choices[$this->indice++] = $item;
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
