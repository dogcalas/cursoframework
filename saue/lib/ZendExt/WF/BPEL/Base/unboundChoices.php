<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of unboundChoices
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_Base_unboundChoices extends ZendExt_WF_BPEL_Base_ComplexChoice {

    //put your code here
    private $objectsCollection;

    /*
     * Me disculpo por el uso de esta variable, de verdad, lo siento muchisimo.
     * Pero sucede que por el escaso tiempo que tengo no puedo pensar en algo mejor.
     */
    private $tempChoices;

    public function __construct($parent, $choices, $tagName = null) {
        $this->tempChoices = $choices;
        $this->objectsCollection = array();
        
        parent::__construct($parent, $tagName);
    }

    public function clonar() {
        return;
    }

    public function create() {
        $selected = $this->getSelectedItem();
        $classPreffix = 'ZendExt_WF_BPEL_ModeloBPEL_';
        $className = $classPreffix.$selected;
        return new $className($this);
    }

    public function initialize() {
        foreach ($this->tempChoices as $choice) {
            $this->addChoice($choice);
        }
    }

    public function addChoice($choice) {
        $this->add($choice);
    }

    public function add($object) {
        if(is_string($object)){
            parent::add($object);
        }  else {
            $this->objectsCollection[] = $object;   
        }        
    }

    public function get($index) {
        if (array_key_exists($index, $this->objectsCollection)) {
            return $this->objectsCollection[$index];
        } else {
            return null;
        }
    }

}

?>
