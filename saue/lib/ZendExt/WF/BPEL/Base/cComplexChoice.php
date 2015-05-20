<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cComplexChoice
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_Base_cComplexChoice extends ZendExt_WF_BPEL_Base_ComplexChoice {

    //put your code here
    public function __construct($parent, $choices, $tagName = null) {
        parent::__construct($parent, $tagName);

        foreach ($choices as $choice) {
            $this->addChoice($choice);
        }
    }

    public function initialize() {
        return;
    }

    public function create() {
        $selected = $this->getSelectedItem();
        $classPreffix = 'ZendExt_WF_BPEL_ModeloBPEL_';

        $className = $classPreffix . $selected;

        $classInstance = new $className($this);
        $this->choices[$selected] = $classInstance;
    }

}

?>
